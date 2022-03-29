<?php

use function Dev4Press\v38\Functions\panel;

$_panel     = panel()->a()->panel_object();
$_subpanel  = panel()->a()->subpanel;
$_subpanels = panel()->subpanels();

?>
<div class="d4p-sidebar">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <div class="_icon">
				<?php echo panel()->r()->icon( $_panel->icon ); ?>
            </div>
            <h3><?php echo esc_html( $_panel->title ); ?></h3>
			<?php

			if ( $_subpanel == 'full' ) {
				echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . esc_html__( "All Settings", "d4plib" ) . '</h4>';
			} else if ( $_subpanel != 'index' ) {
				echo '<h4>' . panel()->r()->icon( $_subpanels[ $_subpanel ]['icon'] ) . esc_html( $_subpanels[ $_subpanel ]['title'] ) . '</h4>';
			}

			?>
            <div class="_info">
				<?php echo esc_html( $_subpanels[ $_subpanel ]['info'] ); ?>
            </div>
        </div>
		<?php if ( $_subpanel == 'full' ) { ?>
            <div class="d4p-panel-mark">
                <p><?php esc_html_e( "Search through settings by typing what you need to find in this field.", "d4plib" ); ?></p>
                <input type="text" class="widefat" id="d4p-settings-mark" aria-label="<?php esc_attr_e( "Search Settings", "d4plib" ); ?>"/>
                <button type="button">
                    <i class="d4p-icon d4p-ui-clear" title="<?php esc_attr_e( "Clear Search", "d4plib" ); ?>"></i>
                </button>
            </div>
		<?php } ?>
		<?php if ( $_subpanel != 'index' ) { ?>
            <div class="d4p-panel-buttons">
                <input type="submit" value="<?php esc_attr_e( "Save Settings", "d4plib" ); ?>" class="button-primary"/>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php esc_html_e( "Return to top", "d4plib" ); ?></a>
            </div>
		<?php } else { ?>
            <div class="d4p-panel-buttons">
                <a style="text-align: center" href="<?php echo esc_url( panel()->a()->panel_url( 'settings', 'full' ) ); ?>" class="button-secondary"><?php esc_html_e( "Show All Settings", "d4plib" ); ?></a>
            </div>
		<?php } ?>
    </div>
</div>
