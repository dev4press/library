<?php

use function Dev4Press\v55\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="d4p-content">
    <div class="d4p-features-wrapper">
        <?php

        panel()->include_element( 'subpanels', 'settings', array( 'class' => '_is-settings' ) );

        ?>
    </div>
</div>
