<?php
class ColmenaMarkersController extends AppController{
	public $name = "ColmenaMarkers";

	//pagination
	public $paginate = array(
		'limit' => 200,
		'order' => array(
			'timestamp' => 'asc'
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
					'<p>Marker añadida correctamente.</p>',
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

				//Edit the entity
				$this->{$this->modelClass}->edit($entity, $new_entity, $locale);

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
                    '<p>Marker editada correctamente.</p>',
                    'flash_ok'
                );
            }
        }
		//re-asign the data
		$entity = $this->{$this->modelClass}->add_media_data($entity);
		$this->request->data = $entity;

		$services = $this->{$this->modelClass}->Service->find("list");
		$entity['Service'] = $this->{$this->modelClass}->get_property_services($id);


		//put data in session
		$this->set('entity', $entity);
		$this->set('services', $services);
		$this->set('types', $this->{$this->modelClass}->Type->find("list"));
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
                '<p>Marker eliminada correctamente.</p>',
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
