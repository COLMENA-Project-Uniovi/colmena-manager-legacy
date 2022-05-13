<?php
class ArticleVersionsController extends AppController{
	public $name = "ArticleVersions";

	public function delete($id_version) {

		//recover the entity
		$this->ArticleVersion->id = $id_version;
		$version = $this->ArticleVersion->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id_version || !$version) {
			return $this->redirect(array('controller' => 'articles', 'action' => 'edit', $id));
		}

		try{
			$this->ArticleVersion->remove($id_version);
		}catch(Exception $e){
			$this->Session->setFlash(
				'<p>'. $e->getMessage() .'</p>',
				'flash_error'
			);
			return $this->redirect(array('controller' => 'articles', 'action' => 'edit', $version['Article']['id'], $version['ArticleVersion']['locale']));
		}

		//print trace
		$this->Session->setFlash(
			'<p>Versión eliminada correctamente.</p>',
			'flash_ok'
		);

		return $this->redirect(array('controller' => 'articles', 'action' => 'edit', $version['Article']['id'], $version['ArticleVersion']['locale']));
	}
}
?>
