<?php
// Aux configuration
$auto_update = '&time='.time();
$main_image_width = '&w=700&h=120';
$extra_images_width = '&w=204&h=204';

$model_name = 'Section';
$entity_id = $entity[$model_name]['id'];
$color = 'color'. $entity[$model_name]['parent_id'];

$generic_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $entity[$model_name]['content_path'] ."/". Configure::read("generic_image") . $main_image_width;
$specific_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $entity[$model_name]['img_url'] . $main_image_width;
$specific_path_css = Configure::read("base_url") . Configure::read("lib"). DS . Configure::read("timthumb") . Configure::read("resources_path") . DS . $entity[$model_name]['img_url'] . $main_image_width;


$title = "Editar sección \"" . $entity[$model_name]['name'] . "\"";
if(!empty($entity['default'])){
    $title = "Editar sección \"" . $entity['default'][$model_name]['name'] . "\"";
}

$header = array(
    "title" => array(
        "name" => $title,
        "color" => $color
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
        "Editar sección" => array()
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
    <div class="content-tabs <?= $color ?>">
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

        <h2><?= $entity['default'][$model_name]['name']; ?></h2>

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
        'class' => 'admin-form ',
        'type' => 'file'
    )
); ?>

    <div class="form-block">
        <h3>Datos generales de la sección</h3>

        <?= $this->Form->input('parent_id',
            array(
                'label' => "Sección padre",
                'options' => $sections,
                'empty' => array("0" => "-- Sin sección padre --"),
                'disabled' => 'disabled'
            )
        ); ?>

        <?= $this->Form->input(
            'name',
            array(
                'label' => 'Nombre de la sección',
                'type' => 'text'
            )
        ); ?>
    </div><!-- .form-block -->

    <?= $this->element(
        'seo-form-block',
        array(
            "model_name" => $model_name
        )
    ); ?>

    <div class="media form-block">
        <h3>Multimedia</h3>
        <section class="block-media block-gallery">
<?php
            foreach($entity[$model_name]['gallery'] as $image):
?>
                <section class="extra-image">
                    <?= $this->Html->image(
                        Configure::read("timthumb") . Configure::read("resources_path") . DS . $image . $extra_images_width.$auto_update,
                        array('alt' => $model_name));
                    ?>
                    <p class="delete <?= $color ?>">
<?=
                        $this->Html->link("Eliminar",
                            array(
                                'controller' => 'sections',
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
            <div class="input file <?= $color ?>">
                <label class="imagen-add" for="extra_img[]">Subir imágenes de fondo de la sección</label>
                <input type="file" name="data[Section][gallery_img][]" multiple="multiple" id="extra_img[]">
            </div>

            <div class="filename">
                <p class="title">Ningún archivo seleccionado</p>
            </div>
        </section><!-- .block-media -->
    </div><!-- .form-block -->

    <?= $this->Form->submit(
        'Editar sección',
        array(
            'class' => 'button ' . $color
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>

<?php
    if ($entity['Section']['has_parts'] == 1) {
        echo $this->element(
            'section-parts',
            array(
                "model_name" => $model_name,
                "entity" => $entity,
                "parts" => $parts
            )
        );
    } else {
        if ($entity['Section']['admin_controller'] != "") {
?>
<header>
    <h2 class="<?= $color ?>">
    <?= $this->Html->link(
        "Pincha aquí para ver los elementos de esta sección",
        array(
            'controller' => $entity['Section']['admin_controller'],
            'action' => 'index'
        )
    ); ?>
    </h2>
</header>
<?php
        }
    }
?>
<div class="clearboth"></div>
