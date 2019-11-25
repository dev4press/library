<?php

namespace Dev4Press\Core\Admin;

if (!defined('ABSPATH')) { exit; }

abstract class Plugin {
    public $menu_cap = 'activate_plugins';

    public $plugin_prefix = '';
    public $plugin = '';

    public $url = '';
    public $path = '';

    public $is_debug = false;

    public $page = false;
    public $panel = false;
    public $task = false;
    public $action = false;

    public $page_ids = array();

    function __construct() {
        $this->consturctor();

        add_filter('plugin_action_links', array($this, 'plugin_actions'), 10, 2);
        add_filter('plugin_row_meta', array($this, 'plugin_links'), 10, 2);

        add_action('plugins_loaded', array($this, 'plugins_loaded'));
    }

    /** @return \Dev4Press\Core\Admin\Plugin|\Dev4Press\Core\Admin\Submenu\Plugin|\Dev4Press\Core\Admin\Menu\Plugin */
    public static function instance() {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class();
        }

        return $instance[$class];
    }

    public function screen() {
        return get_current_screen();
    }

    public function plugin_actions($links, $file) {
        if ($file == $this->plugin_name()) {
            $settings_link = '<a href="'.$this->current_url(false, false).'">'.__("Settings", "d4plib").'</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    public function plugin_links($links, $file) {
        if ($file == $this->plugin_name()) {
            $links[] = '<a target="_blank" href="https://support.dev4press.com/kb/product/'.$this->plugin.'/">'.__("Knowledge Base", "d4plib").'</a>';
            $links[] = '<a target="_blank" href="https://support.dev4press.com/forums/forum/plugins/'.$this->plugin.'/">'.__("Support Forum", "d4plib").'</a>';
        }

        return $links;
    }

    public function plugins_loaded() {
        $this->is_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;

        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));

        add_action('current_screen', array($this, 'current_screen'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function var_handler() {
        return $this->plugin_prefix.'_handler';
    }

    public function plugin_name() {
        return $this->plugin.'/'.$this->plugin.'.php';
    }

    public function install_notice() {
        if (current_user_can('install_plugins') && $this->page === false) {
            echo '<div class="updated"><p>';
            echo sprintf(__("%s is activated and it needs to finish installation.", "d4plib"), $this->title());
            echo ' <a href="'.$this->main_url().'">'.__("Click Here", "d4plib").'</a>.';
            echo '</p></div>';
        }
    }

    public function update_notice() {
        if (current_user_can('install_plugins') && $this->page === false) {
            echo '<div class="updated"><p>';
            echo sprintf(__("%s is updated, and you need to review the update process.", "d4plib"), $this->title());
            echo ' <a href="'.$this->plugin.'">'.__("Click Here", "d4plib").'</a>.';
            echo '</p></div>';
        }
    }

    public function file($type, $name, $min = true, $base_url = null) {
        $get = is_null($base_url) ? $this->url : $base_url;

        $get.= $type.'/'.$name;

        if (!$this->is_debug && $min) {
            $get.= '.min';
        }

        $get.= '.'.$type;

        return $get;
    }

    public function admin_load_hooks() {
        foreach ($this->page_ids as $id) {
            add_action('load-'.$id, array($this, 'load_admin_page'));
        }
    }

    public function load_admin_page() {

    }

    protected function load_postget_back() {
        if (isset($_POST[$this->var_handler()]) && $_POST[$this->var_handler()] == 'postback') {
            $this->run_postback();
        } else if (isset($_GET[$this->var_handler()]) && $_GET[$this->var_handler()] == 'getback') {
            $this->run_getback();
        }
    }

    abstract public function consturctor();
    abstract public function run_getback();
    abstract public function run_postback();
    abstract public function main_url();
    abstract public function current_url($with_panel = true, $with_task = true);
}
