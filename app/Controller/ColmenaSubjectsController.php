<?php
class ColmenaSubjectsController extends AppController{
	public $name = "ColmenaSubjects";

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


	public function add() {
		// Exception control
        $flag = false;
		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$entity = $this->request->data;
			try{

				$this->{$this->modelClass}->createTableForMarkers($entity[$this->modelClass]['table_name']);
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
					'<p>Alumno añadido correctamente al grupo.</p>',
					'flash_ok'
				);
				return $this->redirect(array('action' => 'index'));
			}
		}
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
                    '<p>Asignatura editada correctamente.</p>',
                    'flash_ok'
                );
            }
        }
		//re-asign the data
		$entity = $this->{$this->modelClass}->add_media_data($entity);
		$this->request->data = $entity;

		
		//put data in session
		$this->set('entity', $entity);		
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
                '<p>Asignatura eliminada correctamente.</p>',
                'flash_ok'
            );
        }

        return $this->redirect(array('action' => 'index'));
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
