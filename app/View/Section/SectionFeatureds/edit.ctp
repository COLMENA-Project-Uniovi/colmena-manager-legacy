<?php
// Aux configuration
$auto_update = '&time='.time();
$main_image_width = '&w=700&h=120';

$entity_id = $part['SectionFeatured']['id'];

$generic_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $part['SectionFeatured']['content_path'] ."/". Configure::read("generic_image") . $main_image_width;
$specific_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $part['SectionFeatured']['img_url'] . $main_image_width;
$specific_path_css = Configure::read("base_url") . Configure::read("lib"). DS . Configure::read("timthumb") . Configure::read("resources_path") . DS . $part['SectionFeatured']['img_url'] . $main_image_width;

$model_name = 'SectionFeatured';

$header = array(
    "title" => array(
        "name" => "Editar parte destacada",
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
        "Editar parte destacada" => array()
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
        <?=
            $this->Form->input(
                'line1',
                array(
                    'label' => 'Primera línea',
                    'rows' => '1',
                    'after' => '<div class="clearboth"></div>'
                )
            );
        ?>
        <?=
            $this->Form->input(
                'line2',
                array(
                    'label' => 'Segunda línea',
                    'rows' => '1',
                    'after' => '<div class="clearboth"></div>'
                )
            );
        ?>
        <div class="clearboth"></div>
    </div><!-- .form-block -->

    <section class="media form-block">
        <h3>Imagen de fondo del destacado</h3>
        <section class="block-media block-main">
<?php
            if($part['SectionFeatured']['img_url'] != ""){
                echo $this->Html->image($specific_path.$auto_update, array('alt' => 'Sección', 'class' => 'main-image'));
            }

            echo $this->Form->input(
                'main_img',
                array(
                    'label' => 'Subir nueva imagen',
                    'type' => 'file',
                    'div' => 'input file color10'
                )
            );
?>
            <div class="filename">
                <p class="title">Ningún archivo seleccionado</p>
            </div><!-- .filename -->
        </section><!-- .block-media -->
    </section><!-- .media.form-block -->

    <?= $this->Form->submit(
        'Guardar cambios',
        array(
            'class' => 'button color10'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
