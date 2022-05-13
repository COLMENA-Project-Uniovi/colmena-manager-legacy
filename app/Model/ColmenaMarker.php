<?php

class ColmenaMarker extends AppModel {
	public $name = 'ColmenaMarker';
	public $useTable = "colmena_marker";

	public $belongsTo = array(
		"ColmenaUser" => array(			
            'className' => 'ColmenaUser',
            'foreignKey' => 'user_id'
            )
		);


	public function add($post_entity){
		// Change date format
		$post_entity[$this->name]['published'] = CakeTime::format($post_entity[$this->name]['published'], "%Y-%m-%d");

		$post_entity['Service'] = $this->Service->get_services($post_entity[$this->name]['services']);
		
		
		if (!$this->save($post_entity)) {			
			throw new Exception ("Ha habido un error añadiendo el marcador.");
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
			throw new Exception ("Ha habido un error editando el marcador.");
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
			throw new Exception("Ha habido un error borrando el marcador");
		}
		
		return true;
	}


	public function insert($entity, $table_name, $session_id){
		
		$query = sprintf("INSERT INTO %s (user_id, error_id, gender, session_id, path, timestamp, class_name, project, project_name, line_number, custom_message, ip, active) 
			VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
				$table_name,
				$entity['user_id'],
				$entity['error_id'],
				$entity['gender'],
				$session_id,
				$entity['path'],
				$entity['timestamp'],
				$entity['class_name'],
				$entity['project'],
				$entity['project_name'],
				$entity['line_number'],
				$entity['custom_message'],
				$entity['ip'],
				'1'
		);

		$this->query($query);
	}

}
?>