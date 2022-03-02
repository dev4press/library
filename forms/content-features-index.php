<?php

use Dev4Press\v37\Core\Quick\Sanitize;
use function Dev4Press\v37\Functions\panel;

?>
<div class="d4p-content">
    <div class="d4p-features-wrapper">
		<?php

		foreach ( panel()->subpanels() as $subpanel => $obj ) {
			if ( $subpanel == 'index' ) {
				continue;
			}

			$_checked = '';
			$_classes = array(
				'd4p-feature-box',
				'feature-' . $subpanel,
				'_is-feature'
			);

			if ( $obj['active'] ) {
				$_classes[] = '_is-active';
				$_checked   = ' checked="checked"';
			}

			if ( $obj['always_on'] ) {
				$_classes[] = '_is-always-on';
			}

			$url = panel()->a()->panel_url( 'features', $subpanel );

			?>

            <div class="<?php echo Sanitize::html_classes( $_classes ); ?>">
                <div class="_info">
                    <div class="_icon"><i class="d4p-icon d4p-<?php echo esc_attr( $obj['icon'] ); ?>"></i></div>
                    <h4 class="_title"><?php echo esc_html( $obj['title'] ); ?></h4>
                    <p class="_description"><?php echo esc_html( $obj['info'] ); ?></p>
                </div>
                <div class="_ctrl">
					<?php if ( ! $obj['always_on'] ) { ?>
                        <div class="_activation">
                            <input<?php echo $_checked; ?> data-feature="<?php echo esc_attr( $subpanel ); ?>" id="d4p-feature-toggle-<?php echo esc_attr( $subpanel ); ?>" type="checkbox"/>
                            <label for="d4p-feature-toggle-<?php echo esc_attr( $subpanel ); ?>"><span class="d4p-accessibility-show-for-sr"><?php esc_html_e( "Active", "d4plib" ); ?></span></label>
                        </div>
					<?php } ?>
					<?php if ( $obj['settings'] ) { ?>
                        <div class="_settings">
                            <a title="<?php echo sprintf( __( "Settings for '%s'", "d4plib" ), $obj['title'] ); ?>" href="<?php echo esc_url( $url ); ?>"><i class="d4p-icon d4p-ui-cog"></i></a>
                        </div>
					<?php } ?>
                </div>
            </div>

			<?php

		}

		?>
    </div>
</div>
