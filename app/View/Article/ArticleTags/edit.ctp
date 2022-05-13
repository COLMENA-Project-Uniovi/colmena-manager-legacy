<?php
// Aux configuration
$model_name = 'ArticleTag';

$header = array(
    "title" => array(
        "name" => "Editar etiqueta",
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
        "Etiquetas de noticias" => array(
            'controller' => 'article_tags',
            'action' => 'index'
        ),
        "Editar etiqueta" => array()
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
    <div class="content-tabs color61">
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

<div class="clearboth"></div>

<?= $this->Form->create(
    $model_name,
    array(
        'class' => 'admin-form',
        'type' => 'file'
    )
); ?>
    <div class="form-block">
        <?= $this->Form->input(
            'name',
            array(
                'label' => 'Nombre de la etiqueta',
                'type' => 'text'
            )
        ); ?>
    </div><!-- .form-block -->

    <?= $this->Form->submit(
        'Guardar etiqueta',
        array(
            'class' => 'button color61'
        )
    ); ?>

    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
