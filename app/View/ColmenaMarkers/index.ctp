<?php
// Aux configuration
$model_name = 'ColmenaMarker';
$controller_name = 'colmena_markers';
$title_header = "Errores sin clasificar";
$model_color = "color1";

$header = array(
    "title" => array(
        "name" => $title_header,
        "color" => $model_color
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        $title_header => array(
            'controller' => $controller_name,
            'action' => 'index'
        )
    )
);

$search_form = array(
    'model' => $model_name,
    'color' => $model_color
);
?>

<?= $this->element(
    "header",
    $header
); ?>


<section class="top">
   <section class="actions">
        <?= $this->Html->link('Backup de la tabla', array('action' => 'backup_database', 'colmena_marker_temp'), array('class' => 'button color1')); ?>
    </section><!-- .actions -->
</section><!-- .top -->

<?php
    if ($keyword != "") :
?>
    <p class="search-results">Resultados de la búsqueda: <?= $keyword; ?></p>
<?php
    endif;
?>

<section class="results">
<?php
    if (!empty($entities)) :
?>
    <p class="num-results"><?= $this->Paginator->counter('<span>Mostrando {:start}-{:end} de {:count} elementos</span>'); ?></p>
    <table class="<?= $model_color; ?>">
        <thead>
            <tr>
            	<th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaMarker.user_id','Nombre', array('class' => 'sortable'));?>
            	</th>

                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaMarker.error_id','Error id', array('class' => 'sortable'));?>
                </th>

                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaMarker.class_name','Clase', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaMarker.project_name','Proyecto', array('class' => 'sortable'));?>
                </th>

                <th class="show-responsive visible">
                    <?= $this->Paginator->sort('ColmenaMarker.timestamp','Timestamp', array('class' => 'sortable'));?>                    
                </th>
                <th class="show-responsive actions short">
                    Operaciones
                </th>
            </tr>
        </thead>
        <tbody>
    <?php
        $pair = "pair";

        foreach ($entities as $entity):
            

            if($pair == "pair"):
                $pair = "odd";
            else:
                $pair = "pair";
            endif;
    ?>

            <tr class="<?= $pair; ?>">                
                <td class="show-responsive">
                    <?= $entity[$model_name]['user_id']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['error_id']; ?>
                </td>


                <td class="show-responsive">
                    <?= $entity[$model_name]['class_name']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['project_name']; ?>
                </td>
                <td class="show-responsive">                
                    <?= $entity[$model_name]['timestamp'] ?>
                </td>

                <td class="show-responsive">
                    <?= $this->Html->link(
                        'Editar',
                        array(
                            'action' => 'edit'
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?>
                   
                </td>

            </tr>
    <?php
        endforeach;
    ?>
        </tbody>
    </table>
    <?= $this->element('paginator'); ?>
<?php
   else :
?>
    <p class="no-results">No existen resultados para la búsqueda realizada</p>
<?php
    endif;
?>
</section><!-- .results -->

