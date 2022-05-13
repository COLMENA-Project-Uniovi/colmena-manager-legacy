    <div class="search">
        <?= $this->Form->create(
            $model,
            array(
                'action' => 'index',
                'class' => 'search-form'
            )
        ); ?>
            <?= $this->Form->input(
                'keyword',
                array(
                    'label' => '',
                    'type' => 'text',
                    'value' => $keyword
                )
            ); ?>
        <?= $this->Form->end(
            array(
                'label' => isset($button)? $button: 'Buscar',
                'class' => 'button ' . $color
            )
        );?>
    </div><!-- .search -->
