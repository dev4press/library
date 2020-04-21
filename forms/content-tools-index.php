<div class="d4p-content">
    <?php

    foreach (d4p_panel()->subpanels() as $subpanel => $obj) {
        if ($subpanel == 'index') {
            continue;
        }

        $url = d4p_panel()->a()->panel_url('tools', $subpanel);

        if (isset($obj['break'])) {
            echo d4p_panel()->r()->settings_break($obj['break'], $obj['break-icon']);
        }

        ?>

        <div class="d4p-options-panel">
            <?php echo d4p_panel()->r()->icon($obj['icon']); ?>
            <h5><?php echo $obj['title']; ?></h5>
            <div>
                <a class="button-primary" href="<?php echo $url; ?>"><?php _e("Tools Panel", "d4plib"); ?></a>
            </div>
        </div>

        <?php

    }

    ?>
</div>
