<?php
// Aux configuration
$model_name = 'ColmenaSubject';
$controller_name = 'colmena_subjects';
$title_header = "Crear asignatura";
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
            'controller' => $controller_name,
            'action' => 'index'
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
		<h3>Datos generales de la asignatura</h3>
		<div class="col-left">
			<h4>En español</h4>
			<?= $this->Form->input(
				'subject_name_es',
				array(
					'label' => 'Nombre de la asignatura',
					'rows' => 1
				)
			); ?>
			<?= $this->Form->input(
				'subject_description_es',
				array(
					'type' => 'text',
					'label' => 'Descripción',
					'rows' => 2,
					'class' => 'ckeditor'
				)
			); ?>
		</div>
		<div class="col-right">
			<h4>En inglés</h4>
			<?= $this->Form->input(
				'subject_name_en',
				array(
					'label' => 'Nombre de la asignatura',
					'rows' => 1
				)
			); ?>

			<?= $this->Form->input(
				'subject_description_en',
				array(
					'type' => 'text',
					'label' => 'Descripción',
					'rows' => 2,
					'class' => 'ckeditor'
				)
			); ?>
		</div>
		<div class="col-left">
			<?= $this->Form->input(
				'color',
				array(
					'label' => 'Indique un color identificativo para la asignatura',
					'class' => 'jscolor',
					'value' => 'fe9810',
					'rows' => 1
				)
			); ?>
		</div>
		<div class="col-right">
			<?= $this->Form->input(
				'academic_year',
				array(
					'label' => 'Año académico (por ejemplo, 2017/2018)',
					'rows' => 1
				)
			); ?>
		</div>
		<div class="col-left">
			<?= $this->Form->input(
	            'start_date',
	            array(
	                'label' => 'Fecha de comienzo',
	                'class' => 'datepicker',
	                'value' => CakeTime::format(time(), "%d-%m-%Y"),
	                'type' => 'text',
	                'after' => '<div class="input-note">Introduce la fecha del tipo "DD-MM-AAAA".</div>'
	            )
	        ); ?>
	    </div>
		<div class="col-right">
	        <?= $this->Form->input(
	            'end_date',
	            array(
	                'label' => 'Fecha de fin',
	                'class' => 'datepicker',
	                'value' => CakeTime::format(time(), "%d-%m-%Y"),
	                'type' => 'text',
	                'after' => '<div class="input-note">Introduce la fecha del tipo "DD-MM-AAAA".</div>'
	            )
	        ); ?>
	    </div>
        <?= $this->Form->input(
			'table_name',
			array(
				'label' => 'Table de la base de datos asociada (por ejemplo, colmena_marker_al_2015)',
				'rows' => 1
			)
		); ?>
	</section><!-- .form-block -->


	 <?php
	 	/*
	 	echo $this->element(
	        'seo-form-block',
	        array(
	            "model_name" => $model_name
	        )
    	);
    	*/
    ?>

	

	<?= $this->Form->submit(
        'Crear asignatura',
        array(
            'class' => 'button '. $model_color
        )
    ); ?>
</br>
	<?= $this->Form->end(); ?>
