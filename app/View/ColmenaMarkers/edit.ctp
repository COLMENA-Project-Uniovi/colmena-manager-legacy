<?php
// Aux configuration
$model_name = 'Property';
$controller_name = 'properties';
$entity_id = $entity[$model_name]['id'];
$title_header = "Editar propiedad";
$model_color = "color4";
$auto_update = '&time='.time();
$main_image_width = '&w=700&h=120';
$extra_images_width = '&w=200&h=100';
$generic_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $entity[$model_name]['content_path'] .DS. 'generic'.DS.'principal.jpg' . $main_image_width;
$specific_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $entity[$model_name]['img_url'] . $main_image_width;

$header = array(
    "title" => array(
        "name" =>$title_header,
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

<?php
	if(count(Configure::read('Config.locales.available')) > 1){
?>
<section class="languages">
	<div class="content-tabs <?= $model_color; ?>">
<?php
		foreach (Configure::read('Config.locales.available') as $code => $name) {
			$current = "";
			if($code == $entity[$model_name]['locale']){
				$current = "current";
			}
			echo $this->Html->link($name, array('action' => 'edit', $entity_id, $code), array('class' => 'tab ' . $current));
		}
?>
	</div><!-- .tabs-content -->
<?php
		if(!empty($entity['default'])){
?>
	<div class="admin-view default-content">
		<h3 class="default-title">Contenido en el idioma por defecto</h3>

		<h2><?= $entity['default'][$model_name]['title']; ?></h2>
		<h3><?= $entity['default']['Type']['name']; ?></h3>

		<p class="date">Publicado el <?= CakeTime::format($entity['default'][$model_name]['published'], "%d-%m-%Y");?></p>

		<section class="description">
			<?= $entity ['default'][$model_name]['content'];?>
			<section class="map">
				<h4>Dirección y coordenadas GPS</h4>
				<ul>
					<li><?= $entity['default'][$model_name]['address1'].", ".$entity['default'][$model_name]['address2']; ?></li>
					<li>Latitud: <?= $entity['default'][$model_name]['latitude']; ?></li>
					<li>Longitud: <?= $entity['default'][$model_name]['longitude']; ?></li>
				</ul>
			</section><!-- .map -->
		</section><!-- .ficha-descripcion -->
		<div class="clearboth"></div>
	</div><!-- .admin-view -->
<?php
		}
?>
</section><!-- .languages -->
<?php
	}
?>

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
				'label' => 'Título',
				'rows' => 2
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
		<div class="clearboth"></div>
	</section><!-- .form-block -->

	<section class="form-block">
		<h3>Servicios</h3>
		<?= $this->Form->input('services',
			array(
				'type' => 'text',
				'label' => "Selecciona los servicios de los que dispone esta propiedad",
				'value' => implode(",", $entity['Service']),
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
		<?= $this->Form->input(
			'published',
			array(
				'label' => 'Fecha de publicación',
				'class' => 'datepicker',
				'value' => CakeTime::format($entity[$model_name]['published'], "%d-%m-%Y"),
				'type' => 'text',
				'after' => '<div class="input-note">Introduce la fecha a la que se publicará esta propiedad del tipo "DD-MM-AAAA".</div>'
			)
		); ?>

		<?= $this->Form->input(
			'hour',
			array(
				'label' => 'Hora de publicación',
				'type' => 'text',
				'after' => '<div class="input-note">Introduce la hora a la que se publicará esta propiedad del tipo "HH:MM:SS".</div>'
			)
		);?>
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
			<h3>Imagen principal</h3>
<?php
			if($entity[$model_name]['img_url'] != ""){
				echo $this->Html->image($specific_path.$auto_update, array('alt' => 'Propiedad', 'class' => 'main-image'));
			}

			echo $this->Form->input(
				'main_img',
				array(
					'label' => 'Subir nueva imagen',
					'type' => 'file',
					'div' => 'input file '.$model_color
				)
			);
?>
			<div class="filename">
				<p class="title">Ningún archivo seleccionado</p>
			</div><!-- .filename -->
		</section><!-- .block-media -->

		<section class="block-media block-gallery">
			<h3>Imágenes secundarias</h3>
<?php
			foreach($entity[$model_name]['gallery'] as $image):
?>
				<section class="extra-image">
					<?= $this->Html->image(
						Configure::read("timthumb") . Configure::read("resources_path") . DS . $image . $extra_images_width.$auto_update,
						array('alt' => $model_name));
					?>
					<p class="delete <?= $model_color; ?>">
<?=
						$this->Html->link("Eliminar",
							array(
								'controller' => 'properties',
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
			<div class="input file <?= $model_color; ?>">
				<label class="imagen-add" for="extra_img[]">Subir más imágenes</label>
				<input type="file" name="data[Property][gallery_img][]" multiple="multiple" id="extra_img[]">
			</div>

			<div class="filename">
				<p class="title">Ningún archivo seleccionado</p>
			</div><!-- .filename -->
		</section><!-- block-media -->

		<section class="block-media block-attachments">
			<h3>Archivos adjuntos</h3>
<?php
			foreach($entity[$model_name]['attach'] as $file):
?>
				<section class="files">
					<?= $this->Html->link(
						basename($file),
						Configure::read("base_url") . DS . Configure::read("resources_path") . DS .  $file ,
						array('target' => '_blank')); ?>
				</section>
					<p class="delete <?= $model_color; ?>">
<?=
						$this->Html->link("Eliminar",
							array(
								'controller' => 'properties',
								'action' => 'delete_resource',
								$entity_id,
								"attach",
								basename($file)
							),
							array(),
							'¿Está seguro de que desea eliminar este archivo?'
						);
?>
					</p>
<?php
			endforeach;
?>
			<div class="clearboth"></div>
			<div class="input file <?= $model_color; ?>">
				<label class="imagen-add" for="attach[]">Subir más archivos</label>
				<input type="file" name="data[Property][attach][]" multiple="multiple" id="attach[]">
			</div>

			<div class="filename">
				<p class="title">Ningún archivo seleccionado</p>
			</div><!-- .filename -->
		</section><!-- block-media -->
		<div class="clearboth"></div>
	</section><!-- .media -->

 <?= $this->Form->submit(
        'Guardar propiedad',
        array(
            'class' => 'button ' . $model_color
        )
    ); ?>

    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
