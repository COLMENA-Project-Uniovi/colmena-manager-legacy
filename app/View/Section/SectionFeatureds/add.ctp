<?php
// Aux configuration
$model_name = "SectionFeatured";

$header = array(
    "title" => array(
        "name" => "Añadir parte destacada",
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
        "Añadir parte destacada" => array()
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
    </div><!-- .form-block -->

    <section class="media form-block">
        <h3>Imagen de fondo del destacado</h3>
        <section class="block-media block-main">
            <?= $this->Form->input(
                'main_img' ,
                array(
                    'label' => 'Imagen de fondo del destacado',
                    'type' => 'file',
                    'div' => 'input file color10'
                )
            ); ?>
            <div class="filename">
                <p class="title">Ningún archivo seleccionado</p>
            </div><!-- .filename -->
            <div class="clearboth"></div>
        </section><!-- .block-media -->
    </section><!-- .media.form-block -->

    <?= $this->Form->submit(
        'Añadir parte',
        array(
            'class' => 'button color10'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
