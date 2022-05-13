<?php
class ArticleTagsController extends AppController{
	var $name = "ArticleTags";
	//path to the resources
	private $content_path = "tags/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'ArticleTag.name' => 'asc'
		)
	);

	public function index($keyword = null) {
		//if search
		if ($this->request->is('post')) {
			//recover the keyword
			$keyword = $this->request->data["ArticleTag"]["keyword"];
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
					'ArticleTag.name LIKE' => '%'.$keyword.'%'
				)
			);
		}

		//prepare the pagination
		$this->Paginator->settings = $settings;

		//recover the data
		$entities = $this->Paginator->paginate('ArticleTag');

		//putting data in session
		$this->set('tags', $entities);
		$this->set("keyword", $keyword);
	}


	public function add() {
		// Exception control
        $flag = false;
		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$entity = $this->request->data;
			try{
				$add_result = $this->ArticleTag->add($entity);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);

				$this->request->data = $entity;
				$flag = true;
			}

			if (!$flag) {
				$this->Session->setFlash(
					'<p>Etiqueta añadida correctamente.</p>',
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
		$entity = $this->ArticleTag->get_entity($id, $locale);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the cat
				$new_entity = $this->request->data;
				$this->ArticleTag->edit($entity, $new_entity, $locale);

				// Re-assign data
                $entity = $this->ArticleTag->get_entity($id, $locale);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);

				$flag = true;
			}

			if (!$flag) {
				$this->Session->setFlash(
					'<p>Etiqueta editada correctamente.</p>',
					'flash_ok'
				);
			}
		}

		//re-asign the data
		$this->request->data = $entity;

		$this->set('entity', $entity);
		$this->set('entity_path', $this->content_path);
	}

	public function change_visible($id = null){
		//recover the cat
		$this->ArticleTag->id = $id;
		$entity = $this->ArticleTag->read();

		//If trying to edit an cat that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control visibility
		if($entity['ArticleTag']['visible']){
			$status = $this->ArticleTag->saveField("visible", "0");
		}else{
			$status = $this->ArticleTag->saveField("visible", "1");
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
		//recover the cat
		$this->ArticleTag->id = $id;
		$entity= $this->ArticleTag->read();

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control featured
		if($entity['ArticleTag']['featured']){
			$status = $this->ArticleTag->saveField("featured", "0");
		}else{
			$status = $this->ArticleTag->saveField("featured", "1");
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
		// Exception control
        $flag = false;
		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		//recover the entity
		$this->ArticleTag->id = $id;
		$entity = $this->ArticleTag->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->ArticleTag->remove($id);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>',
				'flash_error'
			);

			$flag = true;
		}

		if (!$flag) {
			$this->Session->setFlash(
				'<p>Etitqueta eliminada correctamente.</p>',
				'flash_ok'
			);
		}

		return $this->redirect(array('action' => 'index'));
	}


}
?>
