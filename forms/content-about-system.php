<?php

use Dev4Press\v55\Library;
use Dev4Press\v55\WordPress;
use function Dev4Press\v55\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="d4p-info-block">
    <h3>
        <?php esc_html_e( 'System Information', 'd4plib' ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'PHP Version', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::i()->php_version() ); ?></strong>
            </li>
            <li>
                <span><?php

                    /* translators: About System information. %s: CMS name. */
                    echo sprintf( esc_html__( '%s Version', 'd4plib' ), esc_html( WordPress::i()->cms_title() ) );

                    ?>:</span><strong><?php echo esc_html( WordPress::i()->version() ); ?></strong>
            </li>
        </ul>
        <hr/>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'Debug Mode', 'd4plib' ); ?>:</span><strong><?php echo WordPress::i()->is_debug() ? esc_html__( 'ON', 'd4plib' ) : esc_html__( 'OFF', 'd4plib' ); ?></strong>
            </li>
            <li>
                <span><?php esc_html_e( 'Script Debug', 'd4plib' ); ?>:</span><strong><?php echo WordPress::i()->is_script_debug() ? esc_html__( 'ON', 'd4plib' ) : esc_html__( 'OFF', 'd4plib' ); ?></strong>
            </li>
        </ul>
    </div>
</div>

<div class="d4p-info-block">
    <h3>
        <?php esc_html_e( 'Plugin Information', 'd4plib' ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'Path', 'd4plib' ); ?>:</span><strong><?php echo esc_html( panel()->a()->path ); ?></strong>
            </li>
            <li>
                <span><?php esc_html_e( 'URL', 'd4plib' ); ?>:</span><strong><?php echo esc_html( panel()->a()->url ); ?></strong>
            </li>
        </ul>
    </div>
</div>


<div class="d4p-info-block">
    <h3>
        <?php esc_html_e( 'Shared Library', 'd4plib' ); ?>
    </h3>
    <div>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'Version', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::i()->version() ); ?></strong>
            </li>
            <li>
                <span><?php esc_html_e( 'Build', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::i()->build() ); ?></strong>
            </li>
        </ul>
        <hr/>
        <ul class="d4p-info-list">
            <li>
                <span><?php esc_html_e( 'Path', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::i()->path() ); ?></strong>
            </li>
            <li>
                <span><?php esc_html_e( 'URL', 'd4plib' ); ?>:</span><strong><?php echo esc_html( Library::i()->url() ); ?></strong>
            </li>
        </ul>
    </div>
</div>
