<?php

use function Dev4Press\v42\Functions\panel;

?>
<div class="d4p-whatsnew-section core-grid">
    <div class="core-row">
        <div class="core-col-sm-12 core-col-md-6">
            <h3><?php esc_html_e( "Support plugin development" ); ?></h3>
            <p><?php echo sprintf( __( "%s is a free plugin, and if you find it useful or want to support its future development, please consider donating." ), panel()->a()->settings()->i()->name() ); ?></p>
            <a href="https://www.buymeacoffee.com/millan" target="_blank" rel="noopener">
                <img style="max-width: 320px" alt="BuyMeACoffee" src="<?php echo esc_url( panel()->a()->url . 'd4plib/resources/gfx/buy_me_a_coffee.png' ); ?>"/>
            </a>
        </div>
        <div class="core-col-sm-12 core-col-md-6">
            <h3><?php esc_html_e( "Development repository" ); ?></h3>
        </div>
    </div>
</div>
