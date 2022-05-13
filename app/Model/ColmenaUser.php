<?php
class ColmenaUser extends AppModel {
    public $name = 'ColmenaUser';
    public $useTable = "colmena_user";

    public $hasAndBelongsToMany = array(
        "ColmenaSubject" => array(         
            'className' => 'ColmenaSubject',
            'joinTable' => 'colmena_user_subject',
            'foreignKey' => 'user_id',
            'associationForeignKey' => 'user_id'
            )
        );

    public function add($post_entity) {
        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error añadiendo los datos de contacto.");
        }

        return true;
    }

    public function edit($read_entity = null, $post_entity = null) {
        $id = $read_entity[$this->name]['id'];
        $this->id = $id;

        $post_entity[$this->name]['modified'] = CakeTime::format(time(), "%Y-%m-%d %H:%M:%S");

        if (!$this->save($post_entity)) {
            throw new Exception ("Ha habido un error editando los datos de contacto.");
        }

        return true;
    }

}
?>
