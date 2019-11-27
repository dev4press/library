<?php

namespace Dev4Press\Core\UI\Admin;

if (!defined('ABSPATH')) { exit; }

abstract class Panel {
    /** @var \Dev4Press\Core\Admin\Plugin|\Dev4Press\Core\Admin\Menu\Plugin|\Dev4Press\Core\Admin\Submenu\Plugin */
    private $admin = null;

    public function __construct($admin) {
        $this->admin = $admin;

        add_action('load_'.$this->admin->screen_id, array($this, 'screen_options_show'));
        add_filter('set-screen-option', array($this, 'screen_options_save'), 10, 3);
    }

    /** @return Panel */
    public static function instance($admin) {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class($admin);
        }

        return $instance[$class];
    }

    public function screen_options_show() {

    }

    public function screen_options_save($status, $option, $value) {
        return $status;
    }
}
