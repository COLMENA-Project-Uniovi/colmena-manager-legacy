<?php
// Aux configuration
$model_name = 'ArticleTag';

$header = array(
    "title" => array(
        "name" => "Añadir nueva etiqueta",
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
        "Añadir nueva etiqueta" => array()
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
        <?= $this->Form->input(
            'name',
            array(
                'label' => 'Etiqueta',
                'type' => 'text'
            )
        ); ?>
    </div><!-- .form-block -->

    <?= $this->Form->submit(
        'Añadir etiqueta',
        array(
            'class' => 'button color61'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
