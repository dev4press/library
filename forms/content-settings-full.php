<?php use Dev4Press\v35\Core\Options\Render; ?>

<div class="d4p-content">
	<?php

	d4p_panel()->settings_fields();

	$class   = d4p_panel()->settings_class;
	$options = $class::instance();

	foreach ( d4p_panel()->subpanels() as $subpanel => $obj ) {
		if ( $subpanel == 'index' || $subpanel == 'full' ) {
			continue;
		}

		if ( isset( $obj['break'] ) ) {
			echo d4p_panel()->r()->settings_break( $obj['break'], $obj['break-icon'] );
		}

		echo d4p_panel()->r()->settings_group_break( $obj['title'], $obj['icon'] );

		$groups = $options->get( $subpanel );

		Render::instance( d4p_panel()->a()->n(), d4p_panel()->a()->plugin_prefix )->prepare( $subpanel, $groups )->render();
	}

	?>

	<?php d4p_panel()->include_accessibility_control(); ?>
</div>