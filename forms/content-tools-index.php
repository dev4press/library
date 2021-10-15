<?php

use function Dev4Press\v37\Functions\panel;

?>
<div class="d4p-content">
    <div class="d4p-features-wrapper">
	<?php

	foreach ( panel()->subpanels() as $subpanel => $obj ) {
		if ( $subpanel == 'index' ) {
			continue;
		}

		$_classes = array(
			'd4p-feature-box',
			'tool-' . $subpanel,
            '_is-tool'
		);

		$url = panel()->a()->panel_url( 'tools', $subpanel );

		if ( isset( $obj['break'] ) ) {
			echo panel()->r()->settings_break( $obj['break'], $obj['break-icon'] );
		}

		?>

        <div class="<?php echo join( ' ', $_classes ); ?>">
            <div class="_info">
                <div class="_icon"><i class="d4p-icon d4p-<?php echo $obj['icon']; ?>"></i></div>
                <h4 class="_title"><?php echo $obj['title']; ?></h4>
                <p class="_description"><?php echo $obj['info']; ?></p>
            </div>
            <div class="_ctrl">
                <div class="_open">
                    <a class="button-primary" href="<?php echo $url; ?>"><?php _e( "Open", "d4plib" ); ?></a>
                </div>
            </div>
        </div>

		<?php

	}

	?>
    </div>
</div>
