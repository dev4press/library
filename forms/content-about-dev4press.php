<?php

use Dev4Press\API\Store;

if (!defined('ABSPATH')) { exit; }

$_plugins = Store::instance()->plugins();

?>

<div class="d4p-about-dev4press-plugins">
    <?php

    foreach ($_plugins as $plugin) {
        $_url = Store::instance()->url($plugin['code']);

    ?>

    <div class="d4p-dev4press-plugin">
        <div class="_badge" style="background-color: <?php echo $plugin['color']; ?>;">
            <a href="<?php echo $_url; ?>" target="_blank" rel="noopener"><i class="d4p-icon d4p-plugin-icon-<?php echo $plugin['code']; ?>"></i></a>
        </div>
        <div class="_info">
            <h5><a href="<?php echo $_url; ?>" target="_blank" rel="noopener"><?php echo $plugin['name']; ?></a></h5>
            <em><?php echo $plugin['punchline']; ?></em>
            <p><?php echo $plugin['description']; ?></p>
        </div>
    </div>

    <?php } ?>
</div>
