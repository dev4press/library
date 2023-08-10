<?php

use function Dev4Press\v43\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$label = panel()->a()->wizard()->is_last_panel() ? __( "Finish", "d4plib" ) : __( "Save and Continue", "d4plib" );

?>

<div class="d4p-wrap-wizard">
    <div class="d4p-setup-wizard">
        <div class="d4p-wizard-logo" style="color: <?php echo esc_attr( panel()->a()->settings()->i()->color() ); ?>;">
            <div class="d4p-wizard-badge">
				<?php echo panel()->r()->icon( 'plugin-' . panel()->a()->plugin ); ?>
            </div>
            <div class="d4p-wizard-title">
				<?php echo panel()->a()->title(); ?>
            </div>
        </div>

        <div class="d4p-wizard-panels"><?php

			$step_width = 100 / count( panel()->a()->wizard()->panels );
			$past_class = 'd4p-wizard-step-done';
			foreach ( panel()->a()->wizard()->panels as $w => $obj ) {
				if ( $w == panel()->a()->wizard()->current_panel() ) {
					$past_class = 'd4p-wizard-step-current';
				}

				echo '<div style="width: ' . $step_width . '%" class="d4p-wizard-step d4p-wizard-step-' . $w . ' ' . $past_class . '">' . $obj[ 'label' ] . '</div>';

				if ( $w == panel()->a()->wizard()->current_panel() ) {
					$past_class = '';
				}
			}

			?></div>

        <div class="d4p-wizard-panel">
            <form method="post" action="<?php echo panel()->a()->wizard()->get_form_action(); ?>">
				<?php panel()->a()->wizard()->render_hidden_elements(); ?>

                <div class="d4p-wizard-panel-inner">
					<?php panel()->include_generic( 'content', 'wizard', panel()->a()->wizard()->current_panel() ); ?>

                    <div class="d4p-wizard-panel-footer">
                        <input type="submit" class="button-primary" value="<?php echo $label; ?>"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
