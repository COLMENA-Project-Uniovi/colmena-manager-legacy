<?php
class ColmenaUsersController extends AppController{
    public $name = "ColmenaUsers";

    //pagination
    public $paginate = array(
        'limit' => 20,
        'order' => array(
            'id' => 'asc'
        )
    );

    public function index($subject_id = null, $keyword = null) {
        //if search
        if ($this->request->is('post')) {
            //recover the keyword
            $keyword = $this->request->data[$this->modelClass]["keyword"];
            //re-send to the same controller with the keyword
            return $this->redirect(array('action' => 'index', $keyword));
        }

        //recover the paginate default configuration
        $settings = $this->paginate;
        // If performing search, there is a keyword
        if($keyword != null){
            // Change pagination conditions for searching
            $settings['conditions'] = array('OR' =>
                array(
                    $this->modelClass.'.title LIKE' => '%'.$keyword.'%',
                    $this->modelClass.'.city LIKE' => '%'.$keyword.'%',
                    $this->modelClass.'.subject_id' => $subject_id
                )
            );
        }

        //prepare the pagination
        $this->Paginator->settings = $settings;

        //recover the data
        $entities = $this->Paginator->paginate($this->modelClass); 

        //putting data in session
        $this->set('entities', $entities);
        $this->set("keyword", $keyword);
    }


    public function add() {
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
                    '<p>Usuario de colmena añadido correctamente.</p>',
                    'flash_ok'
                );
                return $this->redirect(array('action' => 'index'));
            }
        }
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

    public function change_visible($id = null){
        //recover the entity
        $this->{$this->modelClass}->id = $id;
        $entity = $this->{$this->modelClass}->read();

        //If trying to edit an entity that doesn't exists, redirect
        if (!$id || !$entity) {
            return $this->redirect(array('action' => 'index'));
        }

        $status = false;

        //control visibility
        if($entity[$this->modelClass]['active']){
            $status = $this->{$this->modelClass}->saveField("active", "0");
        }else{
            $status = $this->{$this->modelClass}->saveField("active", "1");
        }

        //if changed
        if(!$status){
            $this->set("status", "ERROR");
        }else{
            $this->set("status", "OK");
        }

        //render special view for ajax
        $this->render('change_visible', 'ajax');
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
                '<p>Datos eliminados correctamente.</p>',
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
