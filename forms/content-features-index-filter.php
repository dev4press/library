<?php

use function Dev4Press\v39\Functions\panel;

$buttons = panel()->get_filter_buttons();

?>

<div class="d4p-features-filter">
    <div class="d4p-features-filter-buttons">
		<?php

		foreach ( $buttons as $code => $button ) {
			$class = ( $button['default'] ?? false ) ? 'is-selected' : '';
			echo '<button class="' . $class . '" data-selector="' . $button['selector'] . '" data-filter="' . $code . '" type="button">' . $button['label'] . '</button>';
		}

		?>
    </div>
</div>
