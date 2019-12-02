<?php

/*
Name:    Dev4Press\Core\Admin\Plugin
Version: v2.9.5
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2019 Milan Petrovic (email: support@dev4press.com)

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

namespace Dev4Press\Core\Admin;

use Dev4Press\Core\UI\Enqueue;

if (!defined('ABSPATH')) { exit; }

abstract class Plugin {
    public $menu_cap = 'activate_plugins';
    public $has_widgets = false;

    public $plugin = '';
    public $plugin_prefix = '';
    public $plugin_menu = '';
    public $plugin_title = '';

    public $url = '';
    public $path = '';

    public $is_debug = false;
    public $is_rtl = false;

    public $page = false;
    public $panel = '';
    public $subpanel = '';

    public $screen_id = '';

    /** @var \Dev4Press\Core\UI\Admin\Panel */
    public $object = null;

    /** @var \Dev4Press\Core\UI\Enqueue */
    public $enqueue = null;

    public $enqueue_wp = array();
    public $menu_items = array();
    public $setup_items = array();
    public $page_ids = array();

    function __construct() {
        $this->consturctor();

        add_filter('plugin_action_links', array($this, 'plugin_actions'), 10, 2);
        add_filter('plugin_row_meta', array($this, 'plugin_links'), 10, 2);

        add_action('plugins_loaded', array($this, 'plugins_loaded'), 20);
        add_action('after_setup_theme', array($this, 'after_setup_theme'), 20);
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
            $settings_link = '<a href="'.$this->main_url().'">'.__("Settings", "d4plib").'</a>';
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
        $this->is_rtl = is_rtl();

        $this->enqueue = Enqueue::instance($this->url.'d4plib/', $this);

        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));

        add_action('current_screen', array($this, 'current_screen'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function h($hook) {
        return $this->plugin_prefix.'_'.$hook;
    }

    public function v() {
        return $this->plugin_prefix.'_handler';
    }

    public function plugin_name() {
        return $this->plugin.'/'.$this->plugin.'.php';
    }

    public function title() {
        return $this->plugin_title;
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
        $this->help_tab_sidebar();

        do_action($this->plugin_prefix.'_load_admin_page');

        if ($this->panel !== false && $this->panel != '') {
            do_action($this->h('load_admin_page_'.$this->panel));

            if ($this->subpanel !== false && $this->subpanel != '') {
                do_action($this->h('load_admin_page_'.$this->panel.'_'.$this->subpanel));
            }
        }

        $this->help_tab_getting_help();
    }

    public function help_tab_sidebar() {
        $links = apply_filters($this->plugin_prefix.'_admin_help_sidebar_links', array(
            'home' => '<a target="_blank" href="https://plugins.dev4press.com/'.$this->plugin.'/">'.__("Home Page", "d4plib").'</a>',
            'kb' => '<a target="_blank" href="https://support.dev4press.com/kb/product/'.$this->plugin.'/">'.__("Knowledge Base", "d4plib").'</a>',
            'forum' => '<a target="_blank" href="https://support.dev4press.com/forums/forum/plugins/'.$this->plugin.'/">'.__("Support Forum", "d4plib").'</a>'
        ), $this);

        $this->screen()->set_help_sidebar('<p><strong>'.$this->title().'</strong></p><p>'.join('<br/>', $links).'</p>');
    }

    public function help_tab_getting_help() {
        do_action($this->plugin_prefix.'_admin_help_tabs_before', $this);

        $this->screen()->add_help_tab(
            array(
                'id' => 'd4p-plugin-help-info',
                'title' => __("Help & Support", "d4plib"),
                'content' => '<h2>'.__("Help & Support", "d4plib").'</h2><p>'.sprintf(__("To get help with %s, you can start with Knowledge Base list of frequently asked questions, user guides, articles (tutorials) and reference guide (for developers).", "d4plib"), $this->title()).
                    '</p><p><a href="https://support.dev4press.com/kb/product/'.$this->plugin.'/" class="button-primary" target="_blank">'.__("Knowledge Base", "d4plib").'</a> <a href="https://support.dev4press.com/forums/forum/plugins/'.$this->plugin.'/" class="button-secondary" target="_blank">'.__("Support Forum", "d4plib").'</a></p>'
            )
        );

        $this->screen()->add_help_tab(
            array(
                'id' => 'd4p-plugin-help-bugs',
                'title' => __("Found a bug?", "d4plib"),
                'content' => '<h2>'.__("Found a bug?", "d4plib").'</h2><p>'.sprintf(__("If you find a bug in %s, you can report it in the support forum.", "d4plib"), $this->title()).
                    '</p><p>'.__("Before reporting a bug, make sure you use latest plugin version, your website and server meet system requirements. And, please be as descriptive as possible, include server side logged errors, or errors from browser debugger.", "d4plib").
                    '</p><p><a href="https://support.dev4press.com/forums/forum/plugins/'.$this->plugin.'/" class="button-primary" target="_blank">'.__("Open new topic", "d4plib").'</a></p>'
            )
        );

        do_action($this->plugin_prefix.'_admin_help_tabs', $this);
    }

    protected function load_postget_back() {
        if (isset($_POST[$this->v()]) && $_POST[$this->v()] == 'postback') {
            $this->run_postback();
        } else if (isset($_GET[$this->v()]) && $_GET[$this->v()] == 'getback') {
            $this->run_getback();
        }
    }

    public function install_or_update() {
        $install = $this->settings()->is_install();
        $update = $this->settings()->is_update();

        if ($install) {
            $this->panel = 'install';
        } else if ($update) {
            $this->panel = 'update';
        }

        return $install || $update;
    }

    public function svg_icon() {
        return '';
    }

    public function global_admin_notices() {
        if ($this->settings()->is_install()) {
            add_action('admin_notices', array($this, 'install_notice'));
        }

        if ($this->settings()->is_update()) {
            add_action('admin_notices', array($this, 'update_notice'));
        }
    }

    public function install_notice() {
        if (current_user_can('install_plugins') && $this->page === false) {
            echo '<div class="notice notice-info is-dismissible"><p>';
            echo sprintf(__("%s is activated and it needs to finish installation.", "d4plib"), $this->title());
            echo ' <a href="'.$this->main_url().'">'.__("Click Here", "d4plib").'</a>.';
            echo '</p></div>';
        }
    }

    public function update_notice() {
        if (current_user_can('install_plugins') && $this->page === false) {
            echo '<div class="notice notice-info is-dismissible"><p>';
            echo sprintf(__("%s is updated, and you need to review the update process.", "d4plib"), $this->title());
            echo ' <a href="'.$this->main_url().'">'.__("Click Here", "d4plib").'</a>.';
            echo '</p></div>';
        }
    }

    public function panel_object() {
        if (isset($this->setup_items[$this->panel])) {
            return (object)$this->setup_items[$this->panel];
        } else if (isset($this->menu_items[$this->panel])) {
            return (object)$this->menu_items[$this->panel];
        }
    }

    public function enqueue_scripts($hook) {
        if ($this->page) {
            $this->enqueue->wp($this->enqueue_wp);

            do_action($this->h('enqueue_scripts_early'));

            $this->enqueue->js('shared')->js('admin');
            $this->enqueue->css('font')->css('grid')->css('admin')->css('options');

            if ($this->is_rtl) {
                $this->enqueue->css('rtl');
            }

            do_action($this->h('enqueue_scripts'));

            $this->enqueue->css('responsive');
        }

        if ($this->has_widgets && $hook == 'widgets.php') {
            $this->enqueue->js('widgets')->css('widgets');

            do_action($this->h('enqueue_scripts_widgets'));
        }
    }

    public function css($name, $min = true, $req = array()) {
        $url = trailingslashit($this->url).'css/'.$name;

        if (!$this->is_debug && $min) {
            $url.= '.min';
        }

        $url.= '.css';

        wp_enqueue_style($this->plugin_prefix.'-'.$name, $url, $req, $this->settings()->file_version());
    }

    public function js($name, $min = true, $req = array(), $in_footer = true) {
        $url = trailingslashit($this->url).'js/'.$name;

        if (!$this->is_debug && $min) {
            $url.= '.min';
        }

        $url.= '.js';

        wp_enqueue_script($this->plugin_prefix.'-'.$name, $url, $req, $this->settings()->file_version(), $in_footer);
    }


    public function admin_panel() {
        require_once($this->lib_path().'functions/panel.php');

        $this->object->show();
    }

    public function lib_path() {
        return $this->path.'d4plib/';
    }

    public function panels() {
        return $this->setup_items + $this->menu_items;
    }

    public function admin_init() { }
    public function after_setup_theme() { }

    abstract public function main_url();
    abstract public function current_url($with_subpanel = true);
    abstract public function panel_url($panel = 'dashboard', $subpanel = '');

    abstract public function admin_menu();
    abstract public function current_screen($screen);

    abstract public function consturctor();
    abstract public function run_getback();
    abstract public function run_postback();

    /** @return \Dev4Press\Core\Plugins\Settings */
    abstract public function settings();
}
