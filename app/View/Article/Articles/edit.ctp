<?php
// Aux configuration
$model_name = 'Article';
$controller = 'articles';
$entity_id = $entity[$model_name]['id'];
$auto_update = '&time='.time();
$main_image_width = '&w=200&h=150&zc=2';
$extra_images_width = '&w=100&h=100';
$generic_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $entity[$model_name]['content_path'] .DS. 'generic'.DS.'principal.jpg' . $main_image_width;
$specific_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $entity[$model_name]['img_url'] . $main_image_width;

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
        "Editar noticia" => array()
    )
);

?>

<?= $this->element(
    "header",
    $header
); ?>

<?php
    if(!empty($entity['ArticleVersion'])):
        $nversions = 0;
        foreach ($entity['ArticleVersion'] as $key => $value) :
            if($value['modified'] > $entity[$model_name]['modified'])
                $nversions++;
        endforeach;
        if ($nversions > 0):
?>
            <div class="version-available">
                <p class="accordion-title closed">Hay <span class="big"><?= $nversions ?></span> <?= $nversions > 1 ? 'versiones' : 'versión' ?> del artículo sin guardar.</p>
                <ul class="accordion-content">
            <?php foreach ($entity['ArticleVersion'] as $key => $value) :
                    $class = '';
                    if($value['modified'] > $entity[$model_name]['modified'])
                        $class = ' class="last-version"';
            ?>
                <li<?= $class ?>>Versión de las <?= CakeTime::format($value['modified'], "%H:%M:%S %d-%m-%Y") ?>
                <?= $this->Html->link("Recuperar", array('controller' => 'articles', 'action' => 'version_recover', $entity_id, $value['id'])); ?>
                <?= $this->Html->link("Descartar", array('controller' => 'article_versions', 'action' => 'delete', $value['id'])); ?>
                </li>
            <?php endforeach; ?>
                </ul>
            </div>
<?php
        endif;
    endif;
?>

<?php
    if(count(Configure::read('Config.locales.available')) > 1){
?>
<section class="languages">
    <div class="content-tabs articles-color">
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

        <section class="description">
            <?= $entity ['default'][$model_name]['content'];?>
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
        'class' => 'admin-form autosave',
        'type' => 'file',
        'data-when-autosave' => $controller . '/edit/' . $entity_id . '/' . $entity[$model_name]['locale']
    )
); ?>
    <div class="form-block">
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
                'rows' => '20',
                'label' => 'Contenido',
                'class' => 'ckeditor'
            )
        ); ?>
        <?= $this->Form->input(
            'source',
            array(
                'label' => 'Fuente',
                'rows' => '1'
            )
        ); ?>

        <?= $this->Form->input(
            'published',
            array(
                'label' => 'Fecha de publicación',
                'class' => 'datepicker',
                'value' => CakeTime::format($entity[$model_name]['published'], "%d-%m-%Y"),
                'type' => 'text',
                'after' => '<div class="input-note">Introduce la fecha del tipo "DD-MM-AAAA" o déjala en blanco.</div>'
            )
        ); ?>

        <?= $this->Form->input(
            'hour',
            array(
                'label' => 'Hora de publicación',
                'type' => 'text',
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
                'value' => implode(",", $entity['Tag']),
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
        <section class="block-media block-main">
            <h4>Imagen principal</h4>
        <?php
            if($entity[$model_name]['img_url'] == ""){
                //echo $this->Html->image($generic_path, array('alt' => 'Imagen principal', 'class' => 'main-image'));
            }else{
                echo $this->Html->image($specific_path.$auto_update, array('alt' => 'Imagen principal', 'class' => 'main-image'));
            }

            echo $this->Form->input(
                'main_img' ,
                array(
                    'label' => 'Subir nueva imagen',
                    'div' => 'input file color61',
                    'type' => 'file'
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
                <p class="delete articles-color">
                <?=
                    $this->Html->link(
                        "Eliminar",
                        array(
                            'confirm' => '¿Está seguro de que desea eliminar este archivo?',
                            'controller' => 'articles',
                            'action' => 'delete_resource',
                            $entity_id,
                            "gallery",
                            basename($image)
                        ),
                        array(),
                        '¿Está seguro de que desea eliminar este archivo?'
                    ); ?>
                </p>
            </section>
        <?php
            endforeach;
        ?>
            <div class="clearboth"></div>
            <div class="input file color61">
                <label class="imagen-add" for="extra_img[]">Subir más imágenes</label>
                <input type="file" name="data[<?= $model_name ?>][gallery_img][]" multiple="multiple" id="extra_img[]">
            </div>

            <div class="filename">
                <p class="title">Ningún archivo seleccionado</p>
            </div>
        </section><!-- .block-media -->
    </div><!-- .form-block -->

    <?php
    if ($entity[$model_name]['draft']) {
        echo $this->Form->button(
            'Publicar',
            array(
                'type' => 'submit',
                'name' => 'data['.$model_name .'][draft]',
                'value' => false,
                'class' => 'button main color61'
            )
        );
        echo $this->Form->button(
            'Guardar como borrador',
            array(
                'type' => 'submit',
                'name' => 'data['.$model_name .'][draft]',
                'value' => true,
                'class' => 'button color61'
            )
        );
    } else {
        echo $this->Form->button(
            'Actualizar',
            array(
                'type' => 'submit',
                'name' => 'data['.$model_name .'][draft]',
                'value' => false,
                'class' => 'button main color61'
            )
        );
        echo $this->Html->link(
            'Descartar',
            array(
                'controller' => 'articles',
                'action' => 'index'
            ),
            array(
                'class' => 'button submit color61'
            )
        );
    }
    ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
