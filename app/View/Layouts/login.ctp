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

	<?php
		echo $this->Html->meta(
			'favicon.png',
			$this->Html->url('/img/favicon.png'),
			array('type' => 'icon')
		);
		echo $this->fetch('meta');

		$this->Html->css('reset', array('block' => 'css'));
		$this->Html->css('style-login', array('block' => 'css'));

		$this->Html->css('print/style',  array('block' => 'css', 'media' => 'print'));

		$this->Html->script('jquery/jquery-1.10.2', array('block' => 'scripts-ext'));
		$this->Html->script('jquery/jquery-ui-1.10.3.custom.min', array('block' => 'scripts-ext'));

		$this->Html->script('functions', array('block' => 'scripts'));

		echo $this->fetch('css');
		echo $this->fetch('scripts-ext');
		echo $this->fetch('scripts');
		echo $this->fetch('ckeditor');
	?>

	<script type="text/javascript">
			$(document).ready(function () {
				loadMenu();
				loadFunctions();

			});

	</script>

</head>
<body>
	<section id="container">
		<?= $this->fetch('content'); ?>
	</section><!-- #container -->
</body>
</html>
