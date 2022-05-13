<?php

// app/Controller/UsersController.php
class UsersController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
    }

    public function login() {   
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            }
            $this->layout = 'login';
            $this->Session->setFlash('Usuario o contraseña incorrectos', 'default', array(), 'auth');
        }else{
            $this->layout = 'login';
            $this->Session->setFlash('Para acceder al portal es necesario usuario y contraseña', 'default', array(), 'auth');
        }
    }

	public function logout() {
	    return $this->redirect($this->Auth->logout());
	}


    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Usuario guardado con éxito'));
                return $this->redirect(array('action' => 'login'));
            }
            $this->Session->setFlash(('Errores guardando el usuario.'));
        }
    }

    public function index(){
        $this->redirect(array('action' => 'login'));
    }

}

?>