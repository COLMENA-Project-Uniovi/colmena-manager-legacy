<?php
// Aux configuration
$model_name = 'ColmenaSession';
$controller_name = 'colmena_sessions';
$title_header = "Sesiones";
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
            'Crear sesión',
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
                    <?= $this->Paginator->sort($model_name.'.session_name_es','Nombre', array('class' => 'sortable'));?>
            	</th>

                <th class="show-responsive">
                    <?= $this->Paginator->sort($model_name.'.week','Semana', array('class' => 'sortable'));?>
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
                    <?= $entity[$model_name]['session_name_es']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['week']; ?>
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
                            'confirm' => '¿Está seguro de que desea eliminar la sesión?',
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

