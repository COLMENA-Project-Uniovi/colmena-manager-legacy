<section class="section-parts">
    <h2 class="sections-title">Contenido de la sección</h2>
    <div class="add-part-buttons">
        <?= $this->Html->link(
            "Añadir texto+imagen",
            array(
                'controller' => 'section_part_images',
                'action' => 'add',
                $entity[$model_name]['id']
            ),
            array(
                'class' => 'color10 button'
            )
        ); ?>
        <?= $this->Html->link(
            "Añadir pestañas",
            array(
                'controller' => 'section_tabs',
                'action' => 'add',
                $entity[$model_name]['id']
            ),
            array(
                'class' => 'color10 button'
            )
        ); ?>
        <?= $this->Html->link(
            "Añadir galería",
            array(
                'controller' => 'section_galleries',
                'action' => 'add',
                $entity[$model_name]['id']
            ),
            array(
                'class' => 'color10 button'
            )
        ); ?>
        <?= $this->Html->link(
            "Añadir destacado",
            array(
                'controller' => 'section_featureds',
                'action' => 'add',
                $entity[$model_name]['id']
            ),
            array(
                'class' => 'color10 button'
            )
        ); ?>
        <div class="clearboth"></div>
    </div><!-- .add-part-buttons -->
    <div class="web-content">
        <header class="color10">
            <h2><?= !isset($entity[$model_name]['name'])? $entity['default'][$model_name]['name']: $entity[$model_name]['name']; ?></h2>
        </header>

        <div class="content">
<?php
    if (empty($parts)) {
?>
            <p class="no-results">No se ha añadido ninguna parte de contenido en este idioma.</p>
<?php
    } else {
        foreach ($parts as $part) {
            $key = key($part);
            $controller = "";
            switch ($key) {
                case 'SectionPartImage':
                    $controller = "section_part_images";
?>
        <div class="part textimage">
<?php
            $entity_id = $entity[$model_name]['id'];

            if ($part[$key]['img_url']) {
                $specific_path = Configure::read("timthumb") . Configure::read("resources_path") . DS . $part[$key]['img_url'] . '&'.time() . '&w='.$part[$key]['image_width'] . '&h='.$part[$key]['image_height'];

                if ($part[$key]['image_adjust']) {
                    $specific_path .= "&zc=2";
                }

                echo $this->Html->image(
                    $specific_path,
                    array(
                        'alt' => 'Imagen principal',
                        'class' => 'image '.$part[$key]['image_position']
                    )
                );
            }

            $part_columns = $part[$key]['content_columns'] != "0" ?
                'style="-webkit-column-count: '.$part[$key]['content_columns'].'; -moz-column-count: '.$part[$key]['content_columns'].'; column-count: '.$part[$key]['content_columns'].';"' :
                "";
?>
            <div class="part-wrapper" <?= $part_columns; ?>>
                <?= $part[$key]['content']; ?>
            </div>
            <div class="clearboth"></div>
<?php
                break;
                case 'SectionFeatured':
                    $controller = "section_featureds";
?>
        <div class="part featured">
<?php
            $entity_id = $entity[$model_name]['id'];

            if ($part[$key]['img_url']) {
                $specific_path = Configure::read("base_url") . Configure::read("resources_path") . DS . $part[$key]['img_url'] . '?'.time();
            }

            $strips_url = Configure::read("base_url") . "admin/img/sections/strips.png";
?>
            <div class="featured-content">
                <p class="line1"><?= $part[$key]['line1']; ?></p>
                <p class="line2"><?= $part[$key]['line2']; ?></p>
            </div>
            <div class="clearboth"></div>
<?php
                break;
                case 'SectionGallery':
                    $controller = "section_galleries";
?>
        <div class="part gallery">
            <h3><?= $part[$key]['title']; ?></h3>
            <?= $part[$key]['content']; ?>
            <div class="images">
<?php
            foreach ($part[$key]['gallery'] as $image) {
?>
                <div class="image">
<?php
                    $img_url = Configure::read("timthumb") . Configure::read("resources_path") . DS . $image . $extra_images_width;
                    $fancy_url = Configure::read("base_url") . Configure::read("resources_path") . DS . $image;
?>
                    <a href="<?= $fancy_url; ?>" class="fancybox " rel="group">
                        <?= $this->Html->image($img_url);?>
                        <div class="glass"></div>
                    </a>
                </div>
<?php
            }
?>
                <div class="clearboth"></div>
            </div><!-- .images -->
<?php
                break;
                case 'SectionTab':
                    $controller = "section_tabs";
?>
        <div class="part tabs">
<?php
            $entity_id = $entity[$model_name]['id'];
?>
            <div class="header-tabs">
<?php
            if($part[$key]['tab_title1'] != ""){
?>
                <div id="tab1" class="tab"><?= $part[$key]['tab_title1']; ?></div>
<?php
            }
            if($part[$key]['tab_title2'] != ""){
?>
                <div id="tab2" class="tab"><?= $part[$key]['tab_title2']; ?></div>
<?php
            }
            if($part[$key]['tab_title3'] != ""){
?>
                <div id="tab3" class="tab"><?= $part[$key]['tab_title3']; ?></div>
<?php
            }
            if($part[$key]['tab_title4'] != ""){
?>
                <div id="tab4" class="tab"><?= $part[$key]['tab_title4']; ?></div>
<?php
            }
?>
            </div><!-- .header-tabs -->
            <div class="content-tabs">
<?php
            if($part[$key]['tab_title1'] != ""){
?>
                <div class="tab tab1"><?= $part[$key]['tab_content1']; ?></div>
<?php
            }
            if($part[$key]['tab_title2'] != ""){
?>
                <div class="tab tab2"><?= $part[$key]['tab_content2']; ?></div>
<?php
            }
            if($part[$key]['tab_title3'] != ""){
?>
                <div class="tab tab3"><?= $part[$key]['tab_content3']; ?></div>
<?php
            }
            if($part[$key]['tab_title4'] != ""){
?>
                <div class="tab tab4"><?= $part[$key]['tab_content4']; ?></div>
<?php
            }
?>
            </div><!-- .content-tabs -->
<?php
                break;
            }
?>

            <div class="actions">
                <?= $this->Form->postLink(
                    'Eliminar',
                    array(
                        'controller' => $controller,
                        'action' => 'delete',
                        $entity[$model_name]['id'],
                        $part[$key]['id']
                    ),
                    array(
                        'class' => 'color10 button',
                        'confirm' => '¿Estás seguro de que quieres eliminar esta parte de la sección?'
                    )
                ); ?>
                <?= $this->Html->link(
                    "Editar",
                    array(
                        'controller' => $controller,
                        'action' => 'edit',
                        $entity[$model_name]['id'],
                        $part[$key]['id']
                    ),
                    array(
                        'class' => 'color10 button'
                    )
                ); ?>
                <div class="clearboth"></div>
            </div><!-- .actions -->
        </div><!-- .part -->

        <div class="clearboth"></div>
<?php
        }
    }
?>
        </div><!-- .content -->
    </div><!-- .web-content -->
</section><!-- .section-parts -->
