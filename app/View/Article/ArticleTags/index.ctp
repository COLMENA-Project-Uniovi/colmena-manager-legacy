<?php
// Aux configuration

$header = array(
    "title" => array(
        "name" => "Etiquetas de noticias",
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
        )
    )
);

$search_form = array(
    'model' => 'ArticleTag',
    'color' => 'color61'
);
?>

<?= $this->element(
    "header",
    $header
); ?>

<section class="top">

    <div class="actions">
        <?= $this->Html->link(
            'Nueva etiqueta',
            array(
                'controller' => 'article_tags',
                'action' => 'add'
            ),
            array(
                'class' => 'button color61'
            )
        ); ?>
    </div><!-- .actions -->

    <?= $this->element(
        "search-form",
        $search_form
    ); ?>
</section><!-- .top-->

<?php
    if ($keyword != "") {
?>
    <p class="search-results">Resultados de la búsqueda: <?= $keyword; ?></p>
<?php
    }
?>

<section class="results">
<?php
    if(!empty($tags)){
?>
    <p class="num-results"><?= $this->Paginator->counter('<span>Mostrando {:start}-{:end} de {:count} elementos</span>'); ?></p>
    <table class="color61">
        <thead>
            <tr>
                <th class="show-responsive">
                    <?= $this->Paginator->sort(
                        'ArticleTag.tag',
                        'Etiqueta',
                        array(
                            'class' => 'sortable'
                        )
                    );?>
                </th>
                <th class="show-responsive actions short">
                    Operaciones
                </th>
            </tr>
        </thead>
        <tbody>
<?php
        $pair = "pair";

        foreach ($tags as $tag):
            if($pair == "pair"):
                $pair = "odd";
            else:
                $pair = "pair";
            endif;
?>
            <tr class="<?= $pair; ?>">

                <td class="show-responsive element">
                    <?= $tag['ArticleTag']['name']; ?>
                </td>

                <td class="show-responsive">
                <?=
                    $this->Html->link(
                        'Editar',
                        array(
                            'action' => 'edit',
                            $tag['ArticleTag']['id']
                        ),
                        array(
                            'class' => 'button color61 fullwidth'
                        )
                    );
                ?>
                <?=
                    $this->Form->postLink(
                        'Borrar',
                        array(
                            'action' => 'delete',
                            $tag['ArticleTag']['id']
                        ),
                        array(
                            'confirm' => '¿Está seguro de que desea eliminar la categoría?',
                            'class' => 'button color61 fullwidth'
                        )
                    );
                ?>
                </td>
            </tr>
<?php
        endforeach;
?>
        </tbody>
    </table>
    <?= $this->element('paginator'); ?>
<?php
    }else{
?>
    <p class="no-results">No existen resultados para la búsqueda realizada</p>
<?php
    }
?>
</section><!-- .results -->
