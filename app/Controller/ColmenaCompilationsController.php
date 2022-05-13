<?php
class ColmenaCompilationsController extends AppController{
	public $name = "ColmenaCompilations";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'visible' => 'desc',
			'academic_year' => 'desc'
		)
	);


	public function index($keyword = null) {
		//if search
		if ($this->request->is('post')) {
			//recover the keyword
			$keyword = $this->request->data[$this->model_name]['keyword'];
			//re-send to the same controller with the keyword
			return $this->redirect(array('action' => 'index', $keyword));
		}

		//recover the paginate default configuration
		$settings = $this->paginate;
		// If performing search, there is a keyword
		if($keyword != null){
			// Change pagination conditions for searching
			$settings['conditions'] = array('OR' =>
				array(
					$this->modelClass.'.title LIKE' => '%'.$keyword.'%',
					'Type.name LIKE' => '%'.$keyword.'%'
				)
			);
		}

		//prepare the pagination
		$this->Paginator->settings = $settings;

		//recover the data
		$entities = $this->Paginator->paginate($this->model_name);

		//putting data in session
        $this->set('entities', $entities);
		$this->set('keyword', $keyword);
	}



	public function users($subject_id = null) {
		if ($subject_id == null) 
			return $this->redirect(array('action' => 'index'));

		$this->{$this->modelClass}->id = $subject_id;

		//recover the data
		$entities = $this->Paginator->paginate('ColmenaUser');		

		//putting data in session
        $this->set('subject', $this->{$this->modelClass}->read());
        $this->set('entities', $entities);
	}


	public function add() {
		// Exception control
        $flag = false;
		// If request is type post, add new entity
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
					'<p>Propiedad añadida correctamente.</p>',
					'flash_ok'
				);
				return $this->redirect(array('action' => 'index'));
			}
		}

		//put data in session
		$this->set('services', $this->{$this->modelClass}->Service->find("list"));
		$this->set('types', $this->{$this->modelClass}->Type->find("list"));
	}


	public function edit($id = null, $locale = null) {

		$this->loadModel('ColmenaSession');
		// Exception control
        $flag = false;
		//Recover the entity
		$entity = $this->{$this->modelClass}->get_entity($id, $locale);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				// Recover data
                $new_entity = $this->request->data;

                $aux = $entity;

                $session = $this->ColmenaSession->find('first', array(
                	'conditions' => array(
                		'ColmenaSession.id' => $new_entity[$this->modelClass]['session_id']
                		)
                	)
                );

                $aux[$this->modelClass]['session_id'] = $new_entity[$this->modelClass]['session_id'];

				$this->loadModel('ColmenaMarker');
				foreach ($entity['ColmenaMarkers'] as $key => $value) {
					$this->ColmenaMarker->insert($value, 
						$session['ColmenaSubject']['table_name'], 
						$new_entity[$this->modelClass]['session_id']);
				}


				//Edit the entity
				$this->{$this->modelClass}->insert($aux, $session['ColmenaSubject']['table_name']);

				$this->{$this->modelClass}->remove($id);

				// Re-assign data
                $entity = $this->{$this->modelClass}->get_entity($id, $locale);



			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);

				$flag = true;
			}

			if (!$flag) {
                $this->Session->setFlash(
                    '<p>Compilación asignada correctamente.</p>',
                    'flash_ok'
                );
            }
            return $this->redirect(array('action' => 'index'));
        }
		//re-asign the data
		$this->request->data = $entity;

		$sessions = $this->ColmenaSession->find("all", array(
			'fields' => array('id', 'session_name_es'),
			'conditions' => array(
				'ColmenaSession.visible' => 1,
				'ColmenaSession.subject_id' => 10
				)
			)
		);
		$sessions_modified = array();
		foreach ($sessions as $key => $value) {
			$sessions_modified[$value['ColmenaSession']['id']] = $value['ColmenaSession']['session_name_es'];
		}


		//put data in session
		$this->set('entity', $entity);
		$this->set('sessions', $sessions_modified);
	}

	public function delete($id = null) {
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
			return $this->redirect(array('action' => 'index'));
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
                '<p>Propiedad eliminada correctamente.</p>',
                'flash_ok'
            );
        }

        return $this->redirect(array('action' => 'index'));
	}

	public function change_visible($id = null){
		//recover the entity
		$this->{$this->modelClass}->id = $id;
		$entity = $this->{$this->modelClass}->read();

		//If trying to edit an entity that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		 $status = false;

        //control visibility
        if($entity[$this->model_name]['visible']){
            $status = $this->{$this->modelClass}->saveField("visible", "0");
        }else{
            $status = $this->{$this->modelClass}->saveField("visible", "1");
        }

        //if changed
        if(!$status){
            $this->set("status", "ERROR");
        }else{
            $this->set("status", "OK");
        }

        //render special view for ajax
        $this->render('change_visible', 'ajax');
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
