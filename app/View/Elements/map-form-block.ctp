	<div class="form-block map">
		<h3>Dirección y coordenadas GPS</h3>
		<div class="map-columns">
			<div class="map-left">
				<?= $this->Form->input(
					'address',
					array(
						'label' => 'Primera parte de la dirección',
						'type' => 'text',
						'class' => "address1",
						'after' => '<div class="input-note">Tipo de vía, nombre de la vía, número</div>'
					)
				); ?>
				<?= $this->Form->input(
					'address2',
					array(
						'label' => 'Segunda parte de la dirección',
						'type' => 'text',
						'class' => "address2",
						'after' => '<div class="input-note">Código postal, localidad</div>'
					)
				); ?>
				<?= $this->Form->input(
					'zipcode',
					array(
						'label' => 'Código Postal',
						'class' => "zipcode",
					)
				); ?>
				<?= $this->Form->input('city', array('label' => 'Ciudad', 'class' => 'city'));	 ?>
				<?= $this->Form->input('province', array('label' => 'Provincia', 'class' => 'province'));	 ?>

				<?= $this->Form->input(
					'latitude',
					array(
						'label' => 'Latitud',
						'type' => 'text',
						'class' => "latitude",
						'readonly' => 'readonly'
					)
				); ?>
				<?= $this->Form->input(
					'longitude',
					array(
						'label' => 'Longitud',
						'type' => 'text',
						'class' => "longitude",
						'readonly' => 'readonly'
					)
				); ?>

				<div class="button address-search">Buscar dirección en el mapa</div>
			</div><!-- .map-left -->
			<div class="map-right">
				<h4>Si lo deseas, puedes arrastrar el marcador hasta la posición exacta donde se encuentra la propiedad. Haz zoom para mejorar la precisión al mover el marcador.</h4>
				<div class="map-canvas"></div>
			</div><!-- .map-right -->
		</div><!-- .map-columns -->
	</div><!-- .form-block -->
