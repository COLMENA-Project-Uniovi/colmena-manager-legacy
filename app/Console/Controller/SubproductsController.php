<?php
class SubproductsController extends AppController{
	public $name = "Subproducts";
	//path to the resources
	private $content_path = "products/subproducts/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'Subproduct.published' => 'desc'
		)
	);
	
	public function add($id) {
		$this->Subproduct->Product->id = $id;
		$parent = $this->Subproduct->Product->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$parent) {
			return $this->redirect(array('controller' => 'products', 'action' => 'index'));
		}
		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$entity = $this->request->data;			
			$entity['Subproduct']['product_id'] = $id;			
			try{
				$add_result = $this->Subproduct->add($entity);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				return $this->redirect(array('action' => 'edit', $this->Subproduct->id));
			}
			
			$this->Session->setFlash(
				'<p>Tipo de producto añadido correctamente.</p>',
				'flash_ok'
			);
			return $this->redirect(array('controller' => 'products', 'action' => 'edit', $id));
		}

		$this->set("parent", $parent);
	}


	public function edit($id = null, $id_part = null, $locale = null) {		
		//Recover the product
		$entity = $this->Subproduct->get_entity($id_part, $locale);

		$this->Subproduct->Product->id = $id;
		$parent = $this->Subproduct->Product->read();

		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the product				
				$new_product = $this->request->data;				
				$this->Subproduct->edit($entity, $new_product, $locale);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				//if errors
				//re-asign the data
				$entity = $this->Subproduct->add_media_data($entity);
				$this->request->data = $entity;
			}
			$this->Session->setFlash(
				'<p>Tipo de producto editado correctamente.</p>',
				'flash_ok'
			);
			//redirection
			return $this->redirect(array('action' => 'edit', $this->Subproduct->id));
		}
		
		//re-asign the data
		$entity = $this->Subproduct->add_media_data($entity);
		$this->request->data = $entity;

		
		$this->set('entity', $entity);
		$this->set('parent', $parent);
		$this->set('entity_path', $this->content_path);		
	}

	public function delete($id = null) {
		// TO DO
		exit();
		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		//recover the entity
		$this->Subproduct->id = $id;
		$entity = $this->Subproduct->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->Subproduct->remove($id);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>', 
				'flash_error'
			);
		}
		//print trace
		$this->Session->setFlash(
			'<p>Tipo de producto eliminado correctamente.</p>', 
			'flash_ok'
		);

		return $this->redirect(array('action' => 'index'));
	}

	public function deleteresource($id = null, $folder, $filename = null){
		// TO DO
		exit();
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