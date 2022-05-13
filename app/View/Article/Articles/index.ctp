<?php
// Aux configuration

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
        )
    )
);

$search_form = array(
    'model' => 'Article',
    'color' => 'color61'
);
?>

<?= $this->element(
    "header",
    $header
); ?>

<section class="top">
    <section class="actions">
        <?= $this->Html->link(
            'Nueva noticia',
            array(
                'controller' => 'articles',
                'action' => 'add'
            ),
            array(
                'class' => 'button color61'
            )
        ); ?>
        <?= $this->Html->link(
            'Administrar etiquetas',
            array(
                'controller' => 'article_tags',
                'action' => 'index'
            ),
            array(
                'class' => 'button color61'
            )
        ); ?>
    </section><!-- .actions -->

    <?= $this->element(
        "search-form",
        $search_form
    ); ?>
</section><!-- .top-->

<section class="results">
<?php
    if (!empty($articles)) {
?>
    <p class="num-results">
        <?= $this->Paginator->counter('
            <span>Mostrando {:start}-{:end} de {:count} elementos</span>
            <span>' . $this->Html->link($npublished . ' publicadas', array('action' => 'index', 0)) . '</span>
            <span>' . $this->Html->link($ndraft . ' borradores', array('action' => 'index', 1)) . '</span>');
        ?>
    </p>

    <table class="color61">
        <thead>
            <tr>
                <th class="show-responsive date">
                    <?= $this->Paginator->sort(
                        'Article.published',
                        'Publicada',
                        array(
                            'class' => 'sortable'
                        )
                    );?>
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort(
                        'Article.title',
                        'Título',
                        array(
                            'class' => 'sortable'
                        )
                    ); ?>
                </th>
                <th class="show-responsive visible">
                    Visible
                </th>
                <th class="show-responsive featured">
                    Destacada
                </th>
                <th class="show-responsive actions">
                    Operaciones
                </th>
            </tr>
        </thead>
        <tbody>
    <?php
        $pair = "pair";

        foreach ($articles as $article):
            if($pair == "pair"):
                $pair = "odd";
            else:
                $pair = "pair";
            endif;
    ?>

            <tr class="<?= $pair; ?>">
                <td class="show-responsive date">
                    <?= CakeTime::format($article['Article']['published'], "%d-%m-%Y"); ?>
                </td>

                <td class="show-responsive">
                    <?= $article['Article']['title']; ?>
                    <?= $article['Article']['draft'] ? ' <span class="draft">( borrador )</span>' : '' ?>
                </td>

                <td class="visible">
                <?php
                    $checked = "";
                    if($article['Article']['visible']){
                        $checked = "checked";
                    }
                ?>
                    <p class="check <?= $checked; ?>" id="articles-<?= $article['Article']['id'] ?>">
                        <?= $this->Html->image('menus/check.png'); ?>
                    </p>
                </td>

                <td class="featured">
                <?php
                    $checked = "";
                    if($article['Article']['featured']){
                        $checked = "checked";
                    }
                ?>
                    <p class="check <?= $checked; ?>" id="articles-<?= $article['Article']['id'] ?>">
                        <?= $this->Html->image('menus/check.png'); ?>
                    </p>
                </td>

                <td class="actions show-responsive">
                    <?= $this->Html->link(
                        'Editar',
                        array(
                            'action' => 'edit',
                            $article['Article']['id']
                        ),
                        array(
                            'class' => 'button color61'
                        )
                    );?>
                    <?= $this->Form->postLink(
                        'Borrar',
                        array(
                            'action' => 'delete',
                            $article['Article']['id']
                        ),
                        array(
                            'confirm' => '¿Está seguro de que desea eliminar la noticia?',
                            'class' => 'button color61'
                        )
                    ); ?>
                    <a href="../noticias/preview/<?= $article['Article']['SeoModel']['folder']; ?>" target="_blank" class="button fullwidth color61">Previsualizar</a>
                </td>

            </tr>
    <?php
        endforeach;
    ?>
        </tbody>
    </table>
    <?= $this->element('paginator'); ?>
<?php
    } else {
?>
    <p class="no-results">No existen resultados para la búsqueda realizada</p>
<?php
    }
?>
</section><!-- .results -->
