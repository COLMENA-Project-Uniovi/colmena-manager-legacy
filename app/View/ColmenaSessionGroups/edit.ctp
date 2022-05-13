<?php
// Aux configuration
$model_name = 'ColmenaSessionGroup';
$controller_name = 'ColmenaSessionGroups';
$title_header = "Crear clase práctica";
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
            'action' => 'index',
        ),
        "Calendario" => array(
            'controller' => $controller_name,
            'action' => 'index',
            $this->params['pass'][1]
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
		<h3>Datos de la nueva clase práctica</h3>	
		<div class="col-left">
			
			<?= $this->Form->input(
				'session_id',
				array(
					'label' => 'Sesión a impartir en la clase',
					'options' => $sessions
				)
			); ?>
			<?= $this->Form->input(
				'start_hour',
				array(
					'label' => 'Hora de inicio de la clase (ej: 15:00)',
					'type' => 'text',
	                'default' => CakeTime::format(time(), "%H:%M:%S"),
	                'after' => '<div class="input-note">Introduce la hora a la comienza la clase práctica "HH:MM:SS".</div>'
				)
			); ?>
			<?= $this->Form->input(
	            'session_day',
	            array(
	                'label' => 'Día de la clase',
	                'class' => 'datepicker',
	                'value' => CakeTime::format(time(), "%d-%m-%Y"),
	                'type' => 'date',
	                'after' => '<div class="input-note">Introduce la fecha de impartición de la clase. </div>'
	            )
	        ); ?>
		</div>
		<div class="col-right">
			  <?= $this->Form->input(
				'group_id',
				array(
					'label' => 'Grupo de la clase',
					'options' => $groups
				)
			); ?>
			<?= $this->Form->input(
				'end_hour',
				array(
					'label' => 'Hora de finalización de la clase (ej: 17:30)',
					'type' => 'text',
	                'default' => CakeTime::format(time(), "%H:%M:%S"),
	                'after' => '<div class="input-note">Introduce la hora a la finaliza la clase práctica "HH:MM:SS".</div>'
				)
			); ?>
						  <?= $this->Form->input(
				'location',
				array(
					'label' => 'Localización/Laboratorio (ej: AS-02)',
					'rows' => 1
				)
			); ?>
		</div>
		</br>
		<div class="clearboth"></div>
	</section><!-- .form-block -->
	

	<?= $this->Form->submit(
        'Guardar cambios',
        array(
            'class' => 'button '. $model_color
        )
    ); ?>
	<?= $this->Form->end(); ?>
