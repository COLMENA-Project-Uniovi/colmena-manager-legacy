<?php
// Aux configuration

$header = array(
    "title" => array(
        "name" => "Actualidad",
        "color" => "color6"
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Actualidad" => array(
            'controller' => 'pages',
            'action' => 'actualidad'
        )
    )
);
?>

<?= $this->element(
    "header",
    $header
); ?>
<section class="content-bloques">
    <section class="content-bloques-menu">

        <section class="bloque normal color61">
            <?= $this->Html->link(
                'Noticias' ,
                array(
                    'controller' => 'articles',
                    'action' => 'index'
                )
            ); ?>
        </section><!-- .bloque -->
        <section class="bloque normal color62">
            <?= $this->Html->link(
                'Multimedia' ,
                array(
                    'controller' => 'galleries',
                    'action' => 'index'
                )
            ); ?>
        </section><!-- .bloque -->

        <div class="clearboth"></div>
    </section><!-- .content-bloques-menu -->

    <div class="clearboth"></div>
</section><!-- .content-bloques -->
