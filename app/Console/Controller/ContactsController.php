<?php
class ContactsController extends AppController{
	public $name = "Contacts";
	
	
	public function edit($id = null) {
		//Recover the contact
		$this->Contact->id = $id;
		$contact = $this->Contact->read();


		//If trying to edit a new that doesn't exists, redirect
		if (!$id || !$contact) {
			return $this->redirect(array('action' => 'index'));
		}

		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {			

			try{
				//Edit the contact
				$this->Contact->edit($contact, $this->request->data);
			}catch(Exception $e){
				$this->Session->setFlash(
					'<p>'. $e->getMessage() .'</p>', 
					'flash_error'
				);

				$this->request->data = $contact;
			}
			$this->Session->setFlash(
				'<p>Datos editados correctamente.</p>',
				'flash_ok'
			);
			//redirection
			return $this->redirect(array('action' => 'edit', $this->Contact->id));
		}
		
		$this->request->data = $contact;
		//put data in session
		$this->set('contact', $contact);
	}	
}
?>