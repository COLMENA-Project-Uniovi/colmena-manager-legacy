<?php
class SectionColumnsController extends AppController{
	public $name = "SectionColumns";
	//path to the resources
	private $content_path = "sections/section_columns/";

	public function add($id){
		$this->SectionColumn->Section->id = $id;
		
		$section = $this->SectionColumn->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		// If request is type post, add new element
		if ($this->request->is('post')) {
			$part = $this->request->data;

			$part['SectionColumn']['section_id'] = $id;

			if ($this->SectionColumn->save($part)) {
				$this->Session->setFlash('<p>La parte se ha añadido correctamente.</p>');
				return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $this->SectionColumn->Section->id));
			}
			$this->Session->setFlash(
				'<p>Ha habido un error añadiendo la parte.</p> <p>Por favor, comprueba los datos e inténtalo de nuevo más tarde.</p>', 
				'default', 
				array("class" => 'error-message')
			);
		}

		$this->set("section", $section);
	}

	public function edit($id, $id_part){
		$this->SectionColumn->Section->id = $id;
		$section = $this->SectionColumn->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionColumn->id = $id_part;
		$part = $this->SectionColumn->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			$part = $this->request->data;
			$part['SectionColumn']['section_id'] = $id;

			if ($this->SectionColumn->save($part)) {
				$this->Session->setFlash('<p>La parte se ha editado correctamente.</p>');
				return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $this->SectionColumn->Section->id));
			}

			//if errors
			//re-asign the data
			$this->request->data = $part;
			//redirect
			$this->Session->setFlash(
				'<p>Ha habido un error editando la parte.</p> <p>Por favor, comprueba los datos e inténtalo de nuevo más tarde.</p>', 
				'default', 
				array("class" => 'error-message')
			);
		}

		$this->request->data = $part;
		$this->set('section', $section);
		$this->set('part', $part);
		$this->set('img_url', parent::get_img_url($this->content_path, 'principal', $part['SectionColumn']['id']));
		$this->set('entity_path', $this->content_path);
	}

	public function delete($id, $id_part) {
		$this->SectionColumn->Section->id = $id;
		
		$this->SectionColumn->Section->locale = Configure::read("Config.language");
		$section = $this->SectionColumn->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionColumn->id = $id_part;
		$part = $this->SectionColumn->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		if ($this->SectionColumn->delete($id_part)) {
			//search folder and delete
			$dir = new Folder($this->content_path.$id_part);
			$dir->delete();

			$this->Session->setFlash('<p>La parte se ha eliminado correctamente.</p>');
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}
	}
}
?>