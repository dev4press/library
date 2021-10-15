<?php

use function Dev4Press\v37\Functions\panel;

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
            <div class="d4p-panel-buttons">
                <input type="submit" value="<?php _e( "Save Settings", "d4plib" ); ?>" class="button-primary"/>
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e( "Return to top", "d4plib" ); ?></a>
            </div>
		<?php } else { ?>

		<?php } ?>
    </div>
</div>
