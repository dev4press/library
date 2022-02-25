<?php

use function Dev4Press\v37\Functions\panel;

?>
<div class="d4p-sidebar">
    <div class="d4p-dashboard-badge" style="background-color: <?php echo esc_attr( panel()->a()->settings()->i()->color() ); ?>;">
        <div class="_icon">
			<?php echo panel()->r()->icon( 'plugin-' . panel()->a()->plugin, '9x' ); ?>
        </div>
        <h3>
			<?php echo panel()->a()->title(); ?>
        </h3>
        <div class="_version-wrapper">
            <span class="_edition"><?php echo ucfirst( panel()->a()->settings()->i()->edition ); ?></span>
            <span class="_version"><?php printf( __( "Version: %s", "d4plib" ), '<strong>' . panel()->a()->settings()->i()->version_full() . '</strong>' ); ?></span>
        </div>
    </div>

	<?php

	foreach ( panel()->sidebar_links as $group ) {
		if ( ! empty( $group ) ) {
			echo '<div class="d4p-links-group">';

			foreach ( $group as $link ) {
				echo '<a class="' . esc_attr( $link['class'] ) . '" href="' . esc_url( $link['url'] ) . '">' . panel()->r()->icon( $link['icon'] ) . '<span>' . $link['label'] . '</span></a>';
			}

			echo '</div>';
		}
	}

	?>
</div>
