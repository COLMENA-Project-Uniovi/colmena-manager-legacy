	<div class="form-block">
		<h3>Posicionamiento SEO</h3>
		<p>NOTA: Si no introduces ningún dato se utilizarán los datos de SEO por defecto.</p>
		<?= $this->Form->input(
			$model_name.'.SeoModel.title',
			array(
				'label' => 'Título',
				'type' => 'text',
				'after' => '<div class="input-note">Introduce el título de este elemento.</div>'
			)
		); ?>
		<?= $this->Form->input(
			$model_name.'.SeoModel.description',
			array(
				'label' => 'Descripción',
				'rows' => '5',
				'after' => '<div class="input-note">Introduce una breve descripción de este elemento.</div>'
			)
		); ?>
		<?= $this->Form->input(
			$model_name.'.SeoModel.keywords',
			array(
				'label' => 'Palabras clave',
				'class' => 'keywords',
				'after' => '<div class="input-note">Introduce las palabras clave para este elemento separadas por comas.</div>'
			)
		); ?>
		<?= $this->Form->input(
			$model_name.'.SeoModel.folder',
			array(
				'label' => 'URL amigable',
				'after' => '<div class="input-note">Introduce la URL amigable de este elemento para mostrar en el navegador. El identificador debe ser único.</div>'
			)
		); ?>
	</div><!-- .form-block -->
