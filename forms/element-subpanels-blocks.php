<?php

use Dev4Press\v53\Core\Quick\KSES;
use Dev4Press\v53\Core\Quick\Sanitize;
use function Dev4Press\v53\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

foreach ( panel()->subpanels() as $subpanel => $obj ) {
	if ( $subpanel == 'index' || $subpanel == 'full' ) {
		continue;
	}

	$modd = $obj['modd'] ?? 'regular';

	if ( panel()->a()->plugin()->license === false ) {
		$modd = 'regular';
	}

	$_classes = array(
		'd4p-feature-box',
		'settings-' . $subpanel,
		'd4p-box-modd-' . $modd,
	);

	if ( ! empty( $args['class'] ) ) {
		$_classes[] = $args['class'];
	}

	$url = $obj['url'] ?? panel()->a()->subpanel_url( $subpanel );

	if ( isset( $obj['break'] ) ) {
		echo KSES::standard( panel()->r()->settings_break( $obj['break'], $obj['break-icon'] ?? '', $obj['break-info'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( $modd === 'premium' ) {
		$pro = panel()->a()->plugin()->fs()->get_upgrade_url();
	}

	?>

    <div class="<?php echo Sanitize::html_classes( $_classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
		<?php if ( $modd === 'premium' ) { ?>
            <div class="_banner">PRO</div>
		<?php } ?>
        <div class="_info">
            <div class="_icon"><i class="d4p-icon d4p-<?php echo esc_attr( $obj['icon'] ); ?>"></i></div>
            <h4 class="_title"><?php echo esc_html( $obj['title'] ); ?></h4>
            <p class="_description"><?php echo esc_html( $obj['info'] ); ?></p>
        </div>
        <div class="_ctrl">
            <div class="_open">
				<?php if ( $modd === 'premium' ) { ?>
                    <a class="button-primary" href="<?php echo esc_url( $pro ); ?>"><?php esc_html_e( 'Buy Pro License', 'd4plib' ); ?></a>
				<?php } else { ?>
                    <a class="button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Open', 'd4plib' ); ?></a>
				<?php } ?>
            </div>
        </div>
    </div>

	<?php
}
