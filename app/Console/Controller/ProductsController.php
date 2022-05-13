<?php
class ProductsController extends AppController{
	public $name = "Products";
	//path to the resources
	private $content_path = "products/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'Product.published' => 'desc'
		)
	);

	public function index($keyword = null) {
		//if search
		if ($this->request->is('post')) {
			//recover the keyword
			$keyword = $this->request->data["Product"]["keyword"];
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
					'Product.title LIKE' => '%'.$keyword.'%',
					'Product.subtitle LIKE' => '%'.$keyword.'%'
				)
			);
		}

		//prepare the pagination
		$this->Paginator->settings = $settings;

		//recover the data 
		$products = $this->Paginator->paginate('Product');

		//putting data in session		
		$this->set('products', $products);
		$this->set("keyword", $keyword);
	}


	public function add() {
		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$product = $this->request->data;
			try{
				$add_result = $this->Product->add($product);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				return $this->redirect(array('action' => 'edit', $this->Product->id));
			}
			
			$this->Session->setFlash(
				'<p>Producto añadido correctamente.</p>',
				'flash_ok'
			);
			return $this->redirect(array('action' => 'index'));
		}
		$this->set('cats', $this->Product->Cat->getTree());		

	}


	public function edit($id = null, $locale = null) {
		//Recover the product
		$product = $this->Product->get_entity($id, $locale);

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$product) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the product				
				$new_product = $this->request->data;				
				$this->Product->edit($product, $new_product, $locale);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				//if errors
				//re-asign the data
				$product = $this->Product->add_media_data($product);
				$this->request->data = $product;
			}
			$this->Session->setFlash(
				'<p>Producto editado correctamente.</p>',
				'flash_ok'
			);
			//redirection
			return $this->redirect(array('action' => 'edit', $this->Product->id));
		}
		
		//re-asign the data
		$product = $this->Product->add_media_data($product);
		$this->request->data = $product;

		
		$this->set('entity', $product);		
		$this->set('cats', $this->Product->Cat->getTree());		
		$this->set('entity_path', $this->content_path);		
	}

	public function change_visible($id = null){
		//recover the product
		$this->Product->id = $id;
		$product = $this->Product->read();

		//If trying to edit an product that doesn't exists, redirect
		if (!$id || !$product) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control visibility
		if($product['Product']['visible']){
			$status = $this->Product->saveField("visible", "0");
		}else{
			$status = $this->Product->saveField("visible", "1");
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
		//recover the product
		$this->Product->id = $id;
		$product = $this->Product->read();

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$product) {
			return $this->redirect(array('action' => 'index'));
		}

		$status = false;

		//control featured
		if($product['Product']['featured']){
			$status = $this->Product->saveField("featured", "0");
		}else{
			$status = $this->Product->saveField("featured", "1");
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
		$this->Product->id = $id;
		$product = $this->Product->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$product) {
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->Product->remove($id);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>', 
				'flash_error'
			);
		}
		//print trace
		$this->Session->setFlash(
			'<p>Producto eliminado correctamente.</p>', 
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