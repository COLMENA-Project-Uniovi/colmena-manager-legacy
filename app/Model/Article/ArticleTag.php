<?php

class ArticleTag extends AppModel {
    public $name = 'ArticleTag';


    //PATHS
    protected $content_path = "tags";
    protected $gallery_folder = "gallery";
    protected $attach_folder = "attach";

    //STANDARD RESOURCE NAMES
    protected $main_image_name = "principal";

    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty'
        )
    );

    public $actsAs = array(
        'Translate' => array(
            'name' => 'nametrans'
        )
    );
    public $hasAndBelongsToMany = array(
        'Article'
    );


    public function add($post_entity, $params = array()) {
        if (!$this->save($post_entity, $params)) {
            throw new Exception ("Ha habido un error añadiendo la etiqueta.");
        }

        return true;
    }

    public function edit($read_entity = null, $post_entity = null, $locale = null){
        $id = $read_entity[$this->name]['id'];
        $this->id = $id;
        $this->locale = $locale != null ? $locale : Configure::read("Config.language");
        $post_entity[$this->name]['modified'] = CakeTime::format(time(), "%Y-%m-%d %H:%M:%S");

        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error editando la etiqueta.");
        }

        return true;
    }

    public function remove($entity_id){
        if (!$this->delete($entity_id)) {
        throw new Exception("Ha habido un error borrando la etiqueta.");
        }
        //search folder and delete
        $dir = new Folder(parent::get_root_resources().$this->content_path.DS.$entity_id);

        $dir->delete();
        return true;
    }

    public function get_tags($tags_string){
        $tags_return = array();
        $tags = explode(",", $tags_string);

        if (!empty($tags_string)) {
            foreach ($tags as $tag) {
                $tag_found = $this->findAllByName(
                    $tag,
                    array(),
                    array(),
                    1,
                    1,
                    0
                );

                if(empty($tag_found)){
                    $this->id = null;
                    $tag_new = array(
                        "ArticleTag" => array(
                            "name" => $tag
                        )
                    );

                    if (!$this->save($tag_new)) {
                        throw new Exception("Ha habido un error guardando las etiquetas.");
                    }

                    $tag_new['ArticleTag']['id'] = $this->id;

                    array_push($tags_return, $tag_new['ArticleTag']['id']);
                }else{
                    array_push($tags_return, $tag_found[0]['ArticleTag']['id']);
                }
            }
        }

        return $tags_return;
    }
}


?>
