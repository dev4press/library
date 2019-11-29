<?php

namespace Dev4Press\Core\UI\Admin;

if (!defined('ABSPATH')) { exit; }

abstract class Panel {
    static private $_current_instance = null;

    /** @var \Dev4Press\Core\Admin\Plugin|\Dev4Press\Core\Admin\Menu\Plugin|\Dev4Press\Core\Admin\Submenu\Plugin */
    private $admin = null;

    protected $templates = 'standard';
    protected $sidebar = true;
    protected $subpanels = array();

    public function __construct($admin) {
        $this->admin = $admin;

        add_action('load_'.$this->admin->screen_id, array($this, 'screen_options_show'));
        add_filter('set-screen-option', array($this, 'screen_options_save'), 10, 3);

        add_action($this->h('enqueue_scripts'), array($this, 'enqueue_scripts'));
    }

    /** @return Panel */
    public static function instance($admin = null) {
        $class = get_called_class();

        if (is_null(self::$_current_instance) && !is_null($admin)) {
            self::$_current_instance = new $class($admin);
        }

        return self::$_current_instance;
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
        $this->include_header();

        $this->include_footer();
    }

    protected function forms_path() {
        return $this->admin->path.'d4plib/forms/';
    }

    protected function include_header($name = '') {
        $name = empty($name) ? $this->templates : $name;
        include($this->forms_path().'header-'.$name.'.php');
    }

    protected function include_footer($name = '') {
        $name = empty($name) ? $this->templates : $name;
        include($this->forms_path().'footer-'.$name.'.php');
    }
}
