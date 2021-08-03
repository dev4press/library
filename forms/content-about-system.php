<?php

use Dev4Press\v36\Library;
use Dev4Press\v36\WordPress;
use function Dev4Press\v36\Functions\panel;

?>
<div class="d4p-info-block">
    <h3>
		<?php _e( "System Information", "d4plib" ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php _e( "PHP Version", "d4plib" ); ?>:</span><strong><?php echo Library::instance()->php_version(); ?></strong>
            </li>
            <li>
                <span><?php echo sprintf( __( "%s Version", "d4plib" ), WordPress::instance()->cms_title() ); ?>:</span><strong><?php echo WordPress::instance()->version(); ?></strong>
            </li>
        </ul>
        <hr/>
        <ul class="d4p-info-list">
            <li>
                <span><?php _e( "Debug Mode", "d4plib" ); ?>:</span><strong><?php echo WordPress::instance()->is_debug() ? __( "ON", "d4plib" ) : __( "OFF", "d4plib" ); ?></strong>
            </li>
            <li>
                <span><?php _e( "Script Debug", "d4plib" ); ?>:</span><strong><?php echo WordPress::instance()->is_script_debug() ? __( "ON", "d4plib" ) : __( "OFF", "d4plib" ); ?></strong>
            </li>
        </ul>
    </div>
</div>

<div class="d4p-info-block">
    <h3>
		<?php _e( "Plugin Information", "d4plib" ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php _e( "Path", "d4plib" ); ?>:</span><strong><?php echo panel()->a()->path; ?></strong>
            </li>
            <li>
                <span><?php _e( "URL", "d4plib" ); ?>:</span><strong><?php echo panel()->a()->url; ?></strong>
            </li>
        </ul>
    </div>
</div>


<div class="d4p-info-block">
    <h3>
		<?php _e( "Shared Library", "d4plib" ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php _e( "Version", "d4plib" ); ?>:</span><strong><?php echo Library::instance()->version(); ?></strong>
            </li>
            <li>
                <span><?php _e( "Build", "d4plib" ); ?>:</span><strong><?php echo Library::instance()->build(); ?></strong>
            </li>
        </ul>
        <hr/>
        <ul class="d4p-info-list">
            <li>
                <span><?php _e( "Path", "d4plib" ); ?>:</span><strong><?php echo Library::instance()->path(); ?></strong>
            </li>
            <li>
                <span><?php _e( "URL", "d4plib" ); ?>:</span><strong><?php echo Library::instance()->url(); ?></strong>
            </li>
        </ul>
    </div>
</div>
