<?php

use function Dev4Press\v56\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$runner = panel()->get_runner_object();

?>
<div class="d4p-background-job-messages-wrapper">
    <div class="d4p-background-job-messages">
        <?php echo $runner->render_messages(); ?>
    </div>
    <?php if ( ! in_array( $runner->current_status(), array( 'empty', 'done' ) ) ) { ?>
        <div class="d4p-background-job-loader"
                data-code="<?php echo esc_attr( $runner->get_action_code('update') ); ?>"
                data-nonce="<?php echo esc_attr( wp_create_nonce( $runner->get_action_nonce('update') ) ); ?>">
            <i class="d4p-icon d4p-ui-spinner d4p-icon-spin d4p-icon-fw"></i> <?php esc_html_e( 'Checking the process update...', 'd4plib' ); ?>
        </div>
    <?php } ?>
</div>
