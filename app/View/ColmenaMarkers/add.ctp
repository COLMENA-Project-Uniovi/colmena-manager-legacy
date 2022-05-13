<?php
// Aux configuration
$model_name = 'Property';
$controller_name = 'properties';
$title_header = "Añadir nueva propiedad";
$model_color = "color4";

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
        "Propiedades" => array(
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
		<h3>Datos generales de la propiedad</h3>
		<?= $this->Form->input(
			'title',
			array(
				'label' => 'Nombre de la propiedad',
				'rows' => 2
			)
		); ?>

		<?= $this->Form->input(
			'content',
			array(
				'rows' => '200',
				'label' => 'Descripción de la propiedad',
				'class' => 'ckeditor'
			)
		); ?>

		<?= $this->Form->input(
			'type_id',
			array(
				'label' => 'Tipo de propiedad',
				'options' => $types
			)
		); ?>

		<?= $this->Form->input(
			'price',
			array(
				'label' => 'Precio (€)',
				'min' => 0,
				'default' => 0
			)
		); ?>
		<?= $this->Form->input(
			'rooms',
			array(
				'label' => 'Nº de habitaciones',
				'min' => 0,
				'default' => 0
			)
		); ?>
		<?= $this->Form->input(
			'baths',
			array(
				'label' => 'Nº de baños',
				'min' => 0,
				'default' => 0
			)
		); ?>
		<?= $this->Form->input(
			'surface',
			array(
				'label' => 'Superficie interior (m2)',
				'min' => 0,
				'default' => 0
			)
		); ?>
		<?= $this->Form->input(
			'exterior',
			array(
				'label' => 'Superficie exterior (m2)',
				'min' => 0,
				'default' => 0
			)
		); ?>
	</section><!-- .form-block -->

	<section class="form-block">
		<h3>Servicios</h3>
		<?= $this->Form->input('services',
			array(
				'type' => 'text',
				'label' => "Selecciona los servicios de los que dispone esta propiedad",
				'class' => 'tokens',
				'after' => '<div class="input-note">Puedes añadir más servicios separándolos por comas.</div>'
			)
		); ?>
		<script type="text/javascript">
			$(".tokens").select2({
				width : "100%",
				tags : [<?= '"'. implode('","', $services).'"'; ?>],
				tokenSeparators : [","]
			});
		</script>
	</section><!-- .form-block -->

	<section class="form-block">
		<h3>Fecha y hora de publicación</h3>
		<?= $this->Form->input('published',
			array(
				'label' => 'Fecha de publicación',
				'class' => 'datepicker',
				'value' => CakeTime::format(time(), "%d-%m-%Y"),
				'type' => 'text',
				'after' => '<div class="input-note">Introduce la fecha a la que se publicará esta propiedad del tipo "DD-MM-AAAA".</div>'
			)
		); ?>

		<?= $this->Form->input('hour',
			array(
				'label' => 'Hora de publicación',
				'type' => 'text',
				'default' => "00:00:00",
				'after' => '<div class="input-note">Introduce la hora a la que se publicará esta propiedad del tipo "HH:MM:SS".</div>'
			)
		); ?>
	</section><!-- .form-block -->

	 <?= $this->element(
        'map-form-block',
        array(
            "model_name" => $model_name
        )
    ); ?>

	 <?= $this->element(
        'seo-form-block',
        array(
            "model_name" => $model_name
        )
    ); ?>

	<section class="media form-block">
		<h3>Archivos multimedia</h3>
		<section class="block-media block-main">
			<?= $this->Form->input(
					'main_img',
					array(
						'label' => 'Imagen principal de la propiedad',
						'div' => 'input file ' . $model_color ,
						'type' => 'file'
					)
				);
			?>
			<div class="filename">
				<p class="title">Ningún archivo seleccionado</p>
			</div><!-- .filename -->
			<div class="clearboth"></div>
		</section><!-- .block-media -->

		<section class="block-media block-gallery">
			<div class="input file <?= $model_color; ?>">
				<label class="imagen-add" for="gallery_img[]">Imágenes adicionales para la propiedad</label>
				<input type="file" name="data[Property][gallery_img][]" multiple="multiple" id="gallery_img[]">
			</div>
			<div class="clearboth"></div>
			<div class="filename">
				<p class="title">Ningún archivo seleccionado</p>
			</div><!-- .filename -->

			<div class="clearboth"></div>
		</section><!-- block-media -->

		<section class="block-media block-attachments">
			<div class="input file <?= $model_color; ?>">
				<label class="imagen-add" for="attach[]">Archivos adjuntos para la propiedad</label>
				<input type="file" name="data[Property][attach][]" multiple="multiple" id="attach[]">
			</div>
			<div class="clearboth"></div>
			<div class="filename">
				<p class="title">Ningún archivo seleccionado</p>
			</div><!-- .filename -->
		</section><!-- .block-media -->

		<div class="clearboth"></div>
	</section><!-- .media-->

	<?= $this->Form->submit(
        'Añadir propiedad',
        array(
            'class' => 'button '. $model_color
        )
    ); ?>
	<?= $this->Form->end(); ?>
