<?php

/*
Name:    Dev4Press\Core\Plugins\Core
Version: v3.1
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace Dev4Press\Core\Plugins;

use Dev4Press\API\Four;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Core {
    public $widgets = array();
    public $enqueue = false;
    public $cap = 'activate_plugins';
    public $svg_icon = '';
    public $plugin = '';
    public $url = '';

    public $is_debug;
    public $cms = 'wordpress';
    public $cms_version;
    public $wp_version;

    private $_system_requirements = array();
    private $_widget_instance = array();

    public function __construct() {
        add_action('plugins_loaded', array($this, 'plugins_loaded'));
        add_action('after_setup_theme', array($this, 'after_setup_theme'));
    }

    /** @return Core */
    public static function instance() {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class();
        }

        return $instance[$class];
    }

    public function plugins_loaded() {
        if (d4p_is_classicpress()) {
            $this->cms = 'classicpress';
            $this->cms_version = classicpress_version_short();
        } else {
            global $wp_version;

            $this->cms_version = $wp_version;
        }

        $this->wp_version = substr(str_replace('.', '', $this->cms_version), 0, 2);

        if (!empty($this->widgets)) {
            add_action('widgets_init', array($this, 'widgets_init'));
        }

        if ($this->enqueue) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        }

        $this->is_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;

        $this->load_textdomain();

        $this->_system_requirements = $this->check_system_requirements();

        if (!empty($this->_system_requirements)) {
            if (is_admin()) {
                add_action('admin_notices', array($this, 'system_requirements_notices'));
            } else {
                $this->deactivate();
            }
        } else {
            $this->init_capabilities();
            $this->run();
        }
    }

    public function load_textdomain() {
        load_plugin_textdomain($this->plugin, false, $this->plugin.'/languages');
        load_plugin_textdomain('d4plib', false, $this->plugin.'/d4plib/languages');
    }

    public function init_capabilities() {
        $role = get_role('administrator');

        if (!is_null($role)) {
            $role->add_cap($this->cap);
        } else {
            $this->cap = 'activate_plugins';
        }
    }

    public function file($type, $name, $min = true, $base_url = null) {
        $get = is_null($base_url) ? $this->url : $base_url;

        $get .= $type.'/'.$name;

        if (!$this->is_debug && $min) {
            $get .= '.min';
        }

        $get .= '.'.$type;

        return $get;
    }

    public function plugin_name() {
        return $this->plugin.'/'.$this->plugin.'.php';
    }

    public function deactivate() {
        deactivate_plugins($this->plugin_name(), false);
    }

    public function recommend($panel = 'update') {
        $four = Four::instance('plugin', $this->plugin, $this->s()->i()->version, $this->s()->i()->build);
        $four->ad();

        return $four->ad_render($panel);
    }

    public function after_setup_theme() {
    }

    public function widgets_init() {
    }

    public function enqueue_scripts() {
    }

    public function system_requirements_notices() {
        $plugin = $this->s()->i()->name();
        $versions = array();

        foreach ($this->_system_requirements as $req) {
            if ($req[1] == 0) {
                $versions[] = sprintf(_x("%s version %s (%s is not active on your website)", "System requirement version", "d4plib"), $req[0], '<strong>'.$req[2].'</strong>', '<strong style="color: #900;">'.$req[0].'</strong>');
            } else {
                $versions[] = sprintf(_x("%s version %s (your system runs version %s)", "System requirement version", "d4plib"), $req[0], '<strong>'.$req[2].'</strong>', '<strong style="color: #900;">'.$req[1].'</strong>');
            }
        }

        $render = '<div class="notice notice-error"><p>';
        $render .= sprintf(_x("System requirements check for %s failed. This plugin requires %s. The plugin will now be disabled.", "System requirement notice", "d4plib"), '<strong>'.$plugin.'</strong>', join(', ', $versions));
        $render .= '</p></div>';

        echo $render;

        $this->deactivate();
    }

    protected function check_system_requirements() {
        if (defined('DEV4PRESS_NO_SYSREQ_CHECK') && DEV4PRESS_NO_SYSREQ_CHECK) {
            return array();
        }

        global $wpdb;

        $list = array();

        $cms = $this->s()->i()->requirement_version($this->cms);

        if (version_compare($this->cms_version, $cms, '>=') === false) {
            $cms_name = $this->cms == 'classicpress' ? 'ClassicPress' : 'WordPress';
            $list[] = array($cms_name, $this->cms_version, $cms);
        }

        $php = $this->s()->i()->requirement_version('php');

        if (version_compare(PHP_VERSION, $php, '>=') === false) {
            $list[] = array('PHP', PHP_VERSION, $php);
        }

        $mysql = $this->s()->i()->requirement_version('mysql');

        if (version_compare($wpdb->db_version(), $mysql, '>=') === false) {
            $list[] = array('MySQL', $wpdb->db_version(), $mysql);
        }

        $bbpress = $this->s()->i()->requirement_version('bbpress');

        if ($bbpress !== false) {
            $installed = d4p_get_bbpress_major_version_number('full');

            if ($installed === 0 || version_compare($installed, $bbpress, '>=') === false) {
                $list[] = array('bbPress', $installed, $bbpress);
            }
        }

        return $list;
    }

    public function store_widget_instance($instance) {
        $this->_widget_instance = $instance;
    }

    public function widget_instance() {
        return $this->_widget_instance;
    }

    /** @return \Dev4Press\Core\Plugins\Settings */
    abstract public function s();

    abstract public function run();
}
