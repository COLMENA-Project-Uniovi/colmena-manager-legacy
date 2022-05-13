<?php
class ArticlesController extends AppController{
	public $name = "Articles";
	//path to the resources
	private $content_path = "articles/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'Article.published' => 'desc'
		)
	);

	public function index($draft = null, $keyword = null) {
		//if search
		if ($this->request->is('post')) {
			//recover the keyword
			$keyword = $this->request->data["Article"]["keyword"];
			//re-send to the same controller with the keyword
			return $this->redirect(array('action' => 'index', $keyword));
		}

		//recover the paginate default configuration
		$settings = $this->paginate;
		if($draft != null)
			if($draft == '0' || $draft == '1')
				$settings['conditions']  = array('draft' => $draft);
			else
				$keyword = $draft;
		
		// If performing search, there is a keyword
		if($keyword != null){
			// Change pagination conditions for searching
			$settings['conditions'] = array('OR' => 
				array(
					'Article.title LIKE' => '%'.$keyword.'%',
				)
			);
		}

		//prepare the pagination
		$this->Paginator->settings = $settings;

		//recover the data 
		$articles = $this->Paginator->paginate('Article');

		//putting data in session
		$this->set('nall', $this->Article->find('count'));
		$this->set('npublished', $this->Article->find('count', array('conditions' => array( 'draft' => '0' ) )) );
		$this->set('ndraft', $this->Article->find('count', array('conditions' => array( 'draft' => '1' ) )) );		
		$this->set('articles', $articles);
		$this->set("keyword", $keyword);
	}


	public function add() {
		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$article = $this->request->data;		
			try{
				$article['Article']['published'] = CakeTime::format($article['Article']['published'], "%Y-%m-%d");
				if($article['Article']['title'] == '')
					$article['Article']['title'] = '(Sin título)';
				$add_result = $this->Article->add($article);
			}catch(Exception $e){								
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);
			}
			// If we are autosaving
			if(isset($article['autosave'])){
				$data = array('last_id' => $this->Article->getLastInsertId());
				$this->set("data", $data);
				return $this->render("autosave", "ajax");
			}
			
			$this->Session->setFlash(
				'<p>Noticia añadida correctamente.</p>',
				'flash_ok'
			);
			return $this->redirect(array('action' => 'index'));
		}

	}


	public function edit($id = null, $locale = null, $id_version = null) {
		//Recover the article
		$article = $this->Article->get_entity($id, $locale);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$article) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{		
				// Recover data	
				$new_article = $this->request->data;							
				$new_article['Article']['published'] = CakeTime::format($new_article['Article']['published'], "%Y-%m-%d");
				// If we are autosaving
				if(isset($new_article['autosave'])){
					$version['ArticleVersion'] = $new_article['Article'];
					$version['ArticleVersion']['article_id'] = $id;
					$data = array();
					// Add new version
					if($id_version == null){
						$this->Article->ArticleVersion->add($version, $locale);
						$data = array('last_id' => $this->Article->ArticleVersion->getLastInsertId());
					} else {		
						// Edit a version				
						$this->Article->ArticleVersion->edit($id_version, $version, $locale);
						$data = array('last_id' => $id_version);
					}
					$this->set("data", $data);
					return $this->render("autosave", "ajax");
				} else{
					//Edit the article														
					$this->Article->edit($article, $new_article, $locale);
				}
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				//if errors
				//re-asign the data
				$article = $this->Article->add_media_data($article);
				$this->request->data = $article;
			}
			$this->Session->setFlash(
				'<p>Noticia editada correctamente.</p>',
				'flash_ok'
			);
			//redirection
			return $this->redirect(array('action' => 'edit', $this->Article->id, $locale));
		}
		
		//re-asign the data
		$article = $this->Article->add_media_data($article);
		$this->request->data = $article;

		
		$this->set('entity', $article);
		$this->set('entity_path', $this->content_path);		
	}


	public function autosave($id = null, $locale = null) {
		//Recover the article
		$article = $this->Article->get_entity($id, $locale);
		$this->layout='ajax';	

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$article) {
			return false;
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{				
				//Edit the article				
				$new_article = $this->request->data;					
				$new_article['Article']['published'] = CakeTime::format($new_article['Article']['published'], "%Y-%m-%d");
				$this->Article->edit_autosave($article, $new_article, $locale);
			}catch(Exception $e){				
				return false;
			}			
			return true;
		}		
	}

	public function change_visible($id = null){
		//recover the article
		$this->Article->id = $id;
		$article = $this->Article->read();

		//If trying to edit an article that doesn't exists, redirect
		if (!$id || !$article) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control visibility
		if($article['Article']['visible']){
			$status = $this->Article->saveField("visible", "0");
		}else{
			$status = $this->Article->saveField("visible", "1");
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
		$this->Article->id = $id;
		$article = $this->Article->read();

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$article) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control featured
		if($article['Article']['featured']){
			$status = $this->Article->saveField("featured", "0");
		}else{
			$status = $this->Article->saveField("featured", "1");
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
		$this->Article->id = $id;
		$article = $this->Article->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$article) {
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->Article->remove($id);
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



	public function version_recover($id = null, $id_version = null, $locale = null) {		
		//Recover the version
		$article = $this->Article->get_entity($id, $locale);
		$version = $this->Article->ArticleVersion->get_entity($id_version, $locale);
		$version['Article'] = $version['ArticleVersion'];
		unset($version['Article']['id']);
		
		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$article || !$version) {
			return $this->redirect(array('action' => 'edit', $id));
		}

		try{				
			//Edit the article														
			$this->Article->edit($article, $version, $locale);
			$this->Article->ArticleVersion->saveField("recover", "1");			
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>', 
				'flash_error'
			);

			//if errors
			//re-asign the data
			$article = $this->Article->add_media_data($article);
			$this->request->data = $article;
			return $this->redirect(array('action' => 'edit', $this->Article->id, $locale));		
		}
		$this->Session->setFlash(
			'<p>Noticia editada correctamente.</p>',
			'flash_ok'
		);
		
		//re-asign the data
		$article = $this->Article->add_media_data($article);
		$this->request->data = $article;

		
		$this->set('entity', $article);
		$this->set('entity_path', $this->content_path);		
		//redirection
		return $this->redirect(array('action' => 'edit', $this->Article->id, $locale));		
	}
}
?>