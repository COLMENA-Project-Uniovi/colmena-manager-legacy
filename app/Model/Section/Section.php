<?php
class Section extends AppModel{
    public $name = "Section";

    public $actsAs = array(
        'Tree',
        'Translate' => array(
            'name' => 'nametrans'
        ),
        'Seo'
    );

    //PATHS
    protected $content_path = "sections";
    protected $gallery_folder = "gallery";

    //STANDARD RESOURCE NAMES
    protected $main_image_name = "principal";

    public $hasMany = array(
        'SectionPartImage',
        'SectionFeatured',
        'SectionGallery',
        'SectionTab'
    );

    public $validate = array(
        'name' => array(
            'rule'        => array('notEmpty'),
            'allowEmpty'  => false,
            'message'     => 'Debes completar este campo.'
        ),
        'folder' => array(
            'rule'        => array('notEmpty'),
            'allowEmpty'  => false,
            'message'     => 'Debes completar este campo.'
        )
    );

    public $findMethods = array(
        'treepath' =>  true,
        'treepathcomplete' =>  true
    );


    public function add($post_entity){

        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error añadiendo la sección.");
        }


        // Upload media
        //$status = parent::save_main_image($this->id, $post_entity[$this->name]['main_img']);
        $status = parent::save_gallery($this->id, $post_entity[$this->name]['gallery_img']);

        //if error
        if($status !== true){
            throw new Exception ("Ha habido un error subiendo las imágenes o ficheros adjuntos.");
        }

        return true;
    }

    public function edit($read_entity = null, $post_entity = null, $locale = null){
        $id = $read_entity[$this->name]['id'];
        $this->id = $id;
        $this->locale = $locale != null ? $locale : Configure::read("Config.language");
        $post_entity[$this->name]['modified'] = CakeTime::format(time(), "%Y-%m-%d %H:%M:%S");

        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error editando la sección.");
        }


        // Upload media
        //$status = parent::save_main_image($id, $post_entity[$this->name]['main_img']);
        $status = parent::save_gallery($id, $post_entity[$this->name]['gallery_img']);

        //if error
        if($status !== true){
            throw new Exception("Ha habido un error subiendo las imágenes o ficheros adjuntos.");
        }

        return true;
    }

    public function remove($entity_id){
        if (!$this->delete($entity_id)) {
            throw new Exception("Ha habido un error borrando la sección.");
        }

        //search folder and delete
        $dir = new Folder(parent::get_root_resources().$this->content_path.DS.$entity_id);

        $dir->delete();
        return true;
    }

    public function get_ordered_parts($locale = null){
        if($locale == null){
            $locale = Configure::read("Config.language");
        }
        $full_array = array();
        $full_array['parts'] = array();
        $full_array['order'] = array();
        $full_array['parts_aux'] = array();

        $full_array = $this->get_parts(
            $this->SectionPartImage,
            "SectionPartImage",
            "section_part_images",
            $this->id,
            $locale,
            $full_array);

        $full_array = $this->get_parts(
            $this->SectionFeatured,
            "SectionFeatured",
            "section_featureds",
            $this->id,
            $locale,
            $full_array);

        $full_array = $this->get_parts(
            $this->SectionGallery,
            "SectionGallery",
            "section_galleries",
            $this->id,
            $locale,
            $full_array);

        $full_array = $this->get_parts(
            $this->SectionTab,
            "SectionTab",
            "section_tabs",
            $this->id,
            $locale,
            $full_array);

        array_multisort($full_array['order'], SORT_ASC, $full_array['parts_aux'], $full_array['parts']);
        return $full_array['parts'];
    }

    private function get_parts($find_object, $class_name, $path, $id, $locale, $full_array){

        // OBTAIN PART IMAGES
        $section_parts = $find_object->find(
            "all",
            array(
                'conditions' => array(
                    $class_name.'.section_id' => $id,
                    $class_name.'.locale' => $locale
                ),
                'order' => array(
                    $class_name.'.sort' => 'asc'
                ),
                'recursive' => '0'
            )
        );


        //$section_parts = parent::add_media_data_parts($section_parts, $class_name, $this->content_path.$path, 'principal');

        $options = array(
            'entity_name' => $class_name,
            'content_path' => $this->content_path. DS .$path,
            'main_image_name' => 'principal',
            'gallery_folder' => 'gallery',
            'attach_folder' => 'attach'
        );

        $section_parts = parent::add_media_data_bulk($section_parts, $options);

        foreach ($section_parts as $part) {
            $full_array['order'][$part[$class_name]['id'].$class_name] = $part[$class_name]['sort'];
            $full_array['parts_aux'][$part[$class_name]['id'].$class_name] = $part;
            $full_array['parts'][$part[$class_name]['id'].$class_name] = $part;
        }

        return $full_array;
    }

    protected function _findTreepath($state, $query, $results = array()) {
        if ($state === 'before') {
            return $query;
        }

        // set some default query and get some from the parameters if set
        $pathField = 'tree_path';
        $labelField = 'name';
        if (isset($query['pathField'])) {
            $pathField = $query['pathField'];
            unset($query['pathField']);
        }
        if (isset($query['labelField'])) {
            $labelField = $query['labelField'];
            unset($query['labelField']);
        }

        // find the specified rows and return something like find('list') does
        $this->Behaviors->detach("Translate");
        $query['order'] = array('Section.lft' => 'ASC');
        $results_all = $this->find('all', $query);

        $results = array();
        foreach ($results_all as $i=>$result) {
            $this->_setTreePath($result, $pathField, $labelField);
            $results[$result[$this->name][$this->primaryKey]] = $result[$this->name][$pathField];
        }
        return $results;
    }

    protected function _findTreepathcomplete($state, $query, $results = array()) {
        if ($state === 'before') {
            return $query;
        }

        // set some default query and get some from the parameters if set
        $pathField = 'tree_path';
        $labelField = 'name';
        if (isset($query['pathField'])) {
            $pathField = $query['pathField'];
            unset($query['pathField']);
        }
        if (isset($query['labelField'])) {
            $labelField = $query['labelField'];
            unset($query['labelField']);
        }

        // find the specified rows and return something like find('list') does
        $this->Behaviors->detach("Translate");
        $query['order'] = array('Section.lft' => 'ASC');
        $results_all = $this->find('all', $query);

        $results = array();
        foreach ($results_all as $i=>$result) {
            $this->_setTreePath($result, $pathField, $labelField);
            //$results[$result[$this->name][$this->primaryKey]] = $result[$this->name][$pathField];
            unset($result['ParentSection']);
            unset($result['ChildSection']);
            array_push($results, $result);
        }
        return $results;
    }


    protected function _setTreePath(&$data, $pathField, $label) {
        $cats = $this->getPath($data[$this->name][$this->primaryKey]);
        $path = array();
        foreach ($cats as $cat) {
            array_push($path, $cat[$this->name][$label]);
        }
        $data[$this->name][$pathField] = implode(' » ', $path);
    }



}
?>
