<?php

namespace Dev4Press\Core\UI\Admin;

class PanelTools extends Panel {
    public function __construct($admin) {
        parent::__construct($admin);

        $this->subpanels = array(
            'index' => array(
                'title' => __("Tools Index", "gd-topic-polls"), 'icon' => 'ui-cog',
                'method' => '', 'button_label' => '',
                'info' => __("This panel links all the plugin tools, and you access each starting from the right.", "gd-topic-polls")),
            'updater' => array(
                'title' => __("Recheck and Update", "gd-topic-polls"), 'icon' => 'ui-sync',
                'break' => __("Maintenance", "gd-topic-polls"), 'break-icon' => 'ui-chart-bar',
                'method' => '', 'button_label' => '',
                'info' => __("Run the update procedure and recheck plugin setup.", "gd-topic-polls")),
            'export' => array(
                'title' => __("Export Settings", "gd-topic-polls"), 'icon' => 'ui-download',
                'method' => 'get', 'button_label' => __("Export", "gd-topic-polls"),
                'button_url' => $this->a()->export_url(),
                'info' => __("Export all plugin settings into file.", "gd-topic-polls")),
            'import' => array(
                'title' => __("Import Settings", "gd-topic-polls"), 'icon' => 'ui-upload',
                'method' => 'post', 'button_label' => __("Import", "gd-topic-polls"),
                'info' => __("Import all plugin settings from export file.", "gd-topic-polls")),
            'remove' => array(
                'title' => __("Reset / Remove", "gd-topic-polls"), 'icon' => 'ui-times',
                'method' => 'post', 'button_label' => __("Remove", "gd-topic-polls"),
                'info' => __("Remove selected plugin settings and optionally disable plugin.", "gd-topic-polls"))
        );
    }

    public function prepare() {
        $_subpanel = $this->a()->subpanel;

        if (isset($this->subpanels[$_subpanel])) {
            $method = $this->subpanels[$_subpanel]['method'];

            if ($method == 'post') {
                $this->form = true;
            }
        }
    }

    public function settings_fields() {
        $group = $this->a()->plugin.'-tools';
        $action = $this->a()->v();

        echo "<input type='hidden' name='option_page' value='".esc_attr($group)."' />";
        echo "<input type='hidden' name='".$action."' value='postback' />";
        echo "<input type='hidden' name='".d4p_panel()->a()->n()."[subpanel]' value='".$this->a()->subpanel."' />";

        wp_nonce_field($group.'-options');
    }

    public function enqueue_scripts_early() {

    }
}
