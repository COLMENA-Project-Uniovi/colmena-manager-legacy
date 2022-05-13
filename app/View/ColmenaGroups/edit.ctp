<?php
// Aux configuration
$model_name = 'ColmenaGroup';
$controller_name = 'colmena_groups';
$title_header = "Editar grupo";
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
        "Grupos" => array(
            'controller' => $controller_name,
            'action' => 'index',
            $entity[$model_name]['subject_id']
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

	
	<section class="form-block">
		<h3>Datos generales del grupo</h3>
		<div class="col-left">
			<?= $this->Form->input(
				'group_name',
				array(
					'label' => 'Nombre del grupo',
					'rows' => 1
				)
			); ?>
		</div>
		<div class="col-right">
			<?= $this->Form->input(
				'day_of_week',
				array(
					'label' => 'Día de la semana en que se imparte (L, M, X, J, V)',
					'rows' => 1
				)
			); ?>
		</div>
		<div class="col-left">
			<?= $this->Form->input(
				'start_hour',
				array(
					'label' => 'Hora de inicio de la clase (ej: 15:00)',
					'type' => 'text',
	                'default' => CakeTime::format(time(), "%H:%M:%S"),
	                'after' => '<div class="input-note">Introduce la hora a la comienza la clase del grupo "HH:MM:SS".</div>'
				)
			); ?>
		</div>
		<div class="col-right">
			<?= $this->Form->input(
				'end_hour',
				array(
					'label' => 'Hora de finalización de la clase (ej: 17:30)',
					'type' => 'text',
	                'default' => CakeTime::format(time(), "%H:%M:%S"),
	                'after' => '<div class="input-note">Introduce la hora a la finaliza la clase del grupo "HH:MM:SS".</div>'
				)
			); ?>
		</div>
	</section><!-- .form-block -->
	

	<?= $this->Form->submit(
        'Guardar cambios',
        array(
            'class' => 'button '. $model_color
        )
    ); ?>
	<?= $this->Form->end(); ?>
