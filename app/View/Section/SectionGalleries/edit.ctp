<?php
// Aux configuration
$auto_update = '&time='.time();
$main_image_width = '&w=700&h=120';
$extra_images_width = '&w=200&h=100';

$entity_id = $part['SectionGallery']['id'];

$generic_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $part['SectionGallery']['content_path'] ."/". Configure::read("generic_image") . $main_image_width;
$specific_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $part['SectionGallery']['img_url'] . $main_image_width;

$model_name = 'SectionGallery';

$header = array(
    "title" => array(
        "name" => "Editar galería",
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
        "Editar galería" => array()
    )
);

?>

<?= $this->element(
    "header",
    $header
); ?>

<section class="admin-edit">
	<?= $this->Form->create(
		$model_name,
		array(
			'class' => 'admin-form',
			'type' => 'file'
		)
	); ?>
	<section class="form-block">
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
				'rows' => '2'
			)
		); ?>
		<?= $this->Form->input(
			'content',
			array(
				'rows' => '20',
				'label' => 'Descripción',
				'class' => 'ckeditor'
			)
		); ?>
		<div class="clearboth"></div>
	</section><!-- .form-block -->

	<section class="media form-block">
		<h3>Imágenes de la galería</h3>

		<section class="block-media block-gallery">
		<?php
			foreach($part['SectionGallery']['gallery'] as $image):
		?>
				<section class="extra-image">
					<?= $this->Html->image(
						Configure::read("timthumb") . Configure::read("resources_path") . DS . $image . $extra_images_width.$auto_update,
						array('alt' => 'SectionGallery'));
					?>
					<p class="delete bancarios-color">
					<?=
						$this->Html->link(
							"Eliminar",
							array(
								'confirm' => '¿Está seguro de que desea eliminar este archivo?',
								'controller' => 'section_galleries',
								'action' => 'delete_resource',
								$entity_id,
								"gallery",
								basename($image)
							),
							array(),
							'¿Está seguro de que desea eliminar este archivo?'
						);
					?>
					</p>
				</section>
		<?php
			endforeach;
		?>
			<div class="clearboth"></div>
			<div class="input file bancarios-color">
				<label class="imagen-add" for="extra_img[]">Subir más imágenes</label>
				<input type="file" name="data[SectionGallery][gallery_img][]" multiple="multiple" id="extra_img[]">
			</div>

			<div class="filename">
				<p class="title">Ningún archivo seleccionado</p>
			</div><!-- .filename -->
		</section><!-- block-media -->

		<div class="clearboth"></div>
	</section><!-- .media.form-block -->
	<div class="clearboth"></div>

	<?= $this->Form->submit(
		'Guardar cambios',
		array(
			'class' => 'button color10'
		)
	); ?>
	<div class="clearboth"></div>
<?= $this->Form->end(); ?>
