<?php
// Aux configuration

$model_name = "Article";

$header = array(
    "title" => array(
        "name" => "Noticias",
        "color" => "color61"
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
         "Actualidad" => array(
            'controller' => 'pages',
            'action' => 'actualidad'
        ),
        "Noticias" => array(
            'controller' => 'articles',
            'action' => 'index'
        ),
        "Añadir nueva noticia" => array()
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
        'class' => 'admin-form autosave',
        'type' => 'file',
        'data-when-autosave' => 'articles/edit'
    )
); ?>
    <div class="form-block">
        <h3>Datos generales de la noticia</h3>
        <?= $this->Form->input(
            'title',
            array(
                'label' => 'Título',
                'rows' => '2'
            )
        ); ?>
        <?= $this->Form->input(
            'excerpt',
            array(
                'label' => 'Extracto',
                'rows' => '5',
                'after' => '<div class="input-note">Introduce un extracto resumen de la noticia de 30 palabras o menos.</div>'
            )
        ); ?>
        <?= $this->Form->input(
            'content',
            array(
                'rows' => '200',
                'label' => 'Contenido',
                'class' => 'ckeditor'
            )
        ); ?>
        <?= $this->Form->input(
            'source',
            array(
                'label' => 'Fuente',
                'type' => 'text'
            )
        ); ?>
        <?= $this->Form->input(
            'published',
            array(
                'label' => 'Fecha de publicación',
                'class' => 'datepicker',
                'value' => CakeTime::format(time(), "%d-%m-%Y"),
                'type' => 'text',
                'after' => '<div class="input-note">Introduce la fecha del tipo "DD-MM-AAAA".</div>'
            )
        ); ?>
        <?= $this->Form->input(
            'hour',
            array(
                'label' => 'Hora de publicación',
                'type' => 'text',
                'default' => CakeTime::format(time(), "%H:%M:%S"),
                'after' => '<div class="input-note">Introduce la hora a la que se publicará esta noticia del tipo "HH:MM:SS".</div>'
            )
        ); ?>
    </div><!-- .form-block -->

    <div class="form-block">
        <h3>Tags y posicionamiento SEO</h3>

        <?= $this->Form->input(
            $model_name.'.SeoModel.folder',
            array(
                'label' => 'URL amigable',
                'required' => 'required',
                'after' => '<div class="input-note">Introduce la URL amigable de esta noticia para mostrar en el navegador. El identificador debe ser único, del tipo "noticia-de-prueba" sin comillas.</div>'
            )
        ); ?>

        <?= $this->Form->input('tags',
            array(
                'type' => 'text',
                'label' => "Introduce las etiquetas de la noticia",
                'class' => 'tokens',
                'after' => '<div class="input-note">Puedes añadir más etiquetas separadas por comas.</div>'
            )
        ); ?>
        <script type="text/javascript">
            $(".tokens").select2({
                width : "100%",
                tags : [<?= '"'. implode('","', $tags).'"'; ?>],
                tokenSeparators : [","]
            });
        </script>
    </div><!-- .form-block -->

    <div class="form-block">
        <h3>Multimedia</h3>
        <div class="block-media">
            <?= $this->Form->input(
                'main_img' ,
                array(
                    'label' => 'Imagen principal de la noticia',
                    'type' => 'file',
                    'div' => 'input file color61'
                )
            ); ?>
            <div class="filename">
                <p class="title">Ningún archivo seleccionado</p>
            </div><!-- .filename -->
            <div class="clearboth"></div>
        </div><!-- .block-media -->

        <div class="block-media">
            <div class="input file color61">
                <label for="gallery_img[]">Imágenes adicionales para la noticia</label>
                <input type="file" name="data[Article][gallery_img][]" multiple="multiple" id="gallery_img[]">
            </div>
            <div class="clearboth"></div>
            <div class="filename">
                <p class="title">Ningún archivo seleccionado</p>
            </div><!-- .filename -->
        </div><!-- .block-media -->
        <div class="clearboth"></div>
    </div><!-- .form-block -->

    <div id="autosave-message"></div>

    <?= $this->Form->button(
        'Publicar',
        array(
            'type' => 'submit',
            'name' => 'data[Article][draft]',
            'value' => false,
            'class' => 'button main color61'
        )
    ); ?>
    <?= $this->Form->button(
        'Guardar como borrador',
        array(
            'type' => 'submit',
            'name' => 'data[Article][draft]',
            'value' => true,
            'class' => 'button color61'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
