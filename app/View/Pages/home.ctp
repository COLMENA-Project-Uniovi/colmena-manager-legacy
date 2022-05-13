<header class="home">
    <h1>
        <?=
        $this->Html->link($this->Html->image('thumbs/timthumb.php?src=./menus/logo.png&w=200', array('alt' => 'logo')), "/" , array('escape' => false));
        ?>
    </h1>
    <p>Administrador de Colmena.</p>
</header><!-- .home -->
<section class="content-bloques">
    <section class="content-bloques-menu">
        <section class="bloque normal color0">
            <?= $this->Html->link(
                'Usuarios' ,
                array(
                    'controller' => 'colmena_users',
                    'action' => 'index'
                )
            ); ?>
        </section>
        <section class="bloque normal color0">
            <?= $this->Html->link(
                'Asignaturas' ,
                array(
                    'controller' => 'colmena_subjects',
                    'action' => 'index'
                )
            ); ?>
        </section><!-- .bloque -->
        <section class="bloque normal color0">
            <?= $this->Html->link(
                'Compilaciones sin clasificar' ,
                array(
                    'controller' => 'colmena_compilations',
                    'action' => 'index'
                )
            ); ?>
        </section><!-- .bloque -->
        <div class="clearboth"></div>
    </section><!-- .content-bloques-menu -->

    <div class="clearboth"></div>
</section><!-- .content-bloques -->
