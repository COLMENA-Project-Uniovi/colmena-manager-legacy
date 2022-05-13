<?php
// Aux configuration
$model_name = 'Contact';
$controller_name = 'ColmenaUsers';

$header = array(
    "title" => array(
        "name" => "Editar usuario Colmena",
        "color" => "color2"
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Usuarios" => array(
            'controller' => $controller_name,
            'action' => 'index'
        ),
        "Editar datos del usuario" => array()
    )
);
?>

<?= $this->element(
    "header",
    $header
); ?>

<?= $this->Form->create(
    'Contact',
    array(
        'action' => 'edit',
        'class' => 'admin-form'
    )
); ?>

   <div class="form-block">
        <h3>Datos del usuario</h3>

        <?= $this->Form->input(
            'name',
            array(
                'type' => 'text',
                'label' => 'Nombre real'
            )
        ); ?>
        <?= $this->Form->input(
            'surname',
            array(
                'label' => 'Apellido 1'
            )
        ); ?>
        <?= $this->Form->input(
            'surname2',
            array(
                'label' => 'Apellido 2'
            )
        ); ?>

        <?= $this->Form->input(
            'id',
            array(
                'type' => 'text',
                'label' => 'UO (Identificador)'
            )
        ); ?>

        <?= $this->Form->input(
            'dni',
            array(
                'label' => 'Dni'
            )
        ); ?>
    </div><!-- .form-block -->

    <?= $this->Form->submit(
        'Guardar cambios',
        array(
            'class' => 'button color2'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
