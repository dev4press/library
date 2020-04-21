<?php

$_panel = d4p_panel()->a()->panel_object();
$_subpanel = d4p_panel()->a()->subpanel;
$_subpanels = d4p_panel()->subpanels();

if ($_subpanels[$_subpanel]['method'] == 'post') {
    d4p_panel()->settings_fields();
}

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <?php echo d4p_panel()->r()->icon($_panel->icon); ?>
            <h3><?php echo $_panel->title; ?></h3>
            <?php

            if ($_subpanel != 'index') {
                echo '<h4>'.d4p_panel()->r()->icon($_subpanels[$_subpanel]['icon']).$_subpanels[$_subpanel]['title'].'</h4>';
            }

            ?>
        </div>
        <div class="d4p-panel-info">
            <?php echo $_subpanels[$_subpanel]['info']; ?>
        </div>
        <?php if ($_subpanel != 'index' && $_subpanels[$_subpanel]['method'] != '') { ?>
            <div class="d4p-panel-buttons">
                <?php if ($_subpanels[$_subpanel]['method'] == 'get') { ?>
                    <a type="button" href="<?php echo $_subpanels[$_subpanel]['button_url']; ?>" class="button-primary"><?php echo $_subpanels[$_subpanel]['button_label']; ?></a>
                <?php } else if ($_subpanels[$_subpanel]['method'] == 'ajax') { ?>
                    <input type="button" value="<?php echo $_subpanels[$_subpanel]['button_label']; ?>" class="button-primary"/>
                <?php } else { ?>
                    <input type="submit" value="<?php echo $_subpanels[$_subpanel]['button_label']; ?>" class="button-primary"/>
                <?php } ?>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e("Return to top", "d4plib"); ?></a>
            </div>
        <?php } ?>
    </div>
</div>
