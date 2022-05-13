<?php
class Gallery extends AppModel{
    public $name = "Gallery";

    public $actsAs = array(
        'Translate' => array(
            'name' => 'nametrans',
            'content' => 'contenttrans'
        ),
        'Seo'
    );

    //PATHS
    protected $content_path = "sections";
    protected $gallery_folder = "gallery";

    //STANDARD RESOURCE NAMES
    protected $main_image_name = "principal";

    public $hasMany = array(
        'GalleryImage'
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

}
?>
