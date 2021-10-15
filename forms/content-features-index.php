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
				'feature-' . $subpanel
			);

			if ( $obj['active'] ) {
				$_classes[] = '_is-active';
			}

			?>

            <div class="<?php echo join( ' ', $_classes ); ?>">
                <div class="_info">
                    <div class="_icon"><i class="d4p-icon d4p-<?php echo $obj['icon']; ?>"></i></div>
                    <h4 class="_title"><?php echo $obj['title']; ?></h4>
                    <p class="_description"><?php echo $obj['info']; ?></p>
                </div>
                <div class="_ctrl">
                    <div class="_activation">
                        <input id="d4p-feature-toggle-<?php echo $subpanel; ?>" type="checkbox"/>
                        <label for="d4p-feature-toggle-<?php echo $subpanel; ?>"><span class="d4p-accessibility-show-for-sr"><?php _e( "Active" ); ?></span></label>
                    </div>
					<?php if ( $obj['settings'] ) { ?>
                        <div class="_settings">
                            <a title="<?php echo sprintf( __( "Settings for '%s'" ), $obj['title'] ); ?>" href="#"><i class="d4p-icon d4p-ui-cog"></i></a>
                        </div>
					<?php } ?>
                </div>
            </div>

			<?php

		}

		?>
    </div>
</div>
