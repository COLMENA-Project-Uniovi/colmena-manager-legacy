<?php
// Aux configuration
$model_name = "Section";

$header = array(
    "title" => array(
        "name" => "Añadir nueva sección",
        "color" => "color7"
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Posicionamiento SEO" => array(
            'controller' => 'sections',
            'action' => 'index'
        ),
        "Añadir nueva sección" => array()
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
);  ?>
    <div class="form-block">
        <h3>Datos generales de la sección</h3>
        <?= $this->Form->input('parent_id',
            array(
                'label' => "Selecciona la sección padre",
                'options' => $sections,
                'empty' => array("0" => "-- Sin sección padre --")
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
            <div class="input file color7">
                <label class="imagen-add" for="gallery_img[]">Subir imágenes de fondo de la sección</label>
                <input type="file" name="data[<?= $model_name; ?>][gallery_img][]" multiple="multiple" id="gallery_img[]">
            </div>
            <div class="clearboth"></div>
            <div class="filename">
                <p class="title">Ningún archivo seleccionado</p>
            </div><!-- .filename -->

            <div class="clearboth"></div>
        </section><!-- block-media -->
    </div><!-- .form-block.media -->

    <div class="clearboth"></div>
    <?= $this->Form->submit(
        'Añadir sección',
        array(
            'class' => 'button color7'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
