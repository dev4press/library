<?php $_panel = d4p_panel()->a()->panel_object(); ?>
<div class="d4p-sidebar">
    <div class="d4p-panel-title">
        <?php echo d4p_panel()->r()->icon($_panel->icon); ?>
        <h3><?php echo $_panel->title; ?></h3>
    </div>
    <div class="d4p-panel-info">
        <?php echo $_panel->info; ?>
    </div>
</div>
