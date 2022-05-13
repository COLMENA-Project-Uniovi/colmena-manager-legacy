<?php
// Aux configuration
$header = array(
    "title" => array(
        "name" => "Usuarios",
        "color" => "color2"
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        'Usuarios' => array()
    )
);

$search_form = array(
    'model' => 'ColmenaUser',
    'color' => 'color2'
);
?>

<?= $this->element(
    "header",
    $header
); ?>

<section class="top">
    <section class="actions">
        <?= $this->Html->link(
            'Crear nuevo usuario',
            array(
                'action' => 'add'
            ),
            array(
                'class' => 'button ' . 'color2'
            )
        ); ?>
    </section><!-- .actions -->
</section><!-- .top -->


<?php
    if ($keyword != "") {
?>
    <p class="search-results">Resultados de la búsqueda: <?= $keyword; ?></p>
<?php
    }
?>

<section class="results">
<?php if(!empty($entities)){ ?>
    <p class="num-results"><?= $this->Paginator->counter('<span>Mostrando {:start}-{:end} de {:count} elementos</span>'); ?></p>
    <table class="color2">
        <thead>
            <tr>
                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaUser.id','Id', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaUser.name','Nombre', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive actions short">Apellido</th>
                <th class="show-responsive actions short">Activo</th>
            </tr>
        </thead>
        <tbody>
    <?php
        $pair = "pair";
        $i = 0;

        foreach ($entities as $entity):

            if($pair == "pair"):
                $pair = "odd";
            else:
                $pair = "pair";
            endif;
    ?>
            <tr class="<?= $pair; ?>">  
                <td class="show-responsive">
                    <?= $entity['ColmenaUser']['id'] ?>
                </td>

                <td class="show-responsive">
                    <?= $entity['ColmenaUser']['name']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity['ColmenaUser']['surname']; ?>
                </td>

                <td class="show-responsive visible">
                <?php
                    $checked = "";
                    if ($entity['ColmenaUser']['active']) {
                        $checked = "checked";
                    }
                ?>
                    <p class="check <?= $checked; ?>" id="colmena_users-<?= $entity['ColmenaUser']['id'] ?>">
                        <?= $this->Html->image('menus/check.png'); ?>
                    </p>
                </td>

                <!--<td class="actions show-responsive">
                    <?= $this->Html->link(
                        'Editar',
                        array(
                            'action' => 'edit',
                            $entity['ColmenaUser']['id']
                        ),
                        array(
                            'class' => 'button color2 fullwidth'
                        )
                    ); ?>
                </td>-->
            </tr>
    <?php
        $i++;
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
