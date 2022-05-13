<?php
// Aux configuration
$header = array(
    "title" => array(
        "name" => "Posicionamiento SEO",
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
        )
    )
);

$search_form = array(
    'model' => 'Section',
    'color' => 'color7'
);
?>

<?= $this->element(
    "header",
    $header
); ?>

<section class="top">
    <section class="actions">
        <!--<?= $this->Html->link('Añadir sección', array('controller' => 'sections', 'action' => 'add'), array('class' => 'button color7')); ?>-->
    </section><!-- .actions -->

    <?= $this->element(
        "search-form",
        $search_form
    ); ?>
</section><!-- .top -->

<?php
    if($keyword != ""){
?>
        <p class="results-search">Resultados de la búsqueda: <?= $keyword; ?></p>
<?php
    }
?>

<section class="results">
<?php
    if(empty($sections)):
        if(!$keyword):
?>
    <p class="no-results">Aún no se ha añadido ninguna sección</p>
<?php
        endif;
    else:
?>
    <p class="num-results"><span>Mostrando <?= count($sections); ?> elementos</span></p>
    <table class="tree color7">
        <thead>
            <tr>
                <th class="show-responsive">Nombre de la sección</th>
                <th class="show-responsive actions short">Operaciones</th>
            </tr>
        </thead>
        <tbody>

<?php
        $pair = "pair";

        foreach ($sections as $section):

            $padding = 0;

            if(!empty($section['Section']['tree_path'])):
                $parts = split(" » ", $section['Section']['tree_path']);
                $padding = 20*count($parts);
            endif;

            if($pair == "pair"):
                $pair = "odd";
            else:
                $pair = "pair";
            endif;

            $edit_action = array(
                'action' => 'edit',
                $section['Section']['id']
            );

            if($section['Section']['SeoModel']['folder'] == 'home'){
                $edit_action = array(
                    'controller' => 'home_pages',
                    'action' => 'edit',
                    1
                );
            }
?>

            <tr class="<?= $pair; ?>">

                <td class="show-responsive element" style="padding-left: <?= $padding; ?>px;">
                    <?= $section['Section']['name']; ?>
                </td>

                <td class="show-responsive actions">
                    <?= $this->Html->link(
                        'Editar',
                        $edit_action,
                        array(
                            'class' => 'button color7 fullwidth'
                        )
                    );?>
                    <!--<?= $this->Form->postLink(
                        'Borrar',
                        array(
                            'action' => 'delete',
                            $section['Section']['id']
                        ),
                        array(
                            'confirm' => '¿Está seguro de que desea eliminar la sección?',
                            'class' => 'button color7 fullwidth'
                        )
                    ); ?>-->
                </td>
            </tr>
<?php
        endforeach;
?>
        </tbody>
    </table>
<?php
    endif;
?>
</section><!-- .results -->
