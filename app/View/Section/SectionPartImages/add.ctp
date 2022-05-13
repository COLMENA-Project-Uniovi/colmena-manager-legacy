<?php
// Aux configuration
$model_name = "SectionPartImage";

$header = array(
    "title" => array(
        "name" => "Añadir texto+imagen",
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
        "Añadir texto+imagen" => array()
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
    </div><!-- .form-block -->

    <div class="media form-block">
        <h3>Imagen adjunta de la parte (opcional)</h3>
        <section class="block-media block-main">
            <?= $this->Form->input(
                'main_img' ,
                array(
                    'label' => 'Imagen adjunta de la parte',
                    'type' => 'file',
                    'div' => 'input file color10'
                )
            ); ?>
            <div class="filename">
                <p class="title">Ningún archivo seleccionado</p>
            </div><!-- .filename -->
            <div class="clearboth"></div>
        </section><!-- .block-media -->
    </div><!-- .media -->
    <div class="form-block">
        <h3>Características de la imagen</h3>
        <?= $this->Form->input(
                'image_position',
                array(
                    'label' => 'Posición',
                    'options' => array('center' => 'Centrado', 'left' => 'Izquierda', 'right' => 'Derecha'),
                    'after' => '<div class="clearboth"></div>',
                    'class' => 'select-wrap fullwidth'
                )
            ); ?>
        <?= $this->Form->input(
                'image_width',
                array(
                    'label' => 'Ancho (px)',
                    'default' => '200',
                    'after' => '<div class="clearboth"></div>'
                )
            ); ?>
        <?= $this->Form->input(
                'image_height',
                array(
                    'label' => 'Alto (px)',
                    'default' => '200',
                    'after' => '<div class="clearboth"></div>'
                )
            ); ?>
        <?= $this->Form->input(
                'image_adjust',
                array(
                    'label' => 'Marca esta opción si quieres ajustar la imagen a las medidas indicadas.'
                )
            ); ?>
    </div><!-- .form-block -->

    <?= $this->Form->submit(
        'Añadir parte',
        array(
            'class' => 'button color10'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
