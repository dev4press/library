<?php

use Dev4Press\v56\API\Languages;
use function Dev4Press\v56\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$_plugin  = panel()->a()->settings()->i();
$_sys_req = $_plugin->system_requirements();

?>

    <div class="d4p-info-block">
        <h3>
            <?php esc_html_e( 'Current Version', 'd4plib' ); ?>
        </h3>
        <div>
            <ul class="d4p-info-list">
                <li>
                    <span><?php esc_html_e( 'Version', 'd4plib' ); ?>:</span><strong><?php echo esc_html( $_plugin->version ); ?></strong>
                </li>
                <li>
                    <span><?php esc_html_e( 'Build', 'd4plib' ); ?>:</span><strong><?php echo esc_html( $_plugin->build ); ?></strong>
                </li>
                <li>
                    <span><?php esc_html_e( 'Status', 'd4plib' ); ?>:</span><strong><?php echo esc_html( ucfirst( $_plugin->status ) ); ?></strong>
                </li>
                <li>
                    <span><?php esc_html_e( 'Edition', 'd4plib' ); ?>:</span><strong><?php echo esc_html( ucfirst( $_plugin->get_edition() ) ); ?></strong>
                </li>
                <li>
                    <span><?php esc_html_e( 'Date', 'd4plib' ); ?>:</span><strong><?php echo esc_html( $_plugin->updated ); ?></strong>
                </li>
            </ul>
            <hr style="margin: 1em 0 .7em; border-top: 1px solid #eee"/>
            <ul class="d4p-info-list">
                <li>
                    <span><?php esc_html_e( 'First released', 'd4plib' ); ?>:</span><strong><?php echo esc_html( $_plugin->released ); ?></strong>
                </li>
            </ul>
        </div>
    </div>

    <div class="d4p-info-block">
        <h3>
            <?php esc_html_e( 'System Requirements', 'd4plib' ); ?>
        </h3>
        <div>
            <ul class="d4p-info-list">
                <?php

                foreach ( $_sys_req as $name => $version ) {
                    echo '<li><span>' . esc_html( $name ) . ':</span><strong>' . esc_html( $version ) . '</strong></li>';
                }

                ?>
            </ul>
        </div>
    </div>
