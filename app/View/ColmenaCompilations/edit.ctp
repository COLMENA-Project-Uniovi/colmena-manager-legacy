<?php
// Aux configuration
$model_name = 'ColmenaCompilation';
$controller_name = 'colmena_compilations';
$title_header = "Asignar compilación";
$model_color = "color1";

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
        "Compilaciones" => array(
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
		<?= $this->Form->input(
			'session_id',
			array(
				'label' => 'Sesiones disponibles',
				'options' => $sessions
			)
		); ?>

		<div class="clearboth"></div>
	</section><!-- .form-block -->

 <?= $this->Form->submit(
        'Asignar compilación',
        array(
            'class' => 'button ' . $model_color
        )
    ); ?>

    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
