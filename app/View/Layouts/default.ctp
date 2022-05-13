<?php
/**
  *
 * @copyright     Copyright (c) Neozink
 * @link          http://www.neozink.com Neozink Mkt No Convencional
 * @since         Neozink(tm) v 0.0.0.1
 * @license       All Rights Reserved
 */
?>

<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset('utf8'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>Administración - Colmena</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
    <?php
        echo $this->Html->meta(
            'favicon.png',
            $this->Html->url('/img/favicon.png'),
            array('type' => 'icon')
        );
        echo $this->fetch('meta');

        $this->Html->css('reset', array('block' => 'css'));
        $this->Html->css('style', array('block' => 'css'));
        $this->Html->css('style-smartphone', array('block' => 'css'));
        $this->Html->css('style-web', array('block' => 'css'));
        $this->Html->css('colors', array('block' => 'css'));
        $this->Html->css('jquery/jquery-ui-1.10.3.custom', array('block' => 'css'));

        $this->Html->css('print/style',  array('block' => 'css', 'media' => 'print'));

        //COLOR PICKER
        $this->Html->script('jquery/colorPicker/jquery.simple-color-picker', array('block' => 'scripts-ext'));
        $this->Html->css('jquery/colorPicker/jquery.simple-color-picker', array('block' => 'css'));


        $this->Html->script('jquery/jquery-1.10.2', array('block' => 'scripts-ext'));
        $this->Html->script('jquery/jquery-ui-1.10.3.custom.min', array('block' => 'scripts-ext'));
        $this->Html->script('http://malsup.github.com/jquery.form.js', array('block' => 'scripts-ext'));
        $this->Html->script('jquery/datepicker/jquery.ui.datepicker-es', array('block' => 'scripts-ext'));
        $this->Html->script('jquery/autocomplete/jquery.ui.autocomplete', array('block' => 'scripts-ext'));
        // SELECT 2
        $this->Html->script('select2/select2.min', array('block' => 'scripts-ext'));
        $this->Html->script('select2/select2_locale_es', array('block' => 'scripts-ext'));
        $this->Html->css('select2/select2', array('block' => 'css'));
        // FANCYBOX
        $this->Html->script('jquery/fancybox/jquery.fancybox', array('block' => 'scripts-ext'));
        $this->Html->script('jquery/fancybox/jquery.fancybox.pack', array('block' => 'scripts-ext'));
        $this->Html->script('jquery/fancybox/jquery.fancybox-buttons', array('block' => 'scripts-ext'));
        $this->Html->script('jquery/fancybox/jquery.fancybox-media', array('block' => 'scripts-ext'));
        $this->Html->script('jquery/fancybox/jquery.fancybox-thumbs', array('block' => 'scripts-ext'));
        $this->Html->script('jquery/fancybox/jquery.mousewheel-3.0.6.pack', array('block' => 'scripts-ext'));
        $this->Html->css('jquery/fancybox/jquery.fancybox', array('block' => 'css'));
        $this->Html->css('jquery/fancybox/jquery.fancybox-buttons', array('block' => 'css'));
        $this->Html->css('jquery/fancybox/jquery.fancybox-thumbs', array('block' => 'css'));
        // NEO MAPS
        $this->Html->script("http://maps.google.com/maps/api/js?sensor=false", array('block' => "scripts-ext"));
        $this->Html->script('maps/neo-maps-3.0', array('block' => 'scripts-ext'));

        $this->Html->script('menu-desplegable', array('block' => 'scripts'));
        $this->Html->script('functions', array('block' => 'scripts'));
        $this->Html->script('ckeditor/ckeditor.js', array('block' => 'ckeditor'));

        echo $this->fetch('css');
        echo $this->fetch('scripts-ext');
        echo $this->fetch('scripts');
        echo $this->fetch('ckeditor');
    ?>
</head>
<body>
    <section id="container">
        <?= $this->element('main-menu'); ?>

        <section id="flow-content">
            <?php echo $this->Session->flash(); ?>
            <?php echo $this->fetch('content'); ?>
        </section><!-- #flow-content -->
    </section><!-- #container -->
    <script type="text/javascript">
        var admin_path = '<?= $this->html->url('/', true); ?>';
        loadMenu();
        loadFunctions();

        //ckeditor integration
        CKEDITOR.replace('.ckeditor');
    </script>
<?php echo $this->Js->writeBuffer(); // Write cached scripts ?>
</body>
</html>
<?php $this->Html->css('http://fonts.googleapis.com/css?family=Carrois+Gothic', array('block' => 'css'));?>
