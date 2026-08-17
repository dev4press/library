<?php

use Dev4Press\v56\Library;
use function Dev4Press\v56\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$runner = panel()->get_runner_object();

$_status = $runner->current_status();
$_info   = $runner->current_info();
$_done   = $runner->current_completed();
$_list   = $runner->list_statuses();
$_tabs   = $runner->get_tabs();

?>

<div class="d4p-cards-wrapper">
    <div class="d4p-group d4p-dashboard-card d4p-card-double">
        <h3><?php esc_html_e( 'Scanner Status', 'd4plib' ); ?></h3>
        <div class="d4p-group-inner">
            <div class="d4p-group-elements d4p-regular-flow">
                <div class="d4p-group-element-full">
                    <span><?php esc_html_e( 'Scanner Status', 'd4plib' ); ?>:</span>
                    <?php panel()->status_badge( $_list[ $_status ]['label'], $_list[ $_status ]['color'] ); ?>
                </div>
                <?php

                if ( $runner->current_status() === 'empty' ) {
                    ?>
                    <div class="d4p-group-element-wide">
                        <?php esc_html_e( 'Scanner has not been initialized yet, or the previous scan data has been purged. Click the button bellow to run the Scanner.', 'd4plib' ); ?>
                    </div>
                    <?php
                } else {
                    ?>

                    <div class="d4p-group-element">
                        <span><?php esc_html_e( 'Started', 'd4plib' ); ?>:</span>
                        <strong><?php echo esc_html( Library::i()->datetime()->mysql_date( true, absint( $_info['started'] ) ) ); ?></strong>
                    </div>
                    <div class="d4p-group-element">
                        <span><?php esc_html_e( 'Ended', 'd4plib' ); ?>:</span>
                        <strong><?php echo $_info['ended'] == 0 ? '/' : esc_html( Library::i()->datetime()->mysql_date( true, absint( $_info['ended'] ) ) ); ?></strong>
                    </div>
                    <div class="d4p-group-element">
                        <span><?php echo $runner->get_scope() == 'items' ? esc_html__( 'Total Items', 'd4plib' ) : esc_html__( 'Total Tasks', 'd4plib' ); ?>:</span>
                        <strong class="d4p-background-job-total"><?php echo esc_html( $_done['total'] ); ?></strong>
                    </div>
                    <div class="d4p-group-element">
                        <span><?php echo $runner->get_scope() == 'items' ? esc_html__( 'Completed Items', 'd4plib' ) : esc_html__( 'Completed Tasks', 'd4plib' ); ?>:</span>
                        <strong class="d4p-background-job-done"><?php echo esc_html( $_done['done'] . ' (' . $_done['percentage'] . '%)' ); ?></strong>
                    </div>

                    <?php
                }

                ?>
            </div>
        </div>
        <div class="d4p-group-footer">
            <?php

            if ( $_status === 'working' ) {
                panel()->action_button( __( 'Abort the Scan', 'd4plib' ), array( 'abort', $runner->get_action_nonce( 'abort' ) ) );
            } else if ( in_array( $_status, array( 'empty', 'done', 'abort' ) ) ) {
                panel()->action_button( __( 'Reset the Scanner and Run Again', 'd4plib' ), array( 'run', $runner->get_action_nonce( 'run' ) ) );
                panel()->action_button( __( 'Remove Scanner Results', 'd4plib' ), array( 'clear', $runner->get_action_nonce( 'clear' ) ), false );
            }

            ?>
        </div>
    </div>

    <?php if ( ! empty( $_tabs ) ) { ?>
        <div class="d4p-group d4p-dashboard-card d4p-card-double d4p-group-with-tabs d4p-ctrl-tabs <?php echo $runner->get_name(); ?>-ctrl-tabs">
            <div class="d4p-header-tabs">
                <div role="tablist" aria-label="<?php esc_html_e( 'Group Tabs', 'd4plib' ); ?>">
                    <?php

                    $_selected = true;
                    foreach ( $_tabs as $_tab => $obj ) {
                        $label = $obj['label'];
                        $ctrl  = $runner->get_name() . '-scanner-tabs-' . $_tab;
                        $class = 'd4p-ctrl-tab d4p-ctrl-tab-' . $runner->get_name() . '-scanner-tabs-' . $_tab . ( $_selected ? ' d4p-ctrl-tab-is-active' : '' );

                        ?>
                        <button type="button" role="tab"
                                id="<?php echo esc_attr( $runner->get_name() . '-scanner-tabs-' . $_tab ); ?>-tab"
                                aria-controls="<?php echo esc_attr( $ctrl ); ?>"
                                aria-selected="<?php echo $_selected ? 'true' : 'false'; ?>"
                                data-tabname="<?php echo esc_attr( $_tab ); ?>" class="<?php echo esc_attr( $class ); ?>">
                            <?php echo panel()->r()->icon( $obj['icon'], 'fw' ); ?>
                            <span><?php echo esc_html( $obj['label'] ); ?></span>
                        </button>
                        <?php

                        $_selected = false;
                    }

                    ?>
                </div>
            </div>
            <div class="d4p-group-inner">
                <?php

                $_selected = true;
                foreach ( $_tabs as $_tab => $args ) {
                    $include = empty( $args['include'] ) ? '' : $args['include'];
                    $class   = 'd4p-ctrl-tabs-content d4p-ctrl-tab-' . $runner->get_name() . '-scanner-tabs-' . $_tab . ( $_selected ? ' d4p-ctrl-tabs-content-active' : '' );

                    ?>
                    <div role="tabpanel"
                            id="<?php echo esc_attr( $runner->get_name() . '-scanner-tabs-' . $_tab ); ?>"
                            aria-hidden="<?php echo $_selected ? 'false' : 'true'; ?>"
                            aria-labelledby="<?php echo esc_attr( $runner->get_name() . '-scanner-tabs-' . $_tab ); ?>-tab"
                            class="<?php echo esc_attr( $class ); ?>" <?php echo $_selected ? '' : 'hidden'; ?>>
                        <?php

                        if ( empty( $args['include'] ) ) {
                            panel()->include_runner( $_tab );
                        } else {
                            include $args['include'];
                        }

                        ?>
                    </div>
                    <?php

                    $_selected = false;
                }

                ?>
            </div>
        </div>
    <?php } ?>

</div>
