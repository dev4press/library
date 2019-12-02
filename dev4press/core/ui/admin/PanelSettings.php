<?php

namespace Dev4Press\Core\UI\Admin;

class PanelSettings extends Panel {
    protected $form = true;

    public $settings_class = '';

    public function settings_fields() {
        $group = $this->a()->plugin.'-settings';
        $action = $this->a()->v();

        echo "<input type='hidden' name='option_page' value='".esc_attr($group)."' />";
        echo "<input type='hidden' name='action' value='update' />";
        echo "<input type='hidden' name='".$action."' value='postback' />";

        wp_nonce_field($group.'-options');
    }

    public function enqueue_scripts_early() {
        $this->a()->enqueue->js('mark')->js('confirmsubmit');
    }
}
