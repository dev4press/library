<?php use Dev4Press\v35\Core\Options\Render; ?>

<div class="d4p-content">
	<?php

	d4p_panel()->settings_fields();

	$subpanel = d4p_panel()->a()->subpanel;
	$class    = d4p_panel()->settings_class;
	$options  = $class::instance();
	$groups   = $options->get( $subpanel );

	Render::instance( d4p_panel()->a()->n(), d4p_panel()->a()->plugin_prefix )->prepare( $subpanel, $groups )->render();

	?>

	<?php d4p_panel()->include_accessibility_control(); ?>
</div>