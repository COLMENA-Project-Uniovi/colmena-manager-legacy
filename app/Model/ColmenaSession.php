<?php

class ColmenaSession extends AppModel {
	public $name = 'ColmenaSession';
	public $useTable = "colmena_session";


	public $belongsTo = array(
		"ColmenaSubject" => array(			
            'className' => 'ColmenaSubject',            
            'foreignKey' => 'subject_id'
        )
	);


	public function add($post_entity){		
		if (!$this->save($post_entity)) {			
			throw new Exception ("Ha habido un error añadiendo la sesión.");
		}	
		return true;
	}

	public function edit($read_entity = null, $post_entity = null, $locale = null){
		$id = $read_entity[$this->name]['id'];
		$this->id = $id;
		$this->locale = $locale != null ? $locale : Configure::read("Config.language");
	
		if (!$this->save($post_entity)) {
			throw new Exception ("Ha habido un error editando la sesión.");
		}

		return true;
	}

	public function remove($entity_id){
		if (!$this->delete($entity_id, true)) {
            throw new Exception("Ha habido un error borrando la sesión.");
        }
        return true;
	}

}
?>