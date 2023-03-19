<?php

use function Dev4Press\v40\Functions\panel;

$_panel = panel()->object();

?>

<div class="d4p-sidebar">
    <div class="d4p-panel-title">
        <div class="_icon">
			<?php echo panel()->r()->icon( $_panel->icon ); ?>
        </div>
        <h3><?php echo esc_html( $_panel->title ); ?></h3>

        <div class="_info">
			<?php echo esc_html( $_panel->info ); ?>
        </div>
    </div>
</div>
