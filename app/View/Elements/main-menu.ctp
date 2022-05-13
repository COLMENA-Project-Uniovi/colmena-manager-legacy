    <?php
        $user = $this->Session->read("Auth.User");
        if(!empty($user)){
    ?>
        <nav class="hidden">
            <section class="content-nav">
                <section class="close-nav hidden">
                    <?= $this->Html->image('menus/responsivo.png', array('alt' => 'manu-selector', 'class' => 'menu-selector')); ?>
            </section><!-- .close-nav -->

                <section class="logout">
                    <a href="./logout">
                    <?= $this->Html->link($this->Html->image("menus/logout.png"), array('controller'=>'users', 'action' => 'logout'), array('escape' => false));
                    ?>
                    </a>
                </section><!-- .close-nav -->

                <section class="logo">
                    <section class="content-logo">

                        <?= $this->Html->link($this->Html->image('thumbs/timthumb.php?src=./menus/logo.png&w=210', array('alt' => 'logo')), "/" , array('escape' => false));
                        ?>
                    </section><!-- .content-logo -->
                </section><!-- .logo -->

                <div class="clearboth"></div>
                <section class="items">
                    <?= $this->Html->link(
                        "Asignaturas",
                        array(
                            'controller' => 'colmena_subjects',
                            'action' => 'index'
                        ),
                        array(
                            'class' => 'menu-item color0'
                        )
                    ); ?>
                    <?= $this->Html->link(
                        "Compilaciones sin clasificar",
                        array(
                            'controller' => 'colmena_compilations',
                            'action' => 'index'
                        ),
                        array(
                            'class' => 'menu-item color1'
                        )
                    ); ?>
                    <?= $this->Html->link(
                        "Salir ".$this->Html->image("menus/logout.png",
                            array(
                                'alt' => 'cerrar_sesion',
                                'class' => 'logout-min'
                            )
                        ),
                        array(
                            'controller'=>'users',
                            'action' => 'logout'
                        ),
                        array(
                            'class' => 'menu-item salir',
                            'escape' => false
                        )
                    ); ?>
                </section><!-- .menu-desplegable -->
            </section><!-- .content-nav -->
        </nav>
    <?php
        }
    ?>
