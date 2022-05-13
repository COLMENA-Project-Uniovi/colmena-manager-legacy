<?php
class SectionsController extends AppController{
	public $name = "Sections";
	//path to the resources
	private $content_path = "sections/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'Section.created' => 'desc'
		)
	);

	public function index($keyword = null) {
		//if search
		if ($this->request->is('post')) {
			//recover the keyword
			$keyword = $this->request->data['Section']['keyword'];
			//re-send to the same controller with the keyword
			return $this->redirect(array('action' => 'index', $keyword));
		}

		// If performing search, there is a keyword
		if($keyword != null){
			//recover the data
			$sections = $this->Section->find(
			"treepathcomplete",
				array(
					"conditions" => array(
						'Section.name LIKE' => '%'.$keyword.'%'
					)
				)
			);
		}else{
			$sections = $this->Section->find("treepathcomplete");
		}

		$sections = $this->Section->add_media_data_bulk($sections);

		//putting data in session
		$this->set('sections', $sections);
		$this->set('keyword', $keyword);
	}

	public function add() {
		// Exception control
        $flag = false;

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			try{
				$add_result = $this->Section->add($this->request->data);

			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);

				$flag = true;
			}

			if (!$flag) {
				$this->Session->setFlash(
					'<p>Sección añadida correctamente.</p>',
					'flash_ok'
				);
			}
			return $this->redirect(array('action' => 'index'));
		}

		$this->set(
			'sections',
			$this->Section->find('treepath')
		);
	}

	public function edit($id = null, $locale = null) {
		// Exception control
        $flag = false;

		//Recover the article
		$entity = $this->Section->get_entity($id, $locale);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the article
				$this->Section->edit($entity, $this->request->data, $locale);

				// Re-assign data
                $entity = $this->Section->get_entity($id, $locale);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);

				$flag = true;
			}

			if (!$flag) {
				$this->Session->setFlash(
					'<p>Sección editada correctamente.</p>',
					'flash_ok'
				);
			}
		}

		//re-asign the data
		$entity = $this->Section->add_media_data($entity);
		$this->request->data = $entity;

		//put data in session
		$this->set(
			'sections',
			$this->Section->find(
				'treepath',
				array(
					"conditions" => array(
						"Section.id != ".$entity['Section']['id']
					)
				)
			)
		);
		$this->set('entity', $entity);
		$this->set('parts', $this->Section->get_ordered_parts($locale));
	}

	public function view($id = null, $locale = null) {
		// Exception control
        $flag = false;

		//Recover the article
		$entity = $this->Section->get_entity($id, $locale);

		//If trying to view a section that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		//put data in session
		$this->set(
			'sections',
			$this->Section->find(
				'treepath',
				array(
					"conditions" => array(
						"Section.id != ".$entity['Section']['id']
					)
				)
			)
		);
		$this->set('children', $this->Section->children());
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
		$this->Section->id = $id;
		$section = $this->Section->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->Section->remove($id);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>',
				'flash_error'
			);

			$flag = true;
		}

		if (!$flag) {
			$this->Session->setFlash(
				'<p>Sección eliminada correctamente.</p>',
				'flash_ok'
			);
		}

		return $this->redirect(array('action' => 'index'));
	}


	public function delete_resource($id = null, $folder, $filename = null){
		//if is a post error
		if($this->request->is('post')){
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->Section->delete_resource($id, $this->Section->content_path, $folder, $filename);

		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>',
				'default',
				array("class" => 'error-message')
			);
		}

		$this->Session->setFlash(
			'<p>Archivo eliminado correctamente.</p>'
		);

		//final redirection
		return $this->redirect(array('action' => 'edit', $id));
	}
}
?>
