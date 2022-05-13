<?php
// Aux configuration
$auto_update = '&time='.time();
$main_image_width = '&w=' . $part['SectionPartImage']['image_width'] . '&h='. $part['SectionPartImage']['image_height'];
if($part['SectionPartImage']['image_adjust']){
    $main_image_width .= '&zc=2';
}

$entity_id = $part['SectionPartImage']['id'];

$generic_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $part['SectionPartImage']['content_path'] ."/". Configure::read("generic_image") . $main_image_width;
$specific_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $part['SectionPartImage']['img_url'] . $main_image_width;
$specific_path_css = Configure::read("base_url") . Configure::read("lib"). DS . Configure::read("timthumb") . Configure::read("resources_path") . DS . $part['SectionPartImage']['img_url'] . $main_image_width;

$model_name = 'SectionPartImage';

$header = array(
    "title" => array(
        "name" => "Editar parte de texto+imagen",
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
        "Editar parte de texto+imagen" => array()
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
                'content',
                array(
                    'class' => array('fullwidth', 'ckeditor'),
                    'label' => 'Contenido',
                    'rows' => '20',
                    'after' => '<div class="clearboth"></div>'
                )
            );
        ?>
        <?=
            $this->Form->input(
                'content_columns',
                array(
                    'label' => 'Columnas',
                    'type' => 'number',
                    'min' => '0',
                    'default' => '0',
                    'after' => '<div class="input-note">Especifica el número de columnas en las que quieres que se divida el texto. Déjalo a cero si sólo quieres una columna.</div>'
                )
            );
        ?>
        <div class="clearboth"></div>
    </div><!-- .form-block -->

    <div class="media form-block">
        <h3>Archivos multimedia</h3>
        <section class="block-media block-main">
            <h3>Imagen adjunta de la parte (opcional)</h3>
<?php
            if($part['SectionPartImage']['img_url'] != ""){
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
    </div><!-- .media.form-block -->

    <div class="form-block">
        <h3>Características de la imagen</h3>
        <?=
            $this->Form->input(
                'image_position',
                array(
                    'label' => 'Posición',
                    'options' => array('center' => 'Centrado', 'left' => 'Izquierda', 'right' => 'Derecha'),
                    'after' => '<div class="clearboth"></div>',
                    'class' => 'select-wrap fullwidth'
                )
            );
        ?>
        <?=
            $this->Form->input(
                'image_width',
                array(
                    'label' => 'Ancho (px)',
                    'default' => '200',
                    'after' => '<div class="clearboth"></div>'
                )
            );
        ?>
        <?=
            $this->Form->input(
                'image_height',
                array(
                    'label' => 'Alto (px)',
                    'default' => '200',
                    'after' => '<div class="clearboth"></div>'
                )
            );
        ?>
        <?=
            $this->Form->input(
                'image_adjust',
                array(
                    'label' => 'Marca esta opción si quieres ajustar la imagen a las medidas indicadas.'
                )
            );
        ?>
    </div><!-- .form-block -->

    <?= $this->Form->submit(
        'Guardar cambios',
        array(
            'class' => 'button color10'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
