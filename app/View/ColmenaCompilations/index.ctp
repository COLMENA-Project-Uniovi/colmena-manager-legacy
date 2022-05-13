<?php
// Aux configuration
$model_name = 'ColmenaCompilation';
$controller_name = 'colmena_compilations';
$title_header = "Compilaciones sin clasificar";
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
        <?= $this->Html->link('Backup de la tabla', array('action' => 'backup_database', 'colmena_compilations'), array('class' => 'button color1')); ?>
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
                    <?= $this->Paginator->sort($model_name.'.user_id','Usuario', array('class' => 'sortable'));?>
                </th>

                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.num_markers','Número de errores', array('class' => 'sortable'));?>
                </th>

                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.class_name','Clase', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.project_name','Proyecto', array('class' => 'sortable'));?>
                </th>

                <th class="show-responsive visible">
                    <?= $this->Paginator->sort($model_name.'.timestamp','Timestamp', array('class' => 'sortable'));?>                    
                </th>
                <th class="show-responsive visible">
                    <p> Asignatura asignada </p>                   
                </th>
                <th class="show-responsive visible">
                    <p> Grupo asignado </p>                   
                </th>
                <th class="show-responsive visible">
                    <p> Sesión asignada </p>                   
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
                    <?= $entity[$model_name]['num_markers']; ?>
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
                    TO DO
                </td>
                <td class="show-responsive">                
                    TO DO
                </td>
                <td class="show-responsive">                
                    TO DO
                </td>

                <td class="show-responsive">
                    <?= $this->Html->link(
                        'Asignar error manualmente',
                        array(
                            'action' => 'edit',
                            $entity[$model_name]['id']
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?>
                    <?= $this->Html->link(
                        'Asignar a predeterminada',
                        array(
                            'action' => 'edit',
                            $entity[$model_name]['id']
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

