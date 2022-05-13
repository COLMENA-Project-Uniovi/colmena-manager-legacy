<?php
class CatsController extends AppController{
	var $name = "Cats";
	//path to the resources
	private $content_path = "cats/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'Cat.id' => 'asc'
			)
	);

	public function index($keyword = null) {

		//if search
		if ($this->request->is('post')) {
			//recover the keyword
			$keyword = $this->request->data["Cat"]["keyword"];
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
					'Cat.title LIKE' => '%'.$keyword.'%'					
				)
			);
		}

		//prepare the pagination
		$this->Paginator->settings = $settings;

		//recover the data 
		$cats = $this->Paginator->paginate('Cat');

		//putting data in session
		$cats = $this->Cat->children(0); 
		$this->set('cats', $cats);
		$this->set("keyword", $keyword);
	}


	public function add() {
		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$cat= $this->request->data;
			try{
				$cat['Cat']['published'] = CakeTime::format($cat['Cat']['published'], "%Y-%m-%d");
				$add_result = $this->Cat->add($cat);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				return $this->redirect(array('action' => 'edit', $this->Cat->id));
			}
			
			$this->Session->setFlash(
				'<p>Categoría añadida correctamente.</p>',
				'flash_ok'
			);
			return $this->redirect(array('action' => 'index'));
		}


		$cats = $this->Cat->getTree();
		$this->set('cats', $cats);

	}


	public function edit($id = null, $locale = null) {
		//Recover the cat
		$cat= $this->Cat->get_entity($id, $locale);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$cat) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the cat				
				$new_cat = $this->request->data;				
				$this->Cat->edit($cat, $new_cat, $locale);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);
				
				$this->request->data = $cat;
			}
			$this->Session->setFlash(
				'<p>Categoría editada correctamente.</p>',
				'flash_ok'
			);
			//redirection
			return $this->redirect(array('action' => 'edit', $this->Cat->id, $locale));
		}
		
		//re-asign the data
		$cat = $this->Cat->add_media_data($cat);		
		$this->request->data = $cat;
		
		$this->set('entity', $cat);
		$this->set('entity_path', $this->content_path);		
	}

	public function change_visible($id = null){
		//recover the cat
		$this->Cat->id = $id;
		$cat= $this->Cat->read();

		//If trying to edit an cat that doesn't exists, redirect
		if (!$id || !$cat) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control visibility
		if($cat['Cat']['visible']){
			$status = $this->Cat->saveField("visible", "0");
		}else{
			$status = $this->Cat->saveField("visible", "1");
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
		$this->Cat->id = $id;
		$cat= $this->Cat->read();

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$cat) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control featured
		if($cat['Cat']['featured']){
			$status = $this->Cat->saveField("featured", "0");
		}else{
			$status = $this->Cat->saveField("featured", "1");
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
		$this->Cat->id = $id;
		$cat= $this->Cat->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$cat) {
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->Cat->remove($id);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>', 
				'flash_error'
			);
		}
		//print trace
		$this->Session->setFlash(
			'<p>Categoría eliminada correctamente.</p>', 
			'flash_ok'
		);

		return $this->redirect(array('action' => 'index'));
	}

	
}
?>