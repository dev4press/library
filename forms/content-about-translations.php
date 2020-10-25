<?php use Dev4Press\Core\Helpers\Languages; ?>
<div class="d4p-info-block">
    <h3>
		<?php _e( "List of included Languages", "d4plib" ); ?>
    </h3>
    <div>
		<?php

		$translations = d4p_panel()->a()->settings()->i()->translations;
		$translations = Languages::instance()->plugin_translations( $translations );

		foreach ( $translations as $code => $obj ) {
			echo '<div class="d4p-block-language"><h4>' . $code . ': ' . $obj['native'] . ' / ' . $obj['english'] . '</h4>';
			echo '<p>' . __( "Plugin Version", "d4plib" ) . ': ' . $obj['version'] . '</p>';

			if ( ! empty( $obj['contributors'] ) ) {
				$contributors = array();

				foreach ( $obj['contributors'] as $c ) {
					$contributors[] = '<a href="' . $c['url'] . '" target="_blank" rel="noopener">' . $c['name'] . '</a>';
				}

				echo '<p>' . __( "Contributors", "d4plib" ) . ': ' . join( ', ', $contributors ) . '</p>';
			}

			echo '</div>';
		}

		?>
    </div>
</div>