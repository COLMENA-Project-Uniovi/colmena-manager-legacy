<?php
class GalleriesController extends AppController{
	public $name = "Galleries";
	//path to the resources
	private $content_path = "galleries/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'Gallery.created' => 'desc'
		)
	);

	public function index($keyword = null) {
		//if search
		if ($this->request->is('post')) {
			//recover the keyword
			$keyword = $this->request->data['Gallery']['keyword'];
			//re-send to the same controller with the keyword
			return $this->redirect(array('action' => 'index', $keyword));
		}

		// If performing search, there is a keyword
		if($keyword != null){
			//recover the data
			$entities = $this->Gallery->find(
			"all",
				array(
					"conditions" => array(
						'Gallery.name LIKE' => '%'.$keyword.'%'
					)
				)
			);
		}else{
			$entities = $this->Gallery->find("all");
		}

		$entities = $this->Gallery->add_media_data_bulk($entities);

		//putting data in session
		$this->set('entities', $entities);
		$this->set('keyword', $keyword);
	}

	public function add() {
		// Exception control
        $flag = false;

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			try{
				$add_result = $this->Gallery->add($this->request->data);

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
			'entities',
			$this->Gallery->find('all')
		);
	}

	public function edit($id = null, $locale = null) {
		// Exception control
        $flag = false;

		//Recover the article
		$entity = $this->Gallery->get_entity($id, $locale);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the article
				$this->Gallery->edit($entity, $this->request->data, $locale);

				// Re-assign data
                $entity = $this->Gallery->get_entity($id, $locale);
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
		$entity = $this->Gallery->add_media_data($entity);
		$this->request->data = $entity;

		//put data in session
		$this->set(
			'entities',
			$this->Gallery->find(
				'all',
				array(
					"conditions" => array(
						"Gallery.id != ".$entity['Gallery']['id']
					)
				)
			)
		);
		$this->set('entity', $entity);
		$this->set('parts', $this->Gallery->get_ordered_parts($locale));
	}

	public function view($id = null, $locale = null) {
		// Exception control
        $flag = false;

		//Recover the article
		$entity = $this->Gallery->get_entity($id, $locale);

		//If trying to view a Gallery that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		//put data in session
		$this->set(
			'entities',
			$this->Gallery->find(
				'all',
				array(
					"conditions" => array(
						"Gallery.id != ".$entity['Gallery']['id']
					)
				)
			)
		);
		$this->set('children', $this->Gallery->children());
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
		$this->Gallery->id = $id;
		$Gallery = $this->Gallery->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$Gallery) {
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->Gallery->remove($id);
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
			$this->Gallery->delete_resource($id, $this->Gallery->content_path, $folder, $filename);

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
