<?php

class Article extends AppModel {
    public $name = 'Article';

    //PATHS
    protected $content_path = "articles";
    protected $gallery_folder = "gallery";
    protected $attach_folder = "attach";

    //STANDARD RESOURCE NAMES
    protected $main_image_name = "principal";

    public $validate = array(
        'title' => array(
            'rule' => 'notEmpty'
        ),
        'published' => array(
            'rule' => array('date', 'ymd'),
            'allowEmpty' => false,
            'message' => 'Debes introducir una fecha válida del tipo DD-MM-YYYY.'
        )
    );

    public $actsAs = array(
        'Translate' => array(
            'title' => 'titletrans',
            'excerpt' => 'excerpttrans',
            'content' => 'contenttrans'
        ),
        'Seo'
    );
    public $hasMany = array(
        'ArticleVersion' => array(
            'conditions' => array( 'recover' => '0' ),
            'order' => 'ArticleVersion.created DESC'
        )
    );
    public $hasAndBelongsToMany = array(
        'ArticleTag'
    );


    public function add($post_entity, $params = array()) {
        $post_entity[$this->name]['published'] = CakeTime::format($post_entity[$this->name]['published'], "%Y-%m-%d");
        if($post_entity[$this->name]['title'] == ''){
            $post_entity[$this->name]['title'] = '(Sin título)';
        }

        $post_entity['ArticleTag'] = $this->ArticleTag->get_tags($post_entity[$this->name]['tags']);

        if (!$this->save($post_entity, $params)) {
            throw new Exception ("Ha habido un error añadiendo la noticia.");
        }
        // Upload media
        $status = parent::save_main_image($this->id, $post_entity[$this->name]['main_img']);
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

        if(!empty($post_entity[$this->name]['tags'])){
            $post_entity['ArticleTag'] = $this->ArticleTag->get_tags($post_entity[$this->name]['tags']);
        }

        if(isset($post_entity[$this->name]['published'])){
            $post_entity[$this->name]['published'] = CakeTime::format($post_entity[$this->name]['published'], "%Y-%m-%d");
        }

        if($post_entity[$this->name]['title'] == ''){
            $post_entity[$this->name]['title'] = '(Sin título)';
        }

        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error editando la noticia.");
        }

        // IF TAGS IS EMPTY DELETE ALL TAGS FROM ARTICLE
        if(empty($post_entity[$this->name]['tags'])){
            $this->delete_tags($id);
        }

        // Upload media if is not autosave
        if(!empty($post_entity[$this->name]['main_img'])){
            $status = parent::save_main_image($id, $post_entity[$this->name]['main_img']);
            $status = parent::save_gallery($id, $post_entity[$this->name]['gallery_img']);
            //if error
            if($status !== true){
                throw new Exception("Ha habido un error subiendo las imágenes o ficheros adjuntos.");
            }
        }
        return true;
    }

    public function edit_autosave($read_entity = null, $post_entity = null, $locale = null) {
        $id = $read_entity[$this->name]['id'];
        $this->id = $id;
        $this->locale = $locale != null ? $locale : Configure::read("Config.language");

        $post_entity[$this->name]['published'] = CakeTime::format($post_entity[$this->name]['published'], "%Y-%m-%d");
        if($post_entity[$this->name]['title'] == ''){
            $post_entity[$this->name]['title'] = '(Sin título)';
        }

        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error editando la noticia.");
        }

        return true;
    }

    public function remove($entity_id){
        if (!$this->delete($entity_id)) {
            throw new Exception("Ha habido un error borrando la noticia.");
        }
        //search folder and delete
        $dir = new Folder(parent::get_root_resources().$this->content_path.DS.$entity_id);

        $dir->delete();
        return true;
    }

    public function get_article_tags($id){
        $article_tags = $this->ArticleTag->find(
            "list",
            array(
                'joins' => array(
                    array('table' => 'article_tags_articles',
                        'alias' => 'Tag',
                        'type' => 'INNER',
                        'conditions' => array(
                            'AND' => array(
                                'Tag.article_id = '.$id,
                                'Tag.article_tag_id = ArticleTag.id'
                            )
                        )
                    )
                )
            )
        );

        return $article_tags;
    }

    public function delete_tags($article_id) {
        if (!$this->ArticleTagsArticle->deleteAll(
            array(
                'ArticleTagsArticle.article_id' => $article_id,
                false
            )
        )) {
            throw new Exception("Ha habido un error editando los tags de la noticia. Por favor, inténtalo de nuevo más tarde.");

        }
    }
}


?>
