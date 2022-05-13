<?php

class ColmenaCompilation extends AppModel {
	public $name = 'ColmenaCompilation';
	public $useTable = "colmena_compilations";

	public $belongsTo = array(
		"ColmenaUser" => array(			
            'className' => 'ColmenaUser',
            'foreignKey' => 'user_id'
            
		)
	);

	public $hasMany = array(
		"ColmenaMarkers" => array(			
            'className' => 'ColmenaMarker',
            'foreignKey' => 'colmena_compilation_id'
		)
	);


	public function add($post_entity){
		// Change date format
		$post_entity[$this->name]['published'] = CakeTime::format($post_entity[$this->name]['published'], "%Y-%m-%d");

		$post_entity['Service'] = $this->Service->get_services($post_entity[$this->name]['services']);
		
		
		if (!$this->save($post_entity)) {			
			throw new Exception ("Ha habido un error añadiendo la propiedad.");
		}

		// Upload media
		$status = parent::save_main_image($this->id, $post_entity[$this->name]['main_img']);
		$status = $status && parent::save_gallery($this->id, $post_entity[$this->name]['gallery_img']);
		$status = $status && parent::save_attachments($this->id, $post_entity[$this->name]['attach']);

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

		//Change date format
		$post_entity[$this->name]['published'] = CakeTime::format($post_entity[$this->name]['published'], "%Y-%m-%d");

		$post_entity['Service'] = $this->Service->get_services($post_entity[$this->name]['services']);
	
		if (!$this->save($post_entity)) {
			throw new Exception ("Ha habido un error editando la propiedad.");
		}

		
		// Upload media
		$status = parent::save_main_image($id, $post_entity[$this->name]['main_img']);
		$status = $status && parent::save_gallery($id, $post_entity[$this->name]['gallery_img']);
		$status = $status && parent::save_attachments($id, $post_entity[$this->name]['attach']);
		
		//if error
		if($status !== true){
			throw new Exception("Ha habido un error subiendo las imágenes o ficheros adjuntos.");
		}

		return true;
	}

	public function remove($entity_id){
		if (!$this->delete($entity_id)) {
			throw new Exception("Ha habido un error borrando la compilación");
		}
	}

	public function insert($entity, $table_name){
		$subject_table = explode('_', $table_name);
		array_shift($subject_table);
		array_shift($subject_table);
		$subject_table = implode('_', $subject_table);	
		$compilations_table_name = "colmena_compilations_" . $subject_table;

		$query = sprintf("INSERT INTO %s (id, user_id, session_id, type, timestamp, num_markers, project_name, class_name, ip) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
				$compilations_table_name,
				$entity[$this->name]['id'],
				$entity[$this->name]['user_id'],
				$entity[$this->name]['session_id'],
				$entity[$this->name]['type'],
				$entity[$this->name]['timestamp'],
				$entity[$this->name]['num_markers'],
				$entity[$this->name]['project_name'],
				$entity[$this->name]['class_name'],
				$entity[$this->name]['ip']
		);

		$this->query($query);
	}

}
?>