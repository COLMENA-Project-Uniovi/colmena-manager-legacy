<?php
// Aux configuration
$model_name = 'ColmenaGroup';
$controller_name = 'colmena_groups';
$title_header = "Grupos";
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
        "Grupos" => array(
            'controller' => 'colmena_groups',
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
            'Crear grupo',
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
                    <?= $this->Paginator->sort($model_name.'.group_name','Nombre', array('class' => 'sortable'));?>
            	</th>

                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.day_of_week','Día de la semana', array('class' => 'sortable'));?>
                </th>

                 <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.start_hour','Hora de inicio', array('class' => 'sortable'));?>
                </th>

                 <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.end_hour','Hora de finalización', array('class' => 'sortable'));?>
                </th>
                 <th class="show-responsive actions">
                    Usuarios
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
                    <?= $entity[$model_name]['group_name']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['day_of_week']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['start_hour']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['end_hour']; ?>
                </td>
                 <td class="show-responsive">
                     <?= $this->Html->link(
                        'Ver usuarios del grupo',
                        array(
                            'controller' => 'ColmenaSubjectUsers',
                            'action' => 'index',
                            $this->params['pass'][0],
                            $entity[$model_name]['id']
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?>
                </td>
               
                <td class="show-responsive">
                    <?= $this->Html->link(
                        'Editar',
                        array(
                            'action' => 'edit',
                            $entity[$model_name]['id']
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?>
                    <?= $this->Form->postLink(
                        'Borrar',
                        array(
                            'action' => 'delete',
                            $entity[$model_name]['id']
                        ),
                        array(
                            'confirm' => '¿Está seguro de que desea eliminar el grupo?',
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

