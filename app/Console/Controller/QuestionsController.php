<?php
class NewsController extends AppController{
	public $name = "News";
	//path to the resources
	private $content_path = "news/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'News.published' => 'desc'
		)
	);

	public function index($keyword = null) {
		//if search
		if ($this->request->is('post')) {
			//recover the keyword
			$keyword = $this->request->data["News"]["keyword"];
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
					'News.title LIKE' => '%'.$keyword.'%',
				)
			);
		}

		//prepare the pagination
		$this->Paginator->settings = $settings;

		//recover the data 
		$entities = $this->Paginator->paginate('News');

		//putting data in session				
		$this->set('entities', $entities);
		$this->set("keyword", $keyword);
	}


	public function add() {
		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$entity = $this->request->data;		
			try{
				$entity['News']['published'] = CakeTime::format($entity['News']['published'], "%Y-%m-%d");
				if($entity['News']['title'] == '')
					$entity['News']['title'] = '(Sin título)';
				$add_result = $this->News->add($entity);
			}catch(Exception $e){								
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);
			}
		
			$this->Session->setFlash(
				'<p>Noticia añadida correctamente.</p>',
				'flash_ok'
			);
			return $this->redirect(array('action' => 'index'));
		}

	}


	public function edit($id = null, $locale = null) {
		//Recover the article
		$entity = $this->News->get_entity($id, $locale);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{		
				// Recover data	
				$new_entity = $this->request->data;							
				$new_entity['News']['published'] = CakeTime::format($new_entity['News']['published'], "%Y-%m-%d");
								
				//Edit the article														
				$this->News->edit($entity, $new_entity, $locale);

			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				//if errors
				//re-asign the data
				$entity = $this->News->add_media_data($entity);
				$this->request->data = $entity;
			}
			$this->Session->setFlash(
				'<p>Noticia editada correctamente.</p>',
				'flash_ok'
			);
			//redirection
			return $this->redirect(array('action' => 'edit', $this->News->id, $locale));
		}
		
		//re-asign the data
		$entity = $this->News->add_media_data($entity);
		$this->request->data = $entity;

		
		$this->set('entity', $entity);
		$this->set('entity_path', $this->content_path);		
	}

	public function change_visible($id = null){
		//recover the article
		$this->News->id = $id;
		$entity = $this->News->read();

		//If trying to edit an article that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control visibility
		if($entity['News']['visible']){
			$status = $this->News->saveField("visible", "0");
		}else{
			$status = $this->News->saveField("visible", "1");
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


	public function change_featured($id = null){
		//recover the article
		$this->News->id = $id;
		$entity = $this->News->read();

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control featured
		if($entity['News']['featured']){
			$status = $this->News->saveField("featured", "0");
		}else{
			$status = $this->News->saveField("featured", "1");
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

	public function delete($id = null) {
		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		//recover the entity
		$this->News->id = $id;
		$entity = $this->News->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->News->remove($id);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>', 
				'flash_error'
			);
		}
		//print trace
		$this->Session->setFlash(
			'<p>Noticia eliminada correctamente.</p>', 
			'flash_ok'
		);

		return $this->redirect(array('action' => 'index'));
	}

	public function deleteresource($id = null, $folder, $filename = null){
		//if is a post error
		if($this->request->is('post')){
			return $this->redirect(array('action' => 'index'));
		}

		//recover the file
		$file = new File(parent::getRootResources(). $this->content_path.$id."/".$folder."/".$filename);
		
		//remove the file
		if($file->delete()){
			$this->Session->setFlash(
				'<p>El archivo se ha eliminado correctamente.</p>'
			);
		}else{
			//errors persisting
			$this->Session->setFlash(
				'<p> Ha habido un error eliminando el recurso </p>',
				'default', 
				array("class" => 'error-message')
			);
		}
		//final redirection
		return $this->redirect(array('action' => 'edit', $id));
	}


}
?>