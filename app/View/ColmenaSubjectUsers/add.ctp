<?php
// Aux configuration
$model_name = 'ColmenaSubjectUser';
$controller_name = 'ColmenaSubjectUsers';
$model_color = "color2";


$header = array(
    "title" => array(
        "name" => "Añadir usuario colmena a grupo",
        "color" => $model_color
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Usuarios del grupo" => array(
            'controller' => $controller_name,
            'action' => 'index'
        ),
        "Añadir usuario a grupo" => array()
    )
);
?>


	<?= $this->element(
	    "header",
	    $header
	); ?>

	<?= $this->Form->create(
	    $model_name,
	    array(
	        'class' => 'admin-form',
	        'type' => 'file'
	    )
	); ?>

	<?= $this->Form->input(
		'subject_id',
		array(
			'type' => 'hidden',
			'value' => $this->params['pass'][0]
		)
	); ?>

	<?= $this->Form->input(
		'group_id',
		array(
			'type' => 'hidden',
			'value' => $this->params['pass'][1]
		)
	); ?>
	<div class="form-block">
		<h3>Selecciona un usuario</h3>

		<?= $this->Form->input(
				'user_id',
				array(
					'label' => 'Identificador del usuario',
					'options' => $users
				)
			); ?>
	</div><!-- .form-block -->

	<?= $this->Form->submit(
		'Añadir usuario al grupo',
		array(
			'class' => 'button color2'
		)
	); ?>
	<div class="clearboth"></div>
<?= $this->Form->end(); ?>
