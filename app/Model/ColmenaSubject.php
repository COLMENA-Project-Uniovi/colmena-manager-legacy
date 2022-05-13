<?php

class ColmenaSubject extends AppModel {
	public $name = 'ColmenaSubject';
	public $useTable = "colmena_subject";

	public $hasAndBelongsToMany = array(
		"ColmenaUser" => array(			
            'className' => 'ColmenaUser',
            'joinTable' => 'colmena_user_subject',
            'foreignKey' => 'subject_id',
            'associationForeignKey' => 'user_id'
        )
	);

	public function add($post_entity){
		// Change date format
		$post_entity[$this->name]['start_date'] = CakeTime::format($post_entity[$this->name]['start_date'], "%Y-%m-%d");
		$post_entity[$this->name]['end_date'] = CakeTime::format($post_entity[$this->name]['end_date'], "%Y-%m-%d");		
		
		if (!$this->save($post_entity)) {			
			throw new Exception ("Ha habido un error añadiendo la asignatura.");
		}	
		return true;
	}

	public function edit($read_entity = null, $post_entity = null, $locale = null){
		$id = $read_entity[$this->name]['id'];
		$this->id = $id;
		$this->locale = $locale != null ? $locale : Configure::read("Config.language");

		//Change date format
		$post_entity[$this->name]['start_date'] = CakeTime::format($post_entity[$this->name]['start_date'], "%Y-%m-%d");
		$post_entity[$this->name]['end_date'] = CakeTime::format($post_entity[$this->name]['end_date'], "%Y-%m-%d");
	
		if (!$this->save($post_entity)) {
			throw new Exception ("Ha habido un error editando la asignatura.");
		}

		return true;
	}

	public function remove($entity_id){
		if (!$this->delete($entity_id, true)) {
            throw new Exception("Ha habido un error borrando la asignatura.");
        }
        return true;
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
?>