<?php

use Dev4Press\v37\Core\Helpers\Languages;
use function Dev4Press\v37\Functions\panel;

$plugin = panel()->a()->settings()->i();
$sysreq = $plugin->system_requirements();

$translations = panel()->a()->settings()->i()->translations;
$translations = Languages::instance()->plugin_translations( $translations );

?>

<div class="d4p-info-block">
    <h3>
		<?php _e( "Current Version", "d4plib" ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php _e( "Version", "d4plib" ); ?>:</span><strong><?php echo esc_html( $plugin->version ); ?></strong>
            </li>
            <li>
                <span><?php _e( "Build", "d4plib" ); ?>:</span><strong><?php echo esc_html( $plugin->build ); ?></strong>
            </li>
            <li>
                <span><?php _e( "Status", "d4plib" ); ?>:</span><strong><?php echo esc_html( ucfirst( $plugin->status ) ); ?></strong>
            </li>
            <li>
                <span><?php _e( "Edition", "d4plib" ); ?>:</span><strong><?php echo esc_html( ucfirst( $plugin->edition ) ); ?></strong>
            </li>
            <li>
                <span><?php _e( "Date", "d4plib" ); ?>:</span><strong><?php echo esc_html( $plugin->updated ); ?></strong>
            </li>
        </ul>
        <hr style="margin: 1em 0 .7em; border-top: 1px solid #eee"/>
        <ul class="d4p-info-list">
            <li>
                <span><?php _e( "First released", "d4plib" ); ?>:</span><strong><?php echo esc_html( $plugin->released ); ?></strong>
            </li>
        </ul>
    </div>
</div>

<div class="d4p-info-block">
    <h3>
		<?php _e( "System Requirements", "d4plib" ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
			<?php

			foreach ( $sysreq as $name => $version ) {
				echo '<li><span>' . $name . ':</span><strong>' . $version . '</strong></li>';
			}

			?>
        </ul>
    </div>
</div>

<div class="d4p-info-block">
    <h3>
		<?php _e( "List of included Languages", "d4plib" ); ?>
    </h3>
    <div>
		<?php

		$translations = panel()->a()->settings()->i()->translations;
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