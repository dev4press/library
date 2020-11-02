<?php

use Dev4Press\API\Store;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_plugins = Store::instance()->plugins();

?>

<div class="d4p-about-dev4press-plugins">
	<?php

	foreach ( $_plugins as $plugin ) {
		$_url     = Store::instance()->url( $plugin['code'] );
		$_pro     = Store::instance()->is_pro( $plugin['code'] );
		$_free    = Store::instance()->is_free( $plugin['code'] );
		$_edition = $_pro && $_free ? '_both' : '_single';

		?>

        <div class="d4p-dev4press-plugin">
            <div class="_badge">
                <div class="_icon" style="background-color: <?php echo $plugin['color']; ?>;">
                    <a href="<?php echo $_url; ?>" target="_blank" rel="noopener"><i class="d4p-icon d4p-plugin-<?php echo $plugin['code']; ?>"></i></a>
                </div>
                <div class="_edition <?php echo $_edition; ?>"><?php if ( $_pro ) {
						echo '<span class="_pro">Pro</span>';
					} ?><?php if ( $_free ) {
						echo '<span class="_free">Free</span>';
					} ?></div>
            </div>
            <div class="_info">
                <h5><a href="<?php echo $_url; ?>" target="_blank" rel="noopener"><?php echo $plugin['name']; ?></a>
                </h5>
                <em><?php echo $plugin['punchline']; ?></em>
                <p><?php echo $plugin['description']; ?></p>
            </div>
        </div>

	<?php } ?>
</div>
