<?php
class SectionLinksController extends AppController{
	public $name = "SectionLinks";
	//path to the resources
	//private $content_path = "sections/partlinks/links/";
	private $content_path = "sections/section_links/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'SectionLink.published' => 'desc'
		)
	);

	public function add($id) {
		$this->SectionLink->SectionPartLink->id = $id;
		$part = $this->SectionLink->SectionPartLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$link = $this->request->data;

			// ADDED BECAUSE UNKNOWN ERROR
			$link['SectionLink']['section_part_link_id'] = $id;
			
			if ($this->SectionLink->save($link)) {
				// Upload attachment
				$path = $this->content_path.$this->SectionLink->id;
				$attach = $this->request->data['SectionLink']['attach'];

				$pathinfo = pathinfo($attach['name']);
				$status = parent::upload_file($path, $attach, $pathinfo['filename']);

				//if error
				if($status !== true){
					//print trace
					$this->Session->setFlash(
						'<p>'.$status.'</p>',
						'default', 
						array("class" => 'error-message')
					);

					//redirect to the controller and display error msg
					return $this->redirect(array('action' => 'add', $part['SectionPartLink']['id']));
				}

				//message ok
				$this->Session->setFlash('<p>El enlace se ha añadido correctamente.</p>');

				//redirection
				return $this->redirect(array('controller' => 'section_part_links', 'action' => 'edit', $part['Section']['id'], $part['SectionPartLink']['id']));
			}
			//probelms adding entity
			$this->Session->setFlash(
				'<p>Ha habido un error añadiendo la parte de descargas.</p> <p>Por favor, comprueba los datos e inténtalo de nuevo más tarde.</p>', 
				'default', 
				array("class" => 'error-message')
			);
		}

		$this->set("part", $part);
	}

	public function edit($id, $id_link) {
		$this->SectionLink->SectionPartLink->id = $id;
		$part = $this->SectionLink->SectionPartLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}


		$this->SectionLink->id = $id_link;
		$link = $this->SectionLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_link || !$link) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			$link = $this->request->data;
			// ADDED BECAUSE UNKNOWN ERROR
			$link['SectionLink']['section_part_link_id'] = $id;
		
			if ($this->SectionLink->save($link)) {

				// Upload attachment
				$path = $this->content_path.$this->SectionLink->id;
				$attach = $this->request->data['SectionLink']['attach'];
				$status = true; 
				
				if($attach['size'] != 0){
					// Delete previous files
					$dir = new Folder(parent::getRootResources().$this->content_path.$this->SectionLink->id);
					$dir->delete();

					
					$pathinfo = pathinfo($attach['name']);
					$status = parent::upload_file($path, $attach, $pathinfo['filename']);
				}

				//if error
				if($status !== true){
					//print trace
					$this->Session->setFlash(
						'<p>'.$status.'</p>',
						'default', 
						array("class" => 'error-message')
					);

					//redirect to the controller and display error msg
					return $this->redirect(array('action' => 'add', $part['SectionPartLink']['id']));
				}

				$this->Session->setFlash('<p>El enlace ha sido editado correctamente.</p>');
				//redirection
				return $this->redirect(array('controller' => 'section_part_links', 'action' => 'edit', $part['Section']['id'], $part['SectionPartLink']['id']));
			}

			//if errors
			//re-asign the data
			$this->request->data = $link;
			//redirect
			$this->Session->setFlash(
				'<p>Ha habido un error editando la parte de descargas.</p> <p>Por favor, comprueba los datos e inténtalo de nuevo más tarde.</p>', 
				'default', 
				array("class" => 'error-message')
			);
		}

		//re-asign the data
		$this->request->data = $link;
		//put data in session
		$this->set('part', $part);
		$this->set('link', $link);
		$this->set('entity_path', $this->content_path);
	}

	public function delete($id, $id_link) {
		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionLink->SectionPartLink->id = $id;
		$part = $this->SectionLink->SectionPartLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionLink->id = $id_link;
		$link = $this->SectionLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_link || !$link) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		if ($this->SectionLink->delete($id_link)) {
			//search folder and delete
			$dir = new Folder(parent::getRootResources().$this->content_path.$id_link);
			$dir->delete();

			//print trace
			$this->Session->setFlash('<p>El enlace se ha eliminado correctamente.</p>');
			return $this->redirect(array('controller' => 'section_part_links', 'action' => 'edit', $part['SectionPartLink']['section_id'], $part['SectionPartLink']['id']));
		}
	}

	public function deleteresource($id, $id_link, $filename = null){
		//if is a post error
		if($this->request->is('post')){
			return $this->redirect(array('action' => 'index'));
		}

		$this->SectionLink->SectionPartLink->id = $id;
		$part = $this->SectionLink->SectionPartLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionLink->id = $id_link;
		$link = $this->SectionLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_link || !$link) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		//recover the file
		$file = new File(parent::getRootResources(). $this->content_path.$id_link."/".$filename);
		
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
		return $this->redirect(array('controller' => 'section_part_links', 'action' => 'edit', $part['SectionPartLink']['section_id'], $part['SectionPartLink']['id']));
	}
}
?>