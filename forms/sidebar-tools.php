<?php

use function Dev4Press\v36\Functions\panel;

$_panel     = panel()->a()->panel_object();
$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();

if ( $_subpanels[ $_subpanel ]['method'] == 'post' ) {
	panel()->settings_fields();
}

$_button_id = $_subpanels[ $_subpanel ]['button_id'] ?? panel()->a()->plugin_prefix . '-tools-' . $_subpanel;

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
			<?php echo panel()->r()->icon( $_panel->icon ); ?>
            <h3><?php echo $_panel->title; ?></h3>
			<?php

			if ( $_subpanel != 'index' ) {
				echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . $_subpanels[ $_subpanel ]['title'] . '</h4>';
			}

			?>
        </div>
        <div class="d4p-panel-info">
			<?php echo $_subpanels[ $_subpanel ]['info']; ?>
        </div>
		<?php if ( $_subpanel != 'index' && $_subpanels[ $_subpanel ]['method'] != '' ) { ?>
            <div class="d4p-panel-buttons">
				<?php if ( $_subpanels[ $_subpanel ]['method'] == 'get' ) { ?>
                    <a id="<?php echo $_button_id; ?>" type="button" href="<?php echo $_subpanels[ $_subpanel ]['button_url']; ?>" class="button-primary"><?php echo $_subpanels[ $_subpanel ]['button_label']; ?></a>
				<?php } else if ( $_subpanels[ $_subpanel ]['method'] == 'ajax' ) { ?>
                    <input id="<?php echo $_button_id; ?>" type="button" value="<?php echo $_subpanels[ $_subpanel ]['button_label']; ?>" class="button-primary"/>
				<?php } else { ?>
                    <input id="<?php echo $_button_id; ?>" type="submit" value="<?php echo $_subpanels[ $_subpanel ]['button_label']; ?>" class="button-primary"/>
				<?php } ?>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e( "Return to top", "d4plib" ); ?></a>
            </div>
		<?php } ?>
    </div>
</div>
