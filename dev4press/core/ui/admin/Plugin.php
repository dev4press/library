<?php

namespace Dev4Press\Core\UI\Admin;

if (!defined('ABSPATH')) { exit; }

abstract class Plugin {
    public $url = '';
    public $path = '';
    public $plugin = '';
    public $plugin_prefix = '';
    public $menu_cap = 'activate_plugins';

    public $is_debug;

    public $page = false;
    public $panel = false;
    public $task = false;
    public $action = false;

    public $menu_items;
    public $page_ids = array();

    function __construct() {
        add_filter('plugin_action_links', array($this, 'plugin_actions'), 10, 2);
        add_filter('plugin_row_meta', array($this, 'plugin_links'), 10, 2);
    }

    public function var_handler() {
        return $this->plugin_prefix.'_handler';
    }

    public function plugin_name() {
        return $this->plugin.'/'.$this->plugin.'.php';
    }
}
