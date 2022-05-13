<?php
class ColmenaSessionGroupsController extends AppController{
	public $name = "ColmenaSessionGroups";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'week' => 'asc'
		)
	);


	public function index($subject_id = null) {
		$this->loadModel('ColmenaSessionGroup');
		//recover the paginate default configuration
		$settings = $this->paginate;
		// If performing search, there is a keyword
		if($subject_id != null){
			// Change pagination conditions for searching
			$settings['conditions'] = array(
				$this->modelClass.'.subject_id ' => $subject_id	
			);
		}

		//prepare the pagination
		$this->Paginator->settings = $settings;

		//recover the data
		$entities = $this->Paginator->paginate($this->model_name);
		//$entities = $this->ColmenaSessionGroup->find("all", array(
		//	'conditions' => array(
		//		$this->modelClass.'.subject_id ' => $subject_id	
		//		)
		//	)
		//);

		$this->set('subject', $this->{$this->modelClass}->read());

        $this->set('entities', $entities);	
	}



	public function add($subject_id = null) {
		
		$this->loadModel('ColmenaSession');
		$this->loadModel('ColmenaGroup');
		// Exception control
        $flag = false;

		if ($this->request->is('post')) {
			$entity = $this->request->data;
			try{
				$add_result = $this->{$this->modelClass}->add($entity);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);


                $this->request->data = $entity;
                $flag = true;
			}

			if(!$flag){
				$this->Session->setFlash(
					'<p>Clase práctica añadida correctamente.</p>',
					'flash_ok'
				);
				return $this->redirect(array('action' => 'index', $subject_id));
			}
		}

		$sessions = $this->ColmenaSession->find("all", array(
			'fields' => array('id', 'session_name_es'),
			'conditions' => array(
				'ColmenaSession.visible' => 1,
				'ColmenaSession.subject_id' => $subject_id
				)
			)
		);

		$groups = $this->ColmenaGroup->find("all", array(
			'fields' => array('id', 'group_name'),
			'conditions' => array(
				'ColmenaGroup.subject_id' => $subject_id
				)
			)
		);

		$sessions_modified = array();
		foreach ($sessions as $key => $value) {
			$sessions_modified[$value['ColmenaSession']['id']] = $value['ColmenaSession']['session_name_es'];
		}

		$groups_modified = array();
		foreach ($groups as $key => $value) {
			$groups_modified[$value['ColmenaGroup']['id']] = $value['ColmenaGroup']['group_name'];
		}


		$this->set('sessions', $sessions_modified);
		$this->set('groups', $groups_modified);
	}


	public function edit($id = null, $subject_id = null) {
		$this->loadModel('ColmenaSession');
		$this->loadModel('ColmenaGroup');
		// Exception control
        $flag = false;
		//Recover the entity
		$entity = $this->{$this->modelClass}->get_entity($id, null);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index', $subject_id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				// Recover data
                $new_entity = $this->request->data;

				//Edit the entity
				$this->{$this->modelClass}->edit($entity, $new_entity, null);

				// Re-assign data
                $entity = $this->{$this->modelClass}->get_entity($id, null);

			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);

				$flag = true;
			}

			if (!$flag) {
                $this->Session->setFlash(
                    '<p>Clase editada correctamente.</p>',
                    'flash_ok'
                );
            }
        }
		//re-asign the data
		$entity = $this->{$this->modelClass}->add_media_data($entity);

		$sessions = $this->ColmenaSession->find("all", array(
			'fields' => array('id', 'session_name_es'),
			'conditions' => array(
				'ColmenaSession.visible' => 1,
				'ColmenaSession.subject_id' => $subject_id
				)
			)
		);

		$groups = $this->ColmenaGroup->find("all", array(
			'fields' => array('id', 'group_name'),
			'conditions' => array(
				'ColmenaGroup.subject_id' => $subject_id
				)
			)
		);

		$sessions_modified = array();
		foreach ($sessions as $key => $value) {
			$sessions_modified[$value['ColmenaSession']['id']] = $value['ColmenaSession']['session_name_es'];
		}

		$groups_modified = array();
		foreach ($groups as $key => $value) {
			$groups_modified[$value['ColmenaGroup']['id']] = $value['ColmenaGroup']['group_name'];
		}


		$this->set('sessions', $sessions_modified);
		$this->set('groups', $groups_modified);	
		
		$this->request->data = $entity;
		
		//put data in session
		$this->set('entity', $entity);	

		
	}

	public function delete($id = null, $subject_id = null) {
		// Exception control
        $flag = false;
		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		//recover the entity
		$this->{$this->modelClass}->id = $id;
		$entity = $this->{$this->modelClass}->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index',$this->params['pass'][0]));
		}

		try{			
			$this->{$this->modelClass}->remove($id);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>',
				'flash_error'
			);
			$flag = true;
        }

        if (!$flag) {
            $this->Session->setFlash(
                '<p>Clase eliminada correctamente.</p>',
                'flash_ok'
            );
        }

        return $this->redirect(array('action' => 'index', $subject_id));
	}

	public function delete_resource($id = null, $folder, $filename = null){
		// Exception control
        $flag = false;
        //if is a post error
        if($this->request->is('post')){
            return $this->redirect(array('action' => 'index'));
        }

        try{
            $this->{$this->modelClass}->delete_resource($id, $this->{$this->modelClass}->content_path, $folder, $filename);

        }catch(Exception $e){
            $this->Session->setFlash(
                '<p>'. $e->getMessage() .'</p>',
                'flash_error'
            );

            $flag = true;
        }

        if (!$flag) {
            $this->Session->setFlash(
                '<p>Archivo eliminado correctamente.</p>',
                'flash_ok'
            );
        }

        //final redirection
        return $this->redirect(array('action' => 'edit', $id));
	}
}
?>
