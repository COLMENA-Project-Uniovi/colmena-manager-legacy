<?php
class SectionGalleriesController extends AppController{
	public $name = "SectionGalleries";
	//path to the resources
	private $content_path = "sections/section_galleries";

	public function add($id) {
		// Exception control
        $flag = false;

		$this->SectionGallery->Section->id = $id;
		$section = $this->SectionGallery->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$part = $this->request->data;
			$part['SectionGallery']['section_id'] = $id;
			try{
				$add_result = $this->SectionGallery->add($part);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);
				$this->request->data = $part;
				$flag = true;
			}

			if (!$flag) {
				$this->Session->setFlash(
					'<p>Parte añadida correctamente.</p>',
					'flash_ok'
				);
				return $this->redirect(
					array(
						'controller' => 'sections',
						'action' => 'edit',
						$id,
						$part['SectionGallery']['locale']
					)
				);
			}
		}

		$this->set("section", $section);
	}

	public function edit($id, $id_part) {
		// Exception control
        $flag = false;

		$this->SectionGallery->Section->id = $id;
		$section = $this->SectionGallery->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionGallery->id = $id_part;
		$part = $this->SectionGallery->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the article
				$this->SectionGallery->edit($part, $this->request->data);

				// Re-assign data
                $part = $this->SectionGallery->read();
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);

				$flag = true;
			}

			if (!$flag) {
				$this->Session->setFlash(
					'<p>Parte editada correctamente.</p>',
					'flash_ok'
				);
			}
		}

		//re-asign the data
		$part = $this->SectionGallery->add_media_data($part);
		$this->request->data = $part;

		//put data in session
		$this->set('section', $section);
		$this->set('part', $part);
	}

	public function delete($id, $id_part) {
		// Exception control
        $flag = false;
		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		$this->SectionGallery->Section->id = $id;
		$section = $this->SectionGallery->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionGallery->id = $id_part;
		$part = $this->SectionGallery->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		try{
			$this->SectionGallery->remove($id_part);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>',
				'flash_error'
			);

			$flag = true;
		}

		if (!$flag) {
			//print trace
			$this->Session->setFlash(
				'<p>Parte eliminada correctamente.</p>',
				'flash_ok'
			);
		}

		return $this->redirect(
			array(
				'controller' => 'sections',
				'action' => 'edit',
				$id,
				$part['SectionGallery']['locale']
			)
		);
	}

	public function delete_resource($id = null, $folder, $filename = null){
		//if is a post error
		if($this->request->is('post')){
			return $this->redirect(array('action' => 'index'));
		}

		try{
			$this->SectionGallery->delete_resource($id, $this->SectionGallery->content_path, $folder, $filename);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>',
				'default',
				array("class" => 'error-message')
			);
		}

		$this->Session->setFlash(
				'<p>Archivo eliminado correctamente.</p>'
		);

		//final redirection
		return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
	}
}
?>
