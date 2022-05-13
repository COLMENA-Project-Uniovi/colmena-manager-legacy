<header>
    <h2 class="<?= $title['color']; ?>"><?= $title['name']; ?></h2>

    <div class="breadcrumbs">
<?php
    $last_element = end($breadcrumbs);
    foreach ($breadcrumbs as $breadcrumb => $content) {
        if (empty($content)) {
            echo $breadcrumb;
        } else {
            echo $this->Html->link(
                $breadcrumb,
                $content
            );
        }

        if ($content !== $last_element) {
            echo " » ";
        }
    }
?>
    </div><!-- .breadcrumbs -->
</header>
