<?php


namespace Dev4Press\WordPress\Customizer;


class Control extends \WP_Customize_Control {
    final public function default_value($setting_key = 'default') {
        if (isset($this->settings[$setting_key])) {
            return $this->settings[$setting_key]->default;
        }

        return $this->value($setting_key);
    }
}
