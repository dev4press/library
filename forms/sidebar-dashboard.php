<?php

use function Dev4Press\v36\Functions\panel;

?>
<div class="d4p-sidebar">
    <div class="d4p-dashboard-badge" style="background-color: <?php echo panel()->a()->settings()->i()->color(); ?>;">
		<?php echo panel()->r()->icon( 'plugin-' . panel()->a()->plugin, '9x' ); ?>
        <h3>
			<?php echo panel()->a()->title(); ?>
        </h3>
        <h5>
			<?php printf( __( "Version: %s", "d4plib" ), panel()->a()->settings()->i()->version_full() ); ?>
        </h5>
    </div>

	<?php

	foreach ( panel()->sidebar_links as $group ) {
		if ( ! empty( $group ) ) {
			echo '<div class="d4p-links-group">';

			foreach ( $group as $link ) {
				echo '<a class="' . $link['class'] . '" href="' . $link['url'] . '">' . panel()->r()->icon( $link['icon'] ) . '<span>' . $link['label'] . '</span></a>';
			}

			echo '</div>';
		}
	}

	?>
</div>
