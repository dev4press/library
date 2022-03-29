<?php

use function Dev4Press\v38\Functions\panel;

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
            <h3><?php echo esc_html( $_panel->title ); ?></h3>
			<?php

			if ( $_subpanel != 'index' ) {
				echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . $_subpanels[ $_subpanel ]['title'] . '</h4>';
			}

			?>
            <div class="_info">
				<?php echo esc_html( $_subpanels[ $_subpanel ]['info'] ); ?>
            </div>
        </div>

		<?php if ( $_subpanel != 'index' ) { ?>
            <div class="d4p-panel-control">
                <button type="button" class="button-secondary d4p-feature-more-ctrl"><?php esc_html_e( "More Controls", "d4plib" ); ?></button>
                <div class="d4p-feature-more-ctrl-options" style="display: none">
                    <p><?php esc_html_e( "If you want, you can reset all the settings for this Feature to default values.", "d4plib" ); ?></p>
                    <a class="button-primary" href="<?php echo esc_url( $_url_reset ); ?>"><?php esc_html_e( "Reset Feature Settings", "d4plib" ); ?></a>
                </div>
                <div class="d4p-panel-buttons">
                    <input type="submit" value="<?php esc_attr_e( "Save Settings", "d4plib" ); ?>" class="button-primary"/>
                </div>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php esc_html_e( "Return to top", "d4plib" ); ?></a>
            </div>
		<?php } else { ?>
            <div class="d4p-panel-control">
                <button type="button" class="button-primary d4p-features-bulk-ctrl"><?php esc_html_e( "Bulk Control", "d4plib" ); ?></button>
                <div class="d4p-features-bulk-ctrl-options" style="display: none">
                    <p><?php esc_html_e( "You can enable or disable all the features at once.", "d4plib" ); ?></p>
                    <div>
                        <button class="button-primary" type="button" data-action="enable"><?php esc_html_e( "Enable All", "d4plib" ); ?></button>
                        <button class="button-secondary" type="button" data-action="disable"><?php esc_html_e( "Disable All", "d4plib" ); ?></button>
                    </div>
                </div>
            </div>
		<?php } ?>
    </div>
</div>
