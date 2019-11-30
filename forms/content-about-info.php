<?php

$plugin = d4p_panel()->a()->settings()->i();
$sysreq = $plugin->system_requirements();

?>

<div class="d4p-info-block">
    <h3>
        <?php _e("Current Version"); ?>
    </h3>
    <div>
        <ul>
            <li><?php _e("Version", "gd-topic-polls"); ?>: <strong><?php echo $plugin->version; ?></strong></li>
            <li><?php _e("Status", "gd-topic-polls"); ?>: <strong><?php echo ucfirst($plugin->status); ?></strong></li>
            <li><?php _e("Edition", "gd-topic-polls"); ?>: <strong><?php echo ucfirst($plugin->edition); ?></strong></li>
            <li><?php _e("Build", "gd-topic-polls"); ?>: <strong><?php echo $plugin->build; ?></strong></li>
            <li><?php _e("Date", "gd-topic-polls"); ?>: <strong><?php echo $plugin->updated; ?></strong></li>
        </ul>
        <hr style="margin: 1em 0 .7em; border-top: 1px solid #eee"/>
        <ul>
            <li><?php _e("First released", "gd-topic-polls"); ?>: <strong><?php echo $plugin->released; ?></strong></li>
        </ul>
    </div>
</div>

<div class="d4p-info-block">
    <h3>
        <?php _e("System Requirements"); ?>
    </h3>
    <div>
        <ul>
            <?php

            foreach ($sysreq as $name => $version) {
                echo '<li>'.$name.': <strong>'.$version.'</strong></li>';
            }

            ?>
        </ul>
    </div>
</div>
