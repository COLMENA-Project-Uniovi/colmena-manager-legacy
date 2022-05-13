<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');
App::uses('NeoFileUploader', 'Lib');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('CakeTime', 'Utility');
/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {

    public $actsAs = array('Containable');
    
    //PATHS
    protected $content_path = "";
    protected $gallery_folder = "";
    protected $attach_folder = "";

    //STANDARD RESOURCE NAMES
    protected $main_image_name = "";

    // GET FULL ENTITY WITH TRANSLATIONS
    public function get_entity($id = null, $locale = null){
        if(!$id){
            return null;
        }

        if($locale == null){
            $locale = Configure::read("Config.language");
        }

        $this->id = $id;
        $this->locale = $locale;
        $entity = $this->read();

        if($locale != Configure::read("Config.language")){
            // Create "fake" entity
            $this->locale = Configure::read("Config.language");
            $entity['default'] = $this->read();

            $full_fields = array_keys($entity['default'][$this->name]);
            $translate_fields = array_keys($this->actsAs['Translate']);
            $not_translate_fields = array_diff($full_fields, $translate_fields);

            foreach ($not_translate_fields as $value) {
                $entity[$this->name][$value] = $entity['default'][$this->name][$value];
            }

            $entity[$this->name]['id'] = $id;
            $entity[$this->name]['locale'] = $locale;
        }

        return $entity;

    }

    // GET ROOT RESOURCE
    public function get_root_resources(){
        $exploded_url = (explode(DS, ROOT));
        array_pop($exploded_url);
        return implode(DS, $exploded_url).DS."resources".DS;
    }

    // GET PATH OF EXTRA IMAGES
    protected function get_extra_images_path($resources_folder, $specific_entity_folder){
        $exploded_url = (explode(DS, ROOT));
        array_pop($exploded_url);
        $base_root =  implode(DS, $exploded_url) . $resources_folder . $specific_entity_folder;

        return $base_root;
    }

    // FOLDER FILES
    protected function get_folder_files($dir, $only_images = false){
        $file_urls = array();

        $directory = new Folder($this->get_root_resources() . $dir);
        $directory->sort;

        if($directory->path != ""){
            $files = $directory->read(true, true);
            if(!empty($files[1])){
                foreach($files[1] as $file){
                    if($only_images){
                        if(getimagesize($directory->path. DS .$file)){
                            array_push($file_urls, $dir. DS .$file);
                        }
                    }else{
                        array_push($file_urls, $dir. DS .$file);
                    }
                }
            }
        }

        return $file_urls;
    }

    public function save_main_image($id, $image){
        $path = $this->content_path. DS . $id;
        if (empty($image) || $image['error'] != 0) {
            return true;
        }
        return  $this->upload_file($path, $image, $this->main_image_name, true);
    }

    // propio de asturmasa. Para añadir el segundo logo en gris
    public function save_secondary_image($id, $image){
        $path = $this->content_path. DS . $id;
        if (empty($image) ||$image['error'] != 0) {
            return true;
        }
        return  $this->upload_file($path, $image, $this->black_image_name, true);
    }

    public function save_gallery($id, $gallery){
        if(empty($gallery) || empty($gallery[0])){
            return true;
        }
        $path = $this->content_path. DS . $id;
        //upload extra images
        $path_gallery = $path.DS.$this->gallery_folder;

        $status = true;
        foreach ($gallery as $image) {
            if($image['error'] != 0){
                $status = $status && true;
            }else{
                $pathinfo = pathinfo($image['name']);
                $status = $status && $this->upload_file($path_gallery, $image, $pathinfo['filename'], true);
            }
        }
        return $status;
    }

    public function save_attachments($id, $atachments){
        if(empty($gallery) || empty($attachments[0])){
            return true;
        }
        $path = $this->content_path. DS . $id;

        //upload extra attachments
        $path_attach = $path.DS.$this->attach_folder;

        $status = true;
        foreach ($atachments as $attach) {
            if($attach['error'] != 0){
                $status = $status && true;
            }else{
                $pathinfo = pathinfo($attach['name']);
                $status = $status && $this->upload_file($path_attach, $attach, $pathinfo['filename'], false);
            }
        }
        return $status;
    }


    // UPLOAD - IMAGES
    public function upload_file($path, $file, $file_name, $is_image = true){
        //open folder
        $dir = new Folder($this->get_root_resources().$path);

        //if path does not exist create new one
        if($dir->path == null){
            $dir = new Folder($this->get_root_resources().$path, true, 0777);
        }

        if($file['error'] == 0){
            try{
                // Delete files with same name
                $old_files = $dir->find($file_name.".*", true);
                foreach ($old_files as $old_file_name) {
                    $old_file = new File($dir->path.DS.$old_file_name, false, 0777);
                    $old_file->delete();
                }
                //instance the uploader
                $uploader = new NeoFileUploader();
                $uploader -> upload_file($file, $dir->path, $file_name, $is_image);
            }catch(Exception $e){
                return $e->getMessage();
            }
        }
        return true;
    }

    // URL OF SPECIFIC FILE
    protected function get_file_url($folder, $file_name, $id){

        //open the dir
        $root = $this->get_root_resources();

        $dir = new Folder($root.$folder.DS.$id);
        //search the logo
        $file_url = $dir->find($file_name.".*", true);

        if(count($file_url) == 0){
            return false;
        }

        return $folder. DS . $id . DS . $file_url[0];
    }

    // MERGE ALL DATA
    protected function mergeEntityData($entityname, $olddata, $newdata){
        return array($entityname => array_merge($olddata, $newdata));
    }

    //ADD MEDIA DATA
    public function add_media_data($entity, $options = array()){
        $default = array(
            'entity_name' => $this->name,
            'content_path' => $this->content_path,
            'main_image_name' => $this->main_image_name,
            'black_image_name' => $this->black_image_name,
            'gallery_folder' => $this->gallery_folder,
            'attach_folder' => $this->attach_folder
        );

        $options = array_merge($default, $options);

        $entity[$options['entity_name']]['content_path'] = $options['content_path'];
        $entity[$options['entity_name']]['img_url'] = $this->get_file_url(
            $options['content_path'],
            $options['main_image_name'],
            $entity[$options['entity_name']]['id']);

        $entity[$options['entity_name']]['black_img_url'] = $this->get_file_url(
            $options['content_path'],
            $options['black_image_name'],
            $entity[$options['entity_name']]['id']);
        $entity[$options['entity_name']]['gallery'] = $this->get_folder_files(
            $options['content_path'] . DS . $entity[$options['entity_name']]['id'] . DS . $options['gallery_folder'],
            true);
        $entity[$options['entity_name']]['attach'] = $this->get_folder_files(
            $options['content_path'] . DS . $entity[$options['entity_name']]['id'] . DS . $options['attach_folder']);

        return $entity;
    }

    public function add_media_data_bulk($entities, $options = array()){
        $completed_entities = array();
        foreach($entities as $entity){
            $entity = $this->add_media_data($entity, $options);
            array_push($completed_entities, $entity);
        }
        return $completed_entities;
    }


    //FOR PARTS
    protected function add_media_data_parts($array_entities, $entity_name, $content_path, $img_name){
        $array_modified = array();

        foreach ($array_entities as $entity) {
            if($entity_name == ""){
                $entity['img_url'] = $this->get_file_url($content_path, $img_name, $entity['id']);
                $entity['gallery'] = $this->get_folder_files($content_path . DS . $entity['id'] . DS . "gallery", true);
                $entity['attach'] = $this->get_folder_files($content_path . DS . $entity['id'] . DS . "attach");
            }else{
                $entity[$entity_name]['img_url'] = $this->get_file_url($content_path, $img_name, $entity[$entity_name]['id']);
                $entity[$entity_name]['gallery'] = $this->get_folder_files($content_path . DS . $entity[$entity_name]['id'] . DS . "gallery", true);
                $entity[$entity_name]['attach'] = $this->get_folder_files($content_path . DS . $entity[$entity_name]['id'] . DS . "attach");
            }
            array_push($array_modified, $entity);
        }

        return $array_modified;
    }


    //CHANGE A BOOLEAN PROPERTY
    public function change_property($current_status, $property_name){
        //control visibility
        if($current_status){
            $status = $this->saveField($property_name, "0");
        }else{
            $status = $this->saveField($property_name, "1");
        }

        return $status;
    }


    //DELETE AN INTERNAL RESOURCE
    public function delete_resource($entity_id, $entity_path,  $folder, $filename){
        //recover the file
        $file = new File($this->get_root_resources(). $entity_path .DS. $entity_id .DS. $folder .DS. $filename);
        //remove the file
        return $file->delete();
    }

    //SLUGIFY FUNCTIONS
    static public function slugify($text) {
      // replace non letter or digits by -
      $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

      // trim
      $text = trim($text, '-');

      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

      // lowercase
      $text = strtolower($text);

      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);

      if (empty($text))
      {
        return 'n-a';
      }

      return $text;
    }

    public function createTableForMarkers($table_name){
        $query = sprintf("CREATE TABLE %s 
            (   `user_id` varchar(20) NOT NULL,
                `error_id` int(15) NOT NULL,
                `gender` varchar(20) NOT NULL,
                `timestamp` varchar(20) NOT NULL,
                `path` text NOT NULL,
                `class_name` varchar(40) NOT NULL,
                `ip` varchar(20) NOT NULL,
                `project` text NOT NULL,
                `project_name` varchar(40) NOT NULL,
                `line_number` int(4) NOT NULL,
                `custom_message` varchar(250) NOT NULL,
                `_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `colmena_compilation_id` varchar(100) NOT NULL, 
                `colmena_session_group_id` int(3) UNSIGNED DEFAULT NULL, 
                `active` int(11) NOT NULL DEFAULT '1' )",
                $table_name
        );

        $this->query($query);
    }
}
