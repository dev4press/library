<?php

use function Dev4Press\v37\Functions\panel;

$plugin = panel()->a()->settings()->i();
$sysreq = $plugin->system_requirements();

?>

<div class="d4p-info-block">
    <h3>
		<?php _e( "Current Version", "d4plib" ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li><span><?php _e( "Version", "d4plib" ); ?>:</span><strong><?php echo $plugin->version; ?></strong></li>
            <li><span><?php _e( "Build", "d4plib" ); ?>:</span><strong><?php echo $plugin->build; ?></strong></li>
            <li><span><?php _e( "Status", "d4plib" ); ?>:</span><strong><?php echo ucfirst( $plugin->status ); ?></strong></li>
            <li><span><?php _e( "Edition", "d4plib" ); ?>:</span><strong><?php echo ucfirst( $plugin->edition ); ?></strong></li>
            <li><span><?php _e( "Date", "d4plib" ); ?>:</span><strong><?php echo $plugin->updated; ?></strong></li>
        </ul>
        <hr style="margin: 1em 0 .7em; border-top: 1px solid #eee"/>
        <ul class="d4p-info-list">
            <li><span><?php _e( "First released", "d4plib" ); ?>:</span><strong><?php echo $plugin->released; ?></strong></li>
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
