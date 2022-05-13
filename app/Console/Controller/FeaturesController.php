<?php
class FeaturesController extends AppController{
	public $name = "Features";
	
	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'Feature.sort' => 'desc'
		)
	);
	
	public function add($id) {
		
		if ($this->request->is('post')) {
			$this->Feature->Subproduct->id = $id;
			$parent = $this->Feature->Subproduct->read();

			//If trying to edit a section that doesn't exists, redirect
			if (!$id || !$parent) {
				return $this->redirect(array('controller' => 'products', 'action' => 'index'));
			}
			try{
				$entity = array();
				$entity['Feature']['subproduct_id'] = $id;
				$add_result = $this->Feature->add($entity);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				return $this->redirect(array('action' => 'edit', $this->Feature->id));
			}
			
			$this->Session->setFlash(
				'<p>Tipo de producto añadido correctamente.</p>',
				'flash_ok'
			);
			
			return $this->redirect(array('action' => 'edit', $add_result));
		}
	}


	public function edit($id = null) {		
		//Recover the product
		$this->Feature->id = $id;
		$entity = $this->Feature->get_entity($id);
/*
		$this->Feature->Subproduct->id = $id;
		$parent = $this->Feature->Subproduct->read();
*/
		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the product				
				$new_entity = $this->request->data;				
				$this->Feature->edit($entity, $new_entity);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				//if errors
				//re-asign the data
				$this->request->data = $entity;
			}
			$this->Session->setFlash(
				'<p>Tipo de producto editado correctamente.</p>',
				'flash_ok'
			);
			//re-asign the data		
			
			$entity = $this->Feature->get_entity($id);
			$this->request->data = $entity;
			$this->set('entity', $entity);			
			return $this->render('view', 'ajax');
		}
		
		//re-asign the data		
		$this->request->data = $entity;
		
		$this->layout = 'ajax';
		$this->set('entity', $entity);
		
	}


	public function delete($id = null) {		
		//only post
		if ($this->request->is('get')) {
			exit();
		}

		//recover the entity
		$this->Feature->id = $id;
		$entity = $this->Feature->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$entity) {
			exit;
		}

		try{
			$this->Feature->remove($id);
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
		$this->autoRender = false;
	}

}
?>