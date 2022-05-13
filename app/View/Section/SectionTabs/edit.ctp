<?php
// Aux configuration
$entity_id = $part['SectionTab']['id'];

$model_name = 'SectionTab';

$header = array(
    "title" => array(
        "name" => "Editar pestañas",
        "color" => "color10"
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Secciones" => array(
            'controller' => 'sections',
            'action' => 'index'
        ),
        $part['Section']['name'] => array(
            'controller' => 'sections',
            'action' => 'edit',
            $part['Section']['id']
        ),
        "Editar pestañas" => array()
    )
);
?>

<?= $this->element(
    "header",
    $header
); ?>

<?= $this->Form->create(
	'SectionTab',
	array(
		'class' => 'admin-form',
		'type' => 'file'
	)
); ?>
	<div class="form-block">
		<h3>Detalles de la parte</h3>
		<?=
			$this->Form->input(
				'locale',
				array(
					'label' => 'Idioma' ,
					'options' => Configure::read('Config.locales.available'),
					'class' => 'select-wrap',
					'after' => '<div class="input-note">Selecciona el idioma al que se añadirá esta parte.</div>'
				)
			);
		?>
		<?=
			$this->Form->input(
				'sort',
				array(
					'label' => 'Posición',
					'type' => 'number',
					'min' => '1',
					'default' => '1',
					'after' => '<div class="input-note">Especifica la posición de esta parte dentro de esta sección.</div>'
				)
			);
		?>
	</div><!-- .form-block -->
	<div class="form-block">
		<h3>Introduce el contenido de las pestañas (deja en blanco aquellas que no quieras que aparezcan)</h3>

		<?=
			$this->Form->input(
				'tab_title1',
				array(
					'label' => 'Título 1',
					'rows' => '1'
				)
			);
		?>
		<?=
			$this->Form->input(
				'tab_content1',
				array(
					'class' => array('fullwidth', 'ckeditor'),
					'label' => 'Contenido 1',
					'rows' => '20',
					'after' => '<div class="clearboth"></div>'
				)
			);
		?>
	</div><!-- .form-block -->
	<div class="form-block">
		<?=
			$this->Form->input(
				'tab_title2',
				array(
					'label' => 'Título 2',
					'rows' => '1'
				)
			);
		?>
		<?=
			$this->Form->input(
				'tab_content2',
				array(
					'class' => array('fullwidth', 'ckeditor'),
					'label' => 'Contenido 2',
					'rows' => '20',
					'after' => '<div class="clearboth"></div>'
				)
			);
		?>
	</div><!-- .form-block -->
	<div class="form-block">
		<?=
			$this->Form->input(
				'tab_title3',
				array(
					'label' => 'Título 3',
					'rows' => '1'
				)
			);
		?>
		<?=
			$this->Form->input(
				'tab_content3',
				array(
					'class' => array('fullwidth', 'ckeditor'),
					'label' => 'Contenido 3',
					'rows' => '20',
					'after' => '<div class="clearboth"></div>'
				)
			);
		?>
	</div><!-- .form-block -->
	<div class="form-block">
		<?=
			$this->Form->input(
				'tab_title4',
				array(
					'label' => 'Título 4',
					'rows' => '1'
				)
			);
		?>
		<?=
			$this->Form->input(
				'tab_content4',
				array(
					'class' => array('fullwidth', 'ckeditor'),
					'label' => 'Contenido 4',
					'rows' => '20',
					'after' => '<div class="clearboth"></div>'
				)
			);
		?>

	</div><!-- .form-block -->

	<?= $this->Form->submit(
		'Editar parte',
		array(
			'class' => 'button color10'
		)
	); ?>
	<div class="clearboth"></div>
<?= $this->Form->end(); ?>
