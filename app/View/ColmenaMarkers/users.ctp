<?php
// Aux configuration
$model_name = 'ColmenaUser';
$controller_name = 'colmena_subjects';
$title_header = 'Usuarios de ' . $subject['ColmenaSubject']['subject_name_es'];
$subject_id = $subject['ColmenaSubject']['id'];
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
            'controller' => $controller_name,
            'action' => 'index'
        ),
        $title_header => array()
    )
);

?>

<?= $this->element(
    "header",
    $header
); ?>


<section class="top">
    <section class="actions">
        <?= $this->Html->link('Enviar email con contraseña',
            array(
                'controller' => $controller_name,
                'action' => 'send_notification',
                $subject_id

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
                    <?= $this->Paginator->sort('ColmenaUser.id','Id', array('class' => 'sortable'));?>
            	</th>

                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaUser.name','Nombre', array('class' => 'sortable'));?>
                </th>

                <th class="show-responsive">
                    Apellido
                </th>
                <th class="show-responsive">
                    <?= $this->Paginator->sort('ColmenaUser.role','Rol', array('class' => 'sortable'));?>
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
                    <?= $entity[$model_name]['id']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['name']; ?>
                </td>
                <td class="show-responsive">
                    <?= $entity[$model_name]['surname']; ?>
                </td>

                <td class="show-responsive">
                    <?= $entity[$model_name]['role']; ?>
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

