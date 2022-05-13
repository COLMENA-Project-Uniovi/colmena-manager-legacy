<?php

class ArticleVersion extends AppModel {
    public $name = 'ArticleVersion';

    public $belongsTo = 'Article';

    public $actsAs = array(
        'Translate' => array(
            'title' => 'titletrans',
            'excerpt' => 'excerpttrans',
            'content' => 'contenttrans'
        )
    );

    public $validate = array(

    );

    public function add($post_entity, $locale = null){
        $this->locale = $locale != null ? $locale : Configure::read("Config.language");

        if($post_entity['ArticleVersion']['title'] == ''){
            $post_entity['ArticleVersion']['title'] = '(Sin título)';
        }

        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error añadiendo la versión.");
        }
        return true;
    }

    public function edit($id, $post_entity = null, $locale = null){
        $this->id = $id;
        $this->locale = $locale != null ? $locale : Configure::read("Config.language");
        $post_entity[$this->name]['modified'] = CakeTime::format(time(), "%Y-%m-%d %H:%M:%S");

        if($post_entity['ArticleVersion']['title'] == ''){
            $post_entity['ArticleVersion']['title'] = '(Sin título)';
        }

        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error editando la noticia.");
        }

        return true;
    }

    public function remove($entity_id){
        if (!$this->delete($entity_id)) {
            throw new Exception("Ha habido un error borrando la noticia");
        }
        return true;
    }
}
?>
