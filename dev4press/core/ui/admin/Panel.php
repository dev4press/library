<?php

namespace Dev4Press\Core\UI\Admin;

if (!defined('ABSPATH')) { exit; }

abstract class Panel {
    static private $_current_instance = null;

    /** @var \Dev4Press\Core\Admin\Plugin|\Dev4Press\Core\Admin\Menu\Plugin|\Dev4Press\Core\Admin\Submenu\Plugin */
    private $admin = null;

    /** @var \Dev4Press\Core\UI\Admin\Render */
    private $render = null;

    protected $templates = 'standard';
    protected $sidebar = true;
    protected $subpanels = array();
    protected $render_class = '\\Dev4Press\\Core\\UI\\Admin\\Render';

    public function __construct($admin) {
        $this->admin = $admin;

        $render = $this->render_class;
        $this->render = $render::instance();

        add_action('load_'.$this->a()->screen_id, array($this, 'screen_options_show'));
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

    public function r() {
        return $this->render;
    }

    public function h($hook) {
        return $this->a()->plugin_prefix.'_'.$hook;
    }

    public function subpanels() {
        return $this->subpanels;
    }

    public function has_sidebar() {
        return $this->sidebar;
    }

    public function enqueue_scripts() { }

    public function screen_options_show() { }

    public function screen_options_save($status, $option, $value) {
        return $status;
    }

    public function show() {
        $this->include_header();

        echo '<div class="d4p-inside-wrapper">';
            if ($this->has_sidebar()) {
                $this->include_sidebar();
            }

            $this->include_content();
        echo '</div>';

        $this->include_footer();
    }

    public function forms_path_library() {
        return $this->a()->path.'d4plib/forms/';
    }

    public function forms_path_plugin() {
        return $this->a()->path.'forms/';
    }

    public function include_header($name = '') {
        $name = empty($name) ? $this->templates : $name;
        $this->load('header-'.$name.'.php');
    }

    public function include_footer($name = '') {
        $name = empty($name) ? $this->templates : $name;
        $this->load('footer-'.$name.'.php');
    }

    public function include_sidebar($name = '') {
        $name = empty($name) ? ($this->a()->panel == 'dashboard' ? 'dashboard' : $this->templates) : $name;
        $this->load('sidebar-'.$name.'.php');
    }

    public function include_messages() {
        $this->load('message.php');
    }

    public function include_content() {
        $name = 'content-'.$this->a()->panel;

        if (!empty($this->a()->subpanel)) {
            $name.= '-'.$this->a()->subpanel;
        }

        $name.= '.php';

        $this->load($name);
    }

    protected function load($name, $fallback = 'fallback.php') {
        if (file_exists($this->forms_path_plugin().$name)) {
            include($this->forms_path_plugin().$name);
        } else {
            if (file_exists($this->forms_path_library().$name)) {
                include($this->forms_path_library().$name);
            } else {
                include($this->forms_path_library().$fallback);
            }
        }
    }
}
