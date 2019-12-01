<div class="d4p-content">
    <?php

        foreach (d4p_panel()->subpanels() as $subpanel => $obj) {
            if ($subpanel == 'index' || $subpanel == 'full') continue;

            $url = d4p_panel()->a()->panel_url('settings', $subpanel);

            if (isset($obj['break'])) { ?>

                <div style="clear: both"></div>
                <div class="d4p-panel-break d4p-clearfix">
                    <h4><?php echo $obj['break']; ?></h4>
                </div>
                <div style="clear: both"></div>

            <?php } ?>

            <div class="d4p-options-panel">
                <?php echo d4p_panel()->r()->icon($obj['icon']); ?>
                <h5><?php echo $obj['title']; ?></h5>
                <div>
                    <a class="button-primary" href="<?php echo $url; ?>"><?php _e("Settings Panel", "gd-topic-polls"); ?></a>
                </div>
            </div>

            <?php
        }

    ?>
</div>
