<?php

$_panel = d4p_panel()->a()->panel_object();
$_subpanel = d4p_panel()->a()->subpanel;
$_subpanels = d4p_panel()->subpanels();

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <?php echo d4p_panel()->r()->icon($_panel->icon); ?>
            <h3><?php echo $_panel->title; ?></h3>
            <?php

            if ($_subpanel == 'full') {
                echo '<h4>'.d4p_panel()->r()->icon($_subpanels[$_subpanel]['icon']).__("All Settings").'</h4>';
            } else if ($_subpanel != 'index') {
                echo '<h4>'.d4p_panel()->r()->icon($_subpanels[$_subpanel]['icon']).$_subpanels[$_subpanel]['title'].'</h4>';
            }

            ?>
        </div>
        <div class="d4p-panel-info">
            <?php echo $_subpanels[$_subpanel]['info']; ?>
        </div>
        <?php if ($_subpanel == 'full') { ?>
            <div class="d4p-panel-mark">
                <p><?php _e("Search through settings by typing what you need to find in this field."); ?></p>
                <input type="text" class="widefat" id="d4p-settings-mark" />
                <button type="button"><i class="d4p-icon d4p-ui-clear" title="<?php esc_attr_e("Clear", "d4plib"); ?>"></i></button>
            </div>
        <?php } ?>
        <?php if ($_subpanel != 'index') { ?>
            <div class="d4p-panel-buttons">
                <input type="submit" value="<?php _e("Save Settings", "gd-topic-polls"); ?>" class="button-primary" />
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e("Return to top", "gd-topic-polls"); ?></a>
            </div>
        <?php } else { ?>
            <div class="d4p-panel-buttons">
                <a style="text-align: center" href="<?php echo d4p_panel()->a()->panel_url('settings', 'full'); ?>" class="button-secondary"><?php _e("Show All Settings", "gd-topic-polls"); ?></a>
            </div>
        <?php } ?>
    </div>
</div>
