<?php
class SectionTabsController extends AppController{
	public $name = "SectionTabs";
	//path to the resources
	private $content_path = "sections/tabs/";

	public function add($id){
		// Exception control
        $flag = false;

		$this->SectionTab->Section->id = $id;
		$section = $this->SectionTab->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$part = $this->request->data;
			$part['SectionTab']['section_id'] = $id;
			try{
				$add_result = $this->SectionTab->add($part);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>',
					'flash_error'
				);
				$this->request->data = $entity;
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
						$part['SectionTab']['locale']
					)
				);
			}
		}

		$this->set("section", $section);
	}

	public function edit($id, $id_part){
		// Exception control
        $flag = false;

		$this->SectionTab->Section->id = $id;
		$section = $this->SectionTab->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionTab->id = $id_part;
		$part = $this->SectionTab->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the article
				$this->SectionTab->edit($part, $this->request->data);

				// Re-assign data
                $part = $this->SectionTab->read();
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
		$part = $this->SectionTab->add_media_data($part);
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
		$this->SectionTab->Section->id = $id;
		$section = $this->SectionTab->Section->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionTab->id = $id_part;
		$part = $this->SectionTab->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		try{
			$this->SectionTab->remove($id_part);
		}catch(Exception $e){
			$this->Session->setFlash(
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
				$part['SectionTab']['locale']
			)
		);
	}
}
?>
