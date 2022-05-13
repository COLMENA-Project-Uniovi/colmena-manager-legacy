<?php
// Aux configuration
$model = 'Section';
$title = $entity[$model]['name'];
$color = "color" . $entity[$model]['id'];
$header = array(
    "title" => array(
        "name" => $title,
        "color" => $color
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        $title => array(
            'controller' => 'sections',
            'action' => 'view',
            $entity[$model]['id']
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
    <?php
        $i = 1;
        foreach ($children as $child) :
            $child = $child['Section'];
    ?>
        <section class="bloque normal <?= $color ?> <?= $color . $i++ ?>">
            <?= $this->Html->link(
                $child['name'],
                array(
                    'controller' => 'sections',
                    'action' => 'edit',
                    $child['id']

                )
            ); ?>
        </section><!-- .bloque -->
    <?php
        endforeach;
    ?>
        <div class="clearboth"></div>
    </section><!-- .content-bloques-menu -->

    <div class="clearboth"></div>
</section><!-- .content-bloques -->
