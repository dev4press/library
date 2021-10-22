<?php

use function Dev4Press\v37\Functions\panel;

$_panel     = panel()->a()->panel_object();
$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();

$_url_reset = panel()->a()->current_url();
$_url_reset = add_query_arg( array(
	'single-action'   => 'reset',
	panel()->a()->v() => 'getback',
	'_wpnonce'        => wp_create_nonce( 'coreseo-feature-reset-' . $_subpanel )
) );

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <div class="_icon">
				<?php echo panel()->r()->icon( $_panel->icon ); ?>
            </div>
            <h3><?php echo $_panel->title; ?></h3>
			<?php

			if ( $_subpanel != 'index' ) {
				echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . $_subpanels[ $_subpanel ]['title'] . '</h4>';
			}

			?>
            <div class="_info">
				<?php echo $_subpanels[ $_subpanel ]['info']; ?>
            </div>
        </div>

		<?php if ( $_subpanel != 'index' ) { ?>
            <div class="d4p-panel-control">
                <button type="button" class="button-secondary d4p-feature-more-ctrl"><?php _e( "More Controls" ); ?></button>
                <div class="d4p-feature-more-ctrl-options" style="display: none">
                    <p><?php _e( "If you want, you can reset all the settings for this Feature to default values." ); ?></p>
                    <a class="button-primary" href="<?php echo $_url_reset; ?>"><?php _e( "Reset Feature Settings" ); ?></a>
                </div>
                <div class="d4p-panel-buttons">
                    <input type="submit" value="<?php _e( "Save Settings", "d4plib" ); ?>" class="button-primary"/>
                </div>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e( "Return to top", "d4plib" ); ?></a>
            </div>
		<?php } else { ?>
            <div class="d4p-panel-control">
                <button type="button" class="button-primary d4p-features-bulk-ctrl"><?php _e( "Bulk Control" ); ?></button>
                <div class="d4p-features-bulk-ctrl-options" style="display: none">
                    <p><?php _e( "You can enable or disable all the features at once." ); ?></p>
                    <div>
                        <button class="button-primary" type="button" data-action="enable"><?php _e( "Enable All" ); ?></button>
                        <button class="button-secondary" type="button" data-action="disable"><?php _e( "Disable All" ); ?></button>
                    </div>
                </div>
            </div>
		<?php } ?>
    </div>
</div>
