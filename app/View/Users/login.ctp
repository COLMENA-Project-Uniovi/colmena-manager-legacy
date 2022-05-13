            <section class="login">
                <h1>
                    <?=
                        $this->Html->link(
                            $this->Html->image(
                                'thumbs/timthumb.php?src=./menus/logo.png&w=200',
                                array(
                                    'alt' => 'Logo'
                                )
                            ),
                            "/",
                            array(
                                'escape' => false
                            )
                        );
                    ?>
                </h1>
                <p>Administrador de Colmena</p>
                <?= $this->Session->flash('auth'); ?>
                <?= $this->Form->create('User', array('class' => 'login-form')); ?>
                    <?= $this->Form->input('username', array('label' => 'Usuario', 'autofocus' => 'autofocus')); ?>
                    <?= $this->Form->input('password', array('label' => 'Contraseña')); ?>
                    <?= $this->Form->submit('Acceso', array('class' => 'button')); ?>
                <?= $this->Form->end(); ?>
            </section><!-- .login -->
