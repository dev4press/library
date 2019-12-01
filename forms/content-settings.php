<?php use Dev4Press\Core\Options\Render; ?>
<div class="d4p-content">
    <?php

    d4p_panel()->settings_fields(); ?>

    <?php

    $panel = d4p_panel()->a()->subpanel;
    $class = d4p_panel()->settings_class;
    $options = $class::instance();
    $groups = $options->get($panel);

    Render::instance('gdpolvalue')->prepare($panel, $groups)->render();

    ?>
</div>