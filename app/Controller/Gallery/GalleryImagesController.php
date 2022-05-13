<?php
class GalleryImagesController extends AppController{
	public $name = "GalleryImages";
	//path to the resources
	private $content_path = "galleries/images";

	public function add($id){
		// Exception control
        $flag = false;

		$this->GalleryImage->Gallery->id = $id;
		$entity = $this->GalleryImage->Gallery->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('controller' => 'galleries', 'action' => 'index'));
		}

		// If request is type post, add new entity
		if ($this->request->is('post')) {
			$part = $this->request->data;
			$part['GalleryImage']['gallery_id'] = $id;
			try{
				$add_result = $this->GalleryImage->add($part);
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
						'controller' => 'galleries',
						'action' => 'edit',
						$id,
						$part['GalleryImage']['locale']
					)
				);
			}
		}

		$this->set("section", $entity);
	}

	public function edit($id, $id_part){
		// Exception control
        $flag = false;

		$this->GalleryImage->Gallery->id = $id;
		$entity = $this->GalleryImage->Gallery->read();

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('controller' => 'galleries', 'action' => 'index'));
		}

		$this->GalleryImage->id = $id_part;
		$part = $this->GalleryImage->read();

		//If trying to edit a part that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'galleries', 'action' => 'edit', $id));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			try{
				//Edit the article
				$this->GalleryImage->edit($part, $this->request->data);

				// Re-assign data
                $part = $this->GalleryImage->read();
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
		$part = $this->GalleryImage->add_media_data($part);
		$this->request->data = $part;

		//put data in session
		$this->set('section', $entity);
		$this->set('part', $part);
	}

	public function delete($id, $id_part) {
		// Exception control
        $flag = false;

		//only post
		if ($this->request->is('get')) {
			return $this->redirect(array('controller' => 'galleries', 'action' => 'index'));
		}

		//recover the entity
		$this->GalleryImage->Gallery->id = $id;
		$entity = $this->GalleryImage->Gallery->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$entity) {
			return $this->redirect(array('action' => 'index'));
		}

		$this->GalleryImage->id = $id_part;
		$part = $this->GalleryImage->read();

		//If trying to remove a section that doesn't exists, redirect
		if (!$id_part || !$part) {
			return $this->redirect(array('controller' => 'galleries', 'action' => 'edit', $id));
		}

		try{
			$this->GalleryImage->remove($id_part);
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
				'controller' => 'galleries',
				'action' => 'edit',
				$id,
				$part['GalleryImage']['locale']
			)
		);
	}
}
?>
