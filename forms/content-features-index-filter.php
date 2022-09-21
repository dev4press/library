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
    <div class="d4p-features-filter-search">
        <input placeholder="<?php esc_html_e("Search..."); ?>" type="text"/><i class="d4p-icon d4p-ui-clear"></i>
    </div>
</div>
