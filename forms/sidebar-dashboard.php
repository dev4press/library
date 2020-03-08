<div class="d4p-sidebar">
    <div class="d4p-dashboard-badge" style="background-color: <?php echo d4p_panel()->a()->settings()->i()->color(); ?>;">
        <?php echo d4p_panel()->r()->icon('plugin-icon-'.d4p_panel()->a()->plugin, '9x'); ?>
        <h3>
            <?php echo d4p_panel()->a()->title(); ?>
        </h3>
        <h5>
            <?php printf(__("Version: %s", "d4plib"), d4p_panel()->a()->settings()->i()->version_full()); ?>
        </h5>
    </div>

    <?php

    foreach (d4p_panel()->sidebar_links as $group) {
        if (!empty($group)) {
            echo '<div class="d4p-links-group">';

            foreach ($group as $link) {
                echo '<a class="'.$link['class'].'" href="'.$link['url'].'">'.d4p_panel()->r()->icon($link['icon']).'<span>'.$link['label'].'</span></a>';
            }

            echo '</div>';
        }
    }

    ?>
</div>
