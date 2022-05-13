<?php
// Aux configuration
$model_name = "SectionGallery";

$header = array(
    "title" => array(
        "name" => "Añadir galería",
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
        $section['Section']['name'] => array(
            'controller' => 'sections',
            'action' => 'edit',
            $section['Section']['id']
        ),
        "Añadir galería" => array()
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
		<?= $this->Form->input(
			'sort',
			array(
				'label' => 'Posición',
				'type' => 'number',
				'min' => '1',
				'default' => '1',
				'after' => '<div class="input-note">Especifica la posición de esta parte dentro de esta sección.</div>'
			)
		); ?>

		<?= $this->Form->input(
			'title',
			array(
				'label' => 'Título',
				'rows' => '1'
			)
		); ?>

		<?= $this->Form->input(
			'content',
			array(
				'rows' => '200',
				'label' => 'Descripción',
				'class' => 'ckeditor'
			)
		); ?>
	</div><!-- .form-block -->

	<section class="media form-block">
		<h3>Imagenes de la galería</h3>
		<section class="block-media block-gallery">
			<div class="input file color10">
				<label class="imagen-add" for="gallery_img[]">Imágenes de la galería</label>
				<input type="file" name="data[SectionGallery][gallery_img][]" multiple="multiple" id="gallery_img[]">
			</div>
			<div class="clearboth"></div>
			<div class="filename">
				<p class="title">Ningún archivo seleccionado</p>
			</div><!-- .filename -->

			<div class="clearboth"></div>
		</section><!-- block-media -->
	</section><!-- .media.form-block -->

	<?= $this->Form->submit(
		'Añadir galería',
		array(
			'class' => 'button color10'
		)
	); ?>
	<div class="clearboth"></div>
<?= $this->Form->end(); ?>
