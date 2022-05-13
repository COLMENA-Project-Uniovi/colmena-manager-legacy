<?php
class SectionPartLinksController extends AppController{
	public $name = "SectionPartLinks";
	//path to the resources
	private $content_path = "sections/section_part_links/";

	//pagination
	public $paginate = array(
		'limit' => 20,
		'order' => array(
			'SectionPartLink.published' => 'desc'
		)
	);

	public function add($id) {
		$this->SectionPartLink->Section->id = $id;
		
		$section = $this->SectionPartLink->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$part = $this->request->data;

			$part['SectionPartLink']['section_id'] = $id;
			
			if ($this->SectionPartLink->save($part)) {
				//message ok
				$this->Session->setFlash('<p>La parte de enlaces se ha añadido correctamente.</p>');

				//redirection
				return $this->redirect(array('action' => 'edit', $this->SectionPartLink->Section->id, $this->SectionPartLink->id));
			}
			//probelms adding entity
			$this->Session->setFlash(
				'<p>Ha habido un error añadiendo la parte de enlaces.</p> <p>Por favor, comprueba los datos e inténtalo de nuevo más tarde.</p>', 
				'default', 
				array("class" => 'error-message')
			);
		}

		$this->set("section", $section);
	}

	public function edit($id, $id_part) {
		$this->SectionPartLink->Section->id = $id;
		$section = $this->SectionPartLink->Section->read();


		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionPartLink->id = $id_part;
		$part = $this->SectionPartLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			$part = $this->request->data;
			$part['SectionPartLink']['section_id'] = $id;
		
			if ($this->SectionPartLink->save($part)) {
				$this->Session->setFlash('<p>La parte de enlaces ha sido editada correctamente.</p>');
				return $this->redirect(array('action' => 'edit', $this->SectionPartLink->Section->id, $this->SectionPartLink->id));
			}

			//if errors
			//re-asign the data
			$this->request->data = $part;
			//redirect
			$this->Session->setFlash(
				'<p>Ha habido un error editando la parte de enlaces.</p> <p>Por favor, comprueba los datos e inténtalo de nuevo más tarde.</p>', 
				'default', 
				array("class" => 'error-message')
			);
		}

		//re-asign the data
		$this->request->data = $part;
		//put data in session
		$this->set('section', $section);
		$this->set('part', $part);
		$this->set('extra_images_dir_path', parent::get_extra_images_path("/resources/", $this->content_path) . $id_part);
		$this->set('entity_path', $this->content_path);
	}

	public function delete($id, $id_part) {
		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		$this->SectionPartLink->Section->id = $id;
		$section = $this->SectionPartLink->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionPartLink->id = $id_part;
		$part = $this->SectionPartLink->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		if ($this->SectionPartLink->delete($id_part)) {
			//search folder and delete
			$dir = new Folder(parent::getRootResources().$this->content_path.$id);
			$dir->delete();

			//print trace
			$this->Session->setFlash('<p>La parte de enlaces se ha eliminado correctamente.</p>');
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}
	}
}
?>