<div class="users form">
<?php 
	echo $this->Session->flash('auth'); 
 	echo $this->Form->create('User', array('class' => 'form-administrador corto'));
    echo $this->Form->input('username', array('label' => 'Usuario'));
    echo $this->Form->input('password', array('label' => 'Contraseña'));  
    echo $this->Form->end('CREAR'); 
 ?>
</div>