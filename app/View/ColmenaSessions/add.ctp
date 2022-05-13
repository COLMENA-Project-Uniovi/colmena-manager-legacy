<?php
// Aux configuration
$model_name = 'ColmenaSession';
$controller_name = 'colmena_sessions';
$title_header = "Crear sesión";
$model_color = "color1";

$header = array(
    "title" => array(
        "name" => $title_header,
        "color" => $model_color
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Asignaturas" => array(
            'controller' => 'colmena_subjects',
            'action' => 'index'
        ),
        "Sesiones" => array(
            'action' => 'index',
            $this->params['pass'][0]
        ),
        $title_header => array()
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

	<section class="form-block">
		<h3>Datos generales de la sesión</h3>
		<div class="col-left">
			<h4>En español</h4>
			<?= $this->Form->input(
				'session_name_es',
				array(
					'label' => 'Nombre de la sesión',
					'rows' => 1
				)
			); ?>
			<?= $this->Form->input(
				'objectives_es',
				array(
					'type' => 'text',
					'label' => 'Objetivos',
					'rows' => 2,
					'class' => 'ckeditor'
				)
			); ?>
		</div>
		<div class="col-right">
			<h4>En inglés</h4>
			<?= $this->Form->input(
				'session_name_en',
				array(
					'label' => 'Nombre de la sesión',
					'rows' => 1
				)
			); ?>

			<?= $this->Form->input(
				'objectives_en',
				array(
					'type' => 'text',
					'label' => 'Objetivos',
					'rows' => 2,
					'class' => 'ckeditor'
				)
			); ?>
		</div>
		<?= $this->Form->input(
			'week',
			array(
				'label' => 'Número de semana en la que se imparte',
				'rows' => 1
			)
		); ?>
	</section><!-- .form-block -->
	

	<?= $this->Form->submit(
        'Crear sesión',
        array(
            'class' => 'button '. $model_color
        )
    ); ?>
	<?= $this->Form->end(); ?>
