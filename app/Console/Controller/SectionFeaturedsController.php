<?php
class SectionFeaturedsController extends AppController{
	public $name = "SectionFeatureds";
	//path to the resources
	private $content_path = "sections/section_feautreds";

	public function add($id){
		$this->SectionFeatured->Section->id = $id;
		$section = $this->SectionFeatured->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			try{
				$part = $this->request->data;
				$part['SectionFeatured']['section_id'] = $id;
				$add_result = $this->SectionFeatured->add($part);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				return $this->redirect(array('action' => 'edit', $id, $this->SectionFeatured->id, $part['SectionFeatured']['locale']));
			}
			
			$this->Session->setFlash(
				'<p>Parte añadida correctamente.</p>',
				'flash_ok'
			);
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id, $part['SectionFeatured']['locale']));
		}

		$this->set("section", $section);
	}

	public function edit($id, $id_part){
		$this->SectionFeatured->Section->id = $id;
		$section = $this->SectionFeatured->Section->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		$this->SectionFeatured->id = $id_part;
		$part = $this->SectionFeatured->read();

		//If trying to edit a part that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {			

			try{
				//Edit the article
				$this->SectionFeatured->edit($part, $this->request->data);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				//if errors
				//re-asign the data
				$part = $this->SectionFeatured->add_media_data($part);
				$this->request->data = $section;
			}
			$this->Session->setFlash(
				'<p>Parte editada correctamente.</p>',
				'flash_ok'
			);
			//redirection
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id, $this->request->data['SectionFeatured']['locale']));
		}
		
		//re-asign the data
		$part = $this->SectionFeatured->add_media_data($part);
		$this->request->data = $part;

		//put data in session
		$this->set('section', $section);
		$this->set('part', $part);
	}

	public function delete($id, $id_part) {
		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'index'));
		}

		//recover the entity
		$this->SectionFeatured->Section->id = $id;
		$section = $this->SectionFeatured->Section->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('action' => 'index'));
		}

		$this->SectionFeatured->id = $id_part;
		$part = $this->SectionFeatured->read();

		//If trying to remove a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id));
		}

		try{
			$this->SectionFeatured->remove($id_part);
		}catch(Exception $e){
			$this->SectionFeatured->setFlash(
				'<p>'. $e->getMessage() .'</p>', 
				'flash_error'
			);
		}

		//print trace
		$this->Session->setFlash(
			'<p>Parte eliminada correctamente.</p>', 
			'flash_ok'
		);

		return $this->redirect(array('controller' => 'sections', 'action' => 'edit', $id, $part['SectionFeatured']['locale']));
	}
}
?>