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

        add_action($this->h('enqueue_scripts'), array($this, 'enqueue_scripts'));
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

    public function a() {
        return $this->admin;
    }

    public function h($hook) {
        return $this->admin->plugin_prefix.'_'.$hook;
    }

    public function enqueue_scripts() { }

    public function screen_options_show() { }

    public function screen_options_save($status, $option, $value) {
        return $status;
    }

    public function show() {
        d4p_print_r($this);
    }
}
