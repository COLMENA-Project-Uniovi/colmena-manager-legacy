<?php
// Aux configuration
$model_name = 'ColmenaSubject';
$controller_name = 'colmena_subjects';
$title_header = "Asignaturas";
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
        <?= $this->Html->link(
            'Crear asignatura',
            array(
                'action' => 'add'
            ),
            array(
                'class' => 'button ' . $model_color
            )
        ); ?>
    </section><!-- .actions -->
   
    <?= $this->element(
        "search-form",
        $search_form
    ); ?>
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
                    <?= $this->Paginator->sort('ColmenaSubject.subject_name_es','Nombre', array('class' => 'sortable'));?>
            	</th>

                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaSubject.academic_year','Año', array('class' => 'sortable'));?>
                </th>
                <!--<th class="show-responsive">
                    Usuarios
                </th>-->
                <th class="show-responsive">
                    Calendario de sesiones
                </th>
                <th class="show-responsive">
                    Gestión de grupos y sesiones
                </th>
                <th class="show-responsive visible">
                    Visible
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

            <tr style="background-color: #<?= $entity[$model_name]['color']; ?>">                
                <td class="show-responsive">
                    <?= $entity[$model_name]['subject_name_es']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['academic_year']; ?>
                </td>
                <!--<td class="show-responsive">
                     <?= $this->Html->link(
                        'Ver usuarios',
                        array(
                            'action' => 'users',
                            $entity[$model_name]['id']
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?>
                </td>-->
                  <td class="show-responsive">
                     <?= $this->Html->link(
                        'Ver calendario',
                        array(
                            'controller' => 'ColmenaSessionGroups',
                            'action' => 'index',
                            $entity[$model_name]['id']
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?>
                </td>
                <td class="show-responsive">
                     <?= $this->Html->link(
                        'Ver sesiones',
                        array(
                            'controller' => 'colmena_sessions',
                            'action' => 'index',
                            $entity[$model_name]['id']
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?>
                     <?= $this->Html->link(
                        'Ver grupos',
                        array(
                            'controller' => 'colmena_groups',
                            'action' => 'index',
                            $entity[$model_name]['id']
                        ),
                        array(
                            'class' => 'button ' . $model_color . ' fullwidth'
                        )
                    );?>
                </td>
                <td class="show-responsive visible">
                <?php
                    $checked = "";
                    if($entity[$model_name]['visible']):
                        $checked = "checked";
                    endif;
                ?>
                    <p class="check <?= $checked; ?>" id="colmena_subjects-<?= $entity[$model_name]['id'] ?>">
                        <?= $this->Html->image('menus/check.png'); ?>
                    </p>
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
                            'confirm' => '¿Está seguro de que desea eliminar la asignatura?',
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

