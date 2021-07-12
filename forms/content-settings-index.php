<?php

use function Dev4Press\v36\Functions\panel;

?>
<div class="d4p-content">
	<?php

	foreach ( panel()->subpanels() as $subpanel => $obj ) {
		if ( $subpanel == 'index' || $subpanel == 'full' ) {
			continue;
		}

		$url = panel()->a()->panel_url( 'settings', $subpanel );

		if ( isset( $obj['break'] ) ) {
			echo panel()->r()->settings_break( $obj['break'], $obj['break-icon'] );
		}

		?>

        <div class="d4p-options-panel">
			<?php echo panel()->r()->icon( $obj['icon'] ); ?>
            <h5 aria-label="<?php echo $obj['info']; ?>" data-balloon-pos="up-left" data-balloon-length="large"><?php echo $obj['title']; ?></h5>
            <div>
                <a class="button-primary" href="<?php echo $url; ?>"><?php _e( "Settings Panel", "d4plib" ); ?></a>
            </div>
        </div>

		<?php

	}

	?>
</div>
