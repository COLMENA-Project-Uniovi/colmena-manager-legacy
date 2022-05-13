<?php
class ColmenaSubjectUser extends AppModel {
    public $name = 'ColmenaSubjectUser';
    public $useTable = "colmena_user_subject";

    public $belongsTo = array(
        "ColmenaSubject" => array(          
            'className' => 'ColmenaSubject',            
            'foreignKey' => 'subject_id'
        ),
        "ColmenaGroup" => array(            
            'className' => 'ColmenaGroup',            
            'foreignKey' => 'group_id'
        ),
        "ColmenaUser" => array(          
            'className' => 'ColmenaUser',            
            'foreignKey' => 'user_id'
        ),
    );

    public function add($post_entity){  
        
        if (!$this->save($post_entity)) {           
            throw new Exception ("Ha habido un error añadiendo al usuario al grupo.");
        }   
        return true;
    }


    public function remove($entity_id){
        if (!$this->delete($entity_id, true)) {
            throw new Exception("Ha habido un error eliminando el usuario del grupo.");
        }
        return true;
    }

}
?>
