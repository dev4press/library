<?php

use Dev4Press\v56\Core\Quick\KSES;
use Dev4Press\v56\Core\Quick\Sanitize;
use function Dev4Press\v56\Functions\panel;

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

    if ( ! empty( $obj['class'] ) ) {
        $_classes[] = $obj['class'];
    }

    if ( ! empty( $args['class'] ) ) {
        $_classes[] = $args['class'];
    }

    $url = $obj['url'] ?? panel()->a()->subpanel_url( $subpanel );

    if ( isset( $obj['break'] ) ) {
        echo KSES::standard( panel()->r()->settings_break( $obj['break'], $obj['break-icon'] ?? '', $obj['break-info'] ?? '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    if ( $modd === 'premium' ) {
        $url = panel()->a()->plugin()->fs()->get_upgrade_url();
    }

    ?>

    <div class="<?php echo Sanitize::html_classes( $_classes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>">
        <?php if ( $modd === 'premium' ) { ?>
            <div class="_banner">PRO</div>
        <?php } ?>
        <div class="_info">
            <?php if ( ! empty( $obj['logo'] ) ) { ?>
                <div class="_logo"><img src="<?php echo esc_url( $obj['logo'] ); ?>" alt="<?php echo esc_attr( $obj['title'] ); ?>"/></div>
            <?php } else if ( ! empty( $obj['icon'] ) ) { ?>
                <div class="_icon"><i class="d4p-icon d4p-<?php echo esc_attr( $obj['icon'] ); ?>"></i></div>
            <?php } ?>
            <h4 class="_title"><?php echo esc_html( $obj['title'] ); ?></h4>
            <?php if ( ! empty( $obj['info'] ) ) { ?>
                <p class="_description"><?php echo esc_html( $obj['info'] ); ?></p>
            <?php } ?>
        </div>
        <div class="_ctrl">
            <div class="_activation">
                <?php

                do_action( panel()->h( 'settings_subpanel_activation_div' ), $subpanel );

                ?>
            </div>
            <?php

            if ( $modd === 'premium' ) {
                ?>
                <div class="_purchase">
                    <a href="<?php echo esc_url( $url ); ?>" title="<?php esc_attr_e( 'Buy Pro License', 'd4plib' ); ?>"><i class="d4p-icon d4p-ui-shopping-cart"></i></a>
                </div>
                <?php
            } else {
                do_action( panel()->h( 'settings_subpanel_before_settings_div' ), $subpanel );

                ?>
                <div class="_settings">
                    <a href="<?php echo esc_url( $url ); ?>" title="<?php esc_attr_e( 'Configure', 'd4plib' ); ?>"><i class="d4p-icon d4p-ui-cog"></i></a>
                </div>
                <?php

                do_action( panel()->h( 'settings_subpanel_after_settings_div' ), $subpanel );

            }

            if ( ! empty( $obj['kb']['url'] ) ) {
                ?>
                <div class="_scope">
                    <a target="_blank" rel="noopener" href="<?php echo esc_attr( $obj['kb']['url'] ); ?>" title="<?php esc_attr_e( 'Get more information in the Knowledge Base', 'd4plib' ); ?>"><i class="d4p-icon d4p-ui-book-spells"></i></a>
                </div>
                <?php
            }

            ?>
        </div>
    </div>

    <?php
}
