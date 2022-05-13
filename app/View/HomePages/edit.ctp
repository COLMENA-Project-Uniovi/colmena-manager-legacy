<?php
$model_name = 'HomePage';
$entity_id = $entity[$model_name]['id'];

$header = array(
    "title" => array(
        "name" => "Editar portada",
        "color" => "color1"
    ),
    "breadcrumbs" => array(
        "Inicio" => array(
            'controller' => 'pages',
            'action' => 'home'
        ),
        "Editar portada" => array()
    )
);
?>

<?= $this->element(
    "header",
    $header
); ?>

<?php
    if(count(Configure::read('Config.locales.available')) > 1){
?>
<section class="languages">
    <div class="content-tabs section<?= $entity[$model_name]['id'] ?>-color sections-color">
<?php
        foreach (Configure::read('Config.locales.available') as $code => $name) {
            $current = "";
            if($code == $entity[$model_name]['locale']){
                $current = "current";
            }
            echo $this->Html->link($name, array('action' => 'edit', $entity_id, $code), array('class' => 'tab ' . $current));
        }
?>
    </div><!-- .tabs-content -->
<?php
        if(!empty($entity['default'])){
?>
    <div class="admin-view default-content">


        <div class="clearboth"></div>
    </div><!-- .admin-view -->
<?php
        }
?>
</section><!-- .languages -->
<?php
    }
?>

<?= $this->Form->create(
    $model_name,
    array(
        'class' => 'admin-form ',
        'type' => 'file'
    )
); ?>
    <div class="form-block">
        <h3>Posicionamiento SEO de la página</h3>
        <?= $this->Form->input(
            'title',
            array(
                'label' => 'Título',
                'type' => 'text',
                'after' => '<div class="input-note">Introduce el título de la página.</div>'
            )
        ); ?>
        <?= $this->Form->input(
            'description',
            array(
                'label' => 'Descripción',
                'rows' => '5',
                'after' => '<div class="input-note">Introduce una breve descripción de la página.</div>'
            )
        ); ?>
        <?= $this->Form->input(
            'keywords',
            array(
                'label' => 'Palabras clave',
                'class' => 'keywords',
                'after' => '<div class="input-note">Introduce las palabras clave para la página separadas por comas.</div>'
            )
        ); ?>
    </div><!-- .form-block -->
    <div class="form-block">
    <h3>Redes sociales</h3>
        <?= $this->Form->input(
            'twitter',
            array(
                'label' => 'Twitter',
                'after' => '<div class="input-note">Introduce la dirección del Twitter.</div>'
            )
        ); ?>
        <?= $this->Form->input(
            'facebook',
            array(
                'label' => 'Facebook',
                'after' => '<div class="input-note">Introduce la dirección del Facebook.</div>'
            )
        ); ?>
        <?= $this->Form->input(
            'instagram',
            array(
                'label' => 'Instagram',
                'after' => '<div class="input-note">Introduce la dirección del Instagram.</div>'
            )
        ); ?>
    </div><!-- .form-block -->

    <?= $this->Form->submit(
        'Editar portada',
        array(
            'class' => 'color1 button'
        )
    ); ?>
    <div class="clearboth"></div>
<?= $this->Form->end(); ?>
