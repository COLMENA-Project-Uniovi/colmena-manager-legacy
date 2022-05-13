<?php
class SectionPartImagesController extends AppController{
	public $name = "SectionPartImages";
	//path to the resources
	private $content_path = "sections/section_part_images";

	public function add($id){
		// Exception control
        $flag = false;

		$this->SectionPartImage->Section->id = $id;
		$section = $this->SectionPartImage->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$part = $this->request->data;
			$part['SectionPartImage']['section_id'] = $id;
			try{
				$add_result = $this->SectionPartImage->add($part);
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
						$part['SectionPartImage']['locale']
					)
				);
			}
		}

		$this->set("section", $section);
	}

	public function edit($id, $id_part){
		// Exception control
        $flag = false;

		$this->SectionPartImage->Section->id = $id;
		$section = $this->SectionPartImage->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionPartImage->id = $id_part;
		$part = $this->SectionPartImage->read();

		//If trying to edit a part that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the article
				$this->SectionPartImage->edit($part, $this->request->data);

				// Re-assign data
                $part = $this->SectionPartImage->read();
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
		$part = $this->SectionPartImage->add_media_data($part);
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
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		//recover the entity
		$this->SectionPartImage->Section->id = $id;
		$section = $this->SectionPartImage->Section->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('action' => 'index'));
		}

		$this->SectionPartImage->id = $id_part;
		$part = $this->SectionPartImage->read();

		//If trying to remove a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		try{
			$this->SectionPartImage->remove($id_part);
		}catch(Exception $e){
			$this->SessionPartImages->setFlash(
				'<p>'. $e->getMessage() .'</p>',
				'flash_error'
			);

			$flag = true;
		}

		if (!$flag) {
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
				$part['SectionPartImage']['locale']
			)
		);
	}
}
?>
