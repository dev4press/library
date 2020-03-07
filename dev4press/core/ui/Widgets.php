<?php

namespace Dev4Press\Core\UI;

if (!defined('ABSPATH')) {
    exit;
}
final class Widgets {
    /** @var \Dev4Press\Core\Admin\Plugin|\Dev4Press\Core\Admin\Menu\Plugin|\Dev4Press\Core\Admin\Submenu\Plugin */
    private $_admin;

    public function __construct($admin) {
        $this->_admin = $admin;
    }

    /** @return \Dev4Press\Core\UI\Widgets */
    public static function instance($widget, $admin) {
        static $_d4p_widgets_loader = array();

        if (!isset($_d4p_widgets_loader[$widget])) {
            $_d4p_widgets_loader[$widget] = new Widgets($admin);
        }

        return $_d4p_widgets_loader[$widget];
    }

    public function a() {
        return $this->_admin;
    }

    public function forms_path_library() {
        return $this->a()->path.'d4plib/forms/';
    }

    public function forms_path_plugin() {
        return $this->a()->path.'forms/widgets/';
    }

    public function find($name, $fallback = 'fallback.php') {
        if (file_exists($this->forms_path_plugin().$name)) {
            return $this->forms_path_plugin().$name;
        } else {
            if (file_exists($this->forms_path_library().$name)) {
                return $this->forms_path_library().$name;
            } else {
                return $this->forms_path_library().$fallback;
            }
        }
    }

    public function load($name, $fallback = 'fallback.php') {
        include($this->find($name, $fallback));
    }
}
