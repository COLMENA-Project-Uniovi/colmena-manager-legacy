<?php
class ColmenaSubjectUsersController extends AppController{
    public $name = "ColmenaSubjectUsers";

    //pagination
    public $paginate = array(
        'limit' => 20,
        'order' => array(
            'user_id' => 'asc'
        )
    );

   public function index($subject_id = null, $group_id = null) {

        //recover the paginate default configuration
        $settings = $this->paginate;
        // If performing search, there is a keyword
        if($subject_id != null){
            // Change pagination conditions for searching
            $settings['conditions'] = array(
                $this->modelClass.'.subject_id ' => $subject_id,
                $this->modelClass.'.group_id ' => $group_id,
            );
        }

        //prepare the pagination
        $this->Paginator->settings = $settings;

        //recover the data
        $entities = $this->Paginator->paginate($this->model_name);

        //putting data in session
        $this->set('entities', $entities);  
    }


    public function add() {
        $this->loadModel('ColmenaUser');
        // Exception control
        $flag = false;
        // If request is type post, add new entity
        if ($this->request->is('post')) {
            $entity = $this->request->data;
            try{
                $add_result = $this->{$this->modelClass}->add($entity);
            }catch(Exception $e){
                $this->Session->setFlash(
                    '<p>'. $e->getMessage() .'</p>',
                    'flash_error'
                );

                $flag = true;

                $this->request->data = $entity;
            }

            if (!$flag) {
                $this->Session->setFlash(
                    '<p>Usuario de colmena añadido al grupo correctamente.</p>',
                    'flash_ok'
                );
                return $this->redirect(array('action' => 'index', $this->params['pass'][0],
                  $this->params['pass'][1]));
            }
        }

        $users = $this->ColmenaUser->find("all", array(
            'fields' => array('id')
            )
        );

        $users_modified = array();
        foreach ($users as $key => $value) {
            $users_modified[$value['ColmenaUser']['id']] = $value['ColmenaUser']['id'];
        }

        $this->set('users', $users_modified);

    }


    public function edit($id = null, $locale = null) {
        // Exception control
        // Exception control
        $flag = false;
        //Recover the entity
        $entity = $this->{$this->modelClass}->get_entity($id, $locale);

        //If trying to edit a new that doesn't exists, redirect
        if (!$id || !$entity) {
            return $this->redirect(array('action' => 'index'));
        }

        // If we are submitting edition form
        if ($this->request->is(array('post', 'put'))) {
            try{
                //Edit the entity
                $this->{$this->modelClass}->edit($entity, $this->request->data);

                // Re-assign data
                $entity = $this->{$this->modelClass}->read();
            }catch(Exception $e){
                $this->Session->setFlash(
                    '<p>'. $e->getMessage() .'</p>',
                    'flash_error'
                );

                $flag = true;
            }

            if (!$flag) {
                $this->Session->setFlash(
                    '<p>Datos editados correctamente.</p>',
                    'flash_ok'
                );
            }
        }

        $this->request->data = $entity;
        //put data in session
        $this->set('entity', $entity);
    }


    public function delete($id = null) {
        // Exception control
        $flag = false;
        //only post
        if ($this->request->is('get')) {
            return $this->redirect(array('action' => 'index'));
        }

        //recover the entity
        $this->{$this->modelClass}->id = $id;
        $entity = $this->{$this->modelClass}->read();

        // If trying to remove a element that doesn't exists, redirect
        if (!$id || !$entity) {
            return $this->redirect(array('action' => 'index'));
        }

        try{
            $this->{$this->modelClass}->remove($id);
        }catch(Exception $e){
            $this->Session->setFlash(
                '<p>'. $e->getMessage() .'</p>',
                'flash_error'
            );

            $flag = true;
        }

        if (!$flag) {
            $this->Session->setFlash(
                '<p>Usuario eliminado del grupo correctamente.</p>',
                'flash_ok'
            );
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function sort() {
        foreach ($this->data[$this->modelClass] as $key => $value) {
            $this->{$this->modelClass}->id = $value;
            $this->{$this->modelClass}->saveField("sort", $key + 1);
        }

        exit();
    }
}
?>
