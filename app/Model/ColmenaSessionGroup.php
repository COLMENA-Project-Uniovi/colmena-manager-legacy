<?php

class ColmenaSessionGroup extends AppModel {
	public $name = 'ColmenaSessionGroup';
	public $useTable = "colmena_session_group";


	public $belongsTo = array(
		"ColmenaSubject" => array(			
            'className' => 'ColmenaSubject',            
            'foreignKey' => 'subject_id'
        ),
        "ColmenaGroup" => array(			
            'className' => 'ColmenaGroup',            
            'foreignKey' => 'group_id'
        ),
        "ColmenaSession" => array(			
            'className' => 'ColmenaSession',            
            'foreignKey' => 'session_id'
        ),
	);


	public function add($post_entity){		
		$post_entity[$this->name]['session_day'] = CakeTime::format($post_entity[$this->name]['session_day'], "%Y-%m-%d");

		if (!$this->save($post_entity)) {			
			throw new Exception ("Ha habido un error añadiendo la clase.");
		}	
		return true;
	}

	public function edit($read_entity = null, $post_entity = null, $locale = null){
		$id = $read_entity[$this->name]['id'];
		$this->id = $id;
		$this->locale = $locale != null ? $locale : Configure::read("Config.language");
	
		if (!$this->save($post_entity)) {
			throw new Exception ("Ha habido un error editando la clase.");
		}

		return true;
	}

	public function remove($entity_id){
		if (!$this->delete($entity_id, true)) {
            throw new Exception("Ha habido un error borrando la clase.");
        }
        return true;
	}

}
?>