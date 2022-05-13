<?php
// Aux configuration
$model_name = 'ColmenaUser';
$controller_name = 'ColmenaUsers';
$model_color = "color2";


$header = array(
    "title" => array(
        "name" => "Añadir nuevo usuario colmena",
        "color" => $model_color
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Usuarios" => array(
            'controller' => $controller_name,
            'action' => 'index'
        ),
        "Nuevo usuario" => array()
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
		'action' => 'add',
		'class' => 'admin-form'
	)
); ?>
	<div class="form-block">
		<h3>Datos del usuario</h3>

		<?= $this->Form->input(
			'name',
			array(
				'type' => 'text',
				'label' => 'Nombre real'
			)
		); ?>
		<?= $this->Form->input(
			'surname',
			array(
				'label' => 'Apellido 1'
			)
		); ?>
		<?= $this->Form->input(
			'surname2',
			array(
				'label' => 'Apellido 2'
			)
		); ?>

		<?= $this->Form->input(
			'id',
			array(
				'type' => 'text',
				'label' => 'UO (Identificador)'
			)
		); ?>

		<?= $this->Form->input(
			'dni',
			array(
				'label' => 'Dni'
			)
		); ?>
		<?= $this->Form->input(
			'password',
			array(
				'type' => 'password',
				'label' => 'Contraseña'
			)
		); ?>
		<?= $this->Form->input(
			'password-2',
			array(
				'type' => 'password',
				'label' => 'Repetir contraseña'
			)
		); ?>
	</div><!-- .form-block -->

	<?= $this->Form->submit(
		'Añadir usuario',
		array(
			'class' => 'button color2'
		)
	); ?>
	<div class="clearboth"></div>
<?= $this->Form->end(); ?>
