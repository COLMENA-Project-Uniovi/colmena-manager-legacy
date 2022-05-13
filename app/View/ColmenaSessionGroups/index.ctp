<?php
// Aux configuration
$model_name = 'ColmenaSessionGroup';
$controller_name = 'ColmenaSessionGroups';
$title_header = "Calendario de la asignatura "  . $subject['ColmenaSubject']['subject_name_es'];
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
        "Asignaturas" => array(
            'controller' => 'colmena_subjects',
            'action' => 'index',
        ),
        $title_header => array(
            'controller' => $controller_name,
            'action' => 'index',
            $this->params['pass'][0]
        )
    )
);
?>

<?= $this->element(
    "header",
    $header
); ?>


<section class="top">
    <section class="actions">
        <?= $this->Html->link(
            'Crear nueva clase práctica',
            array(
                'action' => 'add',
                $this->params['pass'][0]
            ),
            array(
                'class' => 'button ' . $model_color
            )
        ); ?>
    </section><!-- .actions -->
</section><!-- .top -->


<section class="results">
<?php
    if (!empty($entities)) :
?>
    <p class="num-results"><?= $this->Paginator->counter('<span>Mostrando {:start}-{:end} de {:count} elementos</span>'); ?></p>
    <table class="<?= $model_color; ?>">
        <thead>
            <tr>
                 <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.session'.'.session_name_es','Sesión', array('class' => 'sortable'));?>
                </th>
            	<th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.group'.'.group_name','Grupo', array('class' => 'sortable'));?>
            	</th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.session_day','Día de la clase', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.start_hour','Hora de inicio', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.end_hour','Hora de fin', array('class' => 'sortable'));?>
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.location','Laboratorio', array('class' => 'sortable'));?>
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
                    <?= $entity['ColmenaSession']['session_name_es']; ?>
                </td>
                <td class="show-responsive">
                    <?= $entity['ColmenaGroup']['group_name']; ?>
                </td>
                <td class="show-responsive">
                    <?= $entity[$model_name]['session_day']; ?>
                </td>
                <td class="show-responsive">
                    <?= $entity[$model_name]['start_hour']; ?>
                </td>
                <td class="show-responsive">
                    <?= $entity[$model_name]['end_hour']; ?>
                </td>
                <td class="show-responsive">
                    <?= $entity[$model_name]['location']; ?>
                </td>

                <td class="show-responsive">
                     <?= $this->Html->link(
                        'Editar',
                        array(
                            'action' => 'edit',
                            $entity[$model_name]['id'],
                            $entity[$model_name]['subject_id']
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?> 
                    <?= $this->Form->postLink(
                        'Borrar',
                        array(
                            'action' => 'delete',
                            $entity[$model_name]['id'],
                            $entity[$model_name]['subject_id']
                        ),
                        array(
                            'confirm' => '¿Está seguro de que desea eliminar la clase?',
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    ); ?>
                    
                    
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

