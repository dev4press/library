<?php

use function Dev4Press\v55\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$_scopes = array(
        'global' => array(
                'label' => __( 'Global', 'd4plib' ),
                'icon'  => 'd4p-ui-globe',
        ),
        'front'  => array(
                'label' => __( 'Frontend', 'd4plib' ),
                'icon'  => 'd4p-ui-desktop',
        ),
        'admin'  => array(
                'label' => __( 'Admin', 'd4plib' ),
                'icon'  => 'd4p-ui-dashboard',
        ),
);

?>
<div class="d4p-content">
    <?php require 'content-features-index-filter.php'; ?>

    <div class="d4p-features-wrapper">
        <?php

        panel()->include_element( 'subpanels', 'features' );

        ?>
    </div>
</div>
