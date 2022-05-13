<?php
// Aux configuration
$header = array(
    "title" => array(
        "name" => "Usuarios del grupo",
        "color" => "color2"
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Asignaturas" => array(
            'controller' => 'colmena_subjects',
            'action' => 'index',
        ),
        "Grupos" => array(
            'controller' => 'colmena_groups',
            'action' => 'index',
            $this->params['pass'][0]
        ),
        'Usuarios del grupo' => array()
    )
);

$search_form = array(
    'model' => 'ColmenaSubjectUser',
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
            'Añadir usuario al grupo',
            array(
                'action' => 'add',
                 $this->params['pass'][0],
                  $this->params['pass'][1]
            ),
            array(
                'class' => 'button ' . 'color2'
            )
        ); ?>
    </section><!-- .actions -->
</section><!-- .top -->


<section class="results">
<?php if(!empty($entities)){ ?>
    <p class="num-results"><?= $this->Paginator->counter('<span>Mostrando {:start}-{:end} de {:count} elementos</span>'); ?></p>
    <table class="color2">
       <thead>
            <tr>
                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaSubjectUser'.'.user'.'id','Id', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaSubjectUser'.'.user'.'name','Nombre', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive actions short">Apellido</th>
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
            <tr>
                <td class="show-responsive">
                    <?= $entity['ColmenaUser']['id'] ?>
                </td>

                <td class="show-responsive">
                    <?= $entity['ColmenaUser']['name']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity['ColmenaUser']['surname']; ?>
                </td>
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
