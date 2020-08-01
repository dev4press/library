<?php

/*
Name:    Dev4Press\Core\Plugins\Widget
Version: v3.1.4
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

use WP_Widget;

if (!defined('ABSPATH')) {
    exit;
}

abstract class Widget extends WP_Widget {
    public $selective_refresh = true;
    public $allow_empty_title = false;
    public $results_cachable = false;

    public $cache_prefix = 'd4p';
    public $cache_method = 'full';

    public $widget_base = '';
    public $widget_name = '';
    public $widget_description = '';

    public $widget_id = '';

    public $defaults = array(
        'title' => 'Base Widget Class',
    );

    protected $shared_defaults = array(
        '_users' => 'all',
        '_roles' => array(),
        '_caps' => array(),
        '_hook' => '',
        '_tab' => 'global',
        '_cached' => 8,
        '_devid' => '',
        '_class' => '',
        '_before' => '',
        '_after' => ''
    );

    protected $cache_active = false;
    protected $cache_time = 0;
    protected $cache_key = '';

    /** @var \Dev4Press\Core\UI\Widgets */
    protected $widgets_render;

    public function __construct($id_base = false, $name = '', $widget_options = array(), $control_options = array()) {
        $defaults = array(
            'customize_selective_refresh' => $this->selective_refresh,
            'classname' => 'widget-'.$this->widget_base,
            'description' => $this->widget_description
        );

        $widget_options = wp_parse_args($widget_options, $defaults);
        $control_options = empty($control_options) ? array('width' => 500) : $control_options;

        parent::__construct($this->widget_base, $this->widget_name, $widget_options, $control_options);
    }

    public function form($instance) {
        $instance = wp_parse_args((array)$instance, $this->get_defaults());
        $the_tabs = $this->the_form($instance);

        $tabs = array(
            'global' => array('name' => __("Global", "d4plib"), 'include' => array('widget-global')),
            'extra' => array('name' => __("Extra", "d4plib"), 'include' => array('widget-extra')),
            'advanced' => array('name' => __("Advanced", "d4plib"), 'include' => array('widget-advanced'))
        );

        if ($this->results_cachable) {
            $tabs['global']['include'][] = 'widget-cache';
        }

        if (!empty($the_tabs)) {
            $tabs = array_slice($tabs, 0, 1, true) +
                $the_tabs +
                array_slice($tabs, 1, 2, true);
        }

        include($this->widgets_render->find('widget-loader.php'));
    }

    public function update($new_instance, $old_instance) {
        return $this->shared_update($new_instance, $old_instance);
    }

    public function widget($args, $instance) {
        $this->prepare_cache($instance);

        $this->the_init($instance, $args);

        if ($this->check_visibility($instance)) {
            if ($this->cache_method == 'full' && $this->cache_active && $this->cache_key !== '') {
                $render = get_transient($this->cache_key);

                if ($render === false) {
                    ob_start();

                    $this->widget_output($args, $instance);

                    $render = ob_get_contents();
                    ob_end_clean();

                    set_transient($this->cache_key, $render, $this->cache_time * 3600);
                }

                echo $render;
            } else {
                $this->widget_output($args, $instance);
            }
        }
    }

    protected function get_valid_list_value($name, $value, $list) {
        $value = d4p_sanitize_basic($value);

        if (in_array($value, $list)) {
            return $value;
        }

        return isset($this->defaults[$name]) ? $this->defaults[$name] : $this->shared_defaults[$name];
    }

    protected function shared_update($new_instance, $old_instance) {
        $instance = $old_instance;

        $instance['title'] = d4p_sanitize_basic($new_instance['title']);

        $instance['_class'] = d4p_sanitize_basic($new_instance['_class']);
        $instance['_tab'] = d4p_sanitize_key_expanded($new_instance['_tab']);
        $instance['_hook'] = d4p_sanitize_key_expanded($new_instance['_hook']);
        $instance['_devid'] = d4p_sanitize_key_expanded($new_instance['_devid']);

        $instance['_users'] = $this->get_valid_list_value('_users', $new_instance['_users'], array_keys($this->get_list_user_visibility()));

        $_caps = d4p_sanitize_basic($new_instance['_caps']);
        $_caps = explode(',', $_caps);
        $instance['_caps'] = array_map('trim', $_caps);

        $instance['_roles'] = array();

        if (isset($new_instance['_roles'])) {
            $_roles = array_map('d4p_sanitize_basic', $new_instance['_roles']);
            $valid_roles = d4p_get_wordpress_user_roles();

            foreach ($_roles as $role) {
                if (isset($valid_roles[$role])) {
                    $instance['_roles'][] = $role;
                }
            }
        }

        if (isset($new_instance['_cached'])) {
            $instance['_cached'] = absint($new_instance['_cached']);
        }

        if (current_user_can('unfiltered_html')) {
            $instance['_before'] = $new_instance['_before'];
            $instance['_after'] = $new_instance['_after'];
        } else {
            $instance['_before'] = d4p_sanitize_html($new_instance['_before']);
            $instance['_after'] = d4p_sanitize_html($new_instance['_after']);
        }

        return $instance;
    }

    protected function prepare_cache($instance) {
        if ($this->results_cachable) {
            $this->cache_time = isset($instance['_cached']) ? absint($instance['_cached']) : 0;

            if ($this->cache_time > 0) {
                $copy = (array)$instance;
                unset($copy['_cached']);

                $this->cache_key = $this->cache_prefix.'_'.md5($this->widget_base.'_'.serialize($copy));
            }
        }
    }

    protected function get_cached_data($instance) {
        if ($this->cache_method == 'data' && $this->cache_active && $this->cache_key !== '') {
            $results = get_transient($this->cache_key);

            if ($results === false) {
                $results = $this->the_results($instance);

                set_transient($this->cache_key, $results, $this->cache_time * 3600);
            }

            return $results;
        } else {
            return $this->the_results($instance);
        }
    }

    protected function widget_output($args, $instance) {
        extract($args, EXTR_SKIP);

        echo $before_widget;

        $title = $this->title($instance);

        if (!empty($title) || $this->allow_empty_title) {
            echo $before_title.$title.$after_title;
        }

        $this->render_widget($instance);

        echo $after_widget;
    }

    protected function render_widget($instance) {
        $results = $this->get_cached_data($instance);

        $this->store_instance($instance);

        $this->render_widget_header($instance);
        $this->the_render($instance, $results);
        $this->render_widget_footer($instance);
    }

    protected function check_visibility($instance) {
        $visible = $this->is_visible($instance);

        if ($visible) {
            $users = isset($instance['_users']) ? $instance['_users'] : 'all';
            $roles = isset($instance['_roles']) ? $instance['_roles'] : array();
            $cap = isset($instance['_cap']) ? $instance['_cap'] : '';

            $logged = is_user_logged_in();

            if ($users == 'users') {
                $visible = $logged;
            } else if ($users == 'visitors') {
                $visible = !$logged;
            } else if ($users == 'roles') {
                if (empty($roles)) {
                    $visible = $logged;
                } else {
                    $visible = d4p_is_current_user_roles($roles);
                }
            } else if ($users == 'caps' && !empty($cap)) {
                $visible = current_user_can($cap);
            }
        }

        if (isset($instance['_hook']) && $instance['_hook'] != '') {
            $visible = apply_filters($this->widget_base.'_visible_'.$instance['_hook'], $visible, $this);
        }

        return $visible;
    }

    protected function render_widget_header($instance) {
        $class = array('d4p-widget-wrapper');
        $class[] = str_replace('_', '-', $this->widget_base);

        if ($instance['_class'] != '') {
            $class[] = $instance['_class'];
        }

        echo '<div class="'.join(' ', $class).'">'.D4P_EOL;

        if (isset($instance['_before']) && $instance['_before'] != '') {
            echo '<div class="d4p-widget-before">'.$instance['_before'].'</div>';
        }
    }

    protected function render_widget_footer($instance) {
        if (isset($instance['_after']) && $instance['_after'] != '') {
            echo '<div class="d4p-widget-after">'.$instance['_after'].'</div>';
        }

        echo '</div>';
    }

    public function get_tabkey($tab) {
        return str_replace(array('_', ' '), array('-', '-'), $this->get_field_id('tab-'.$tab));
    }

    public function get_defaults() {
        return array_merge($this->shared_defaults, $this->defaults);
    }

    public function title($instance) {
        return isset($instance['title']) ? $instance['title'] : '';
    }

    public function is_visible($instance) {
        return true;
    }

    public function standalone_render($instance = array()) {
        $instance = shortcode_atts($this->defaults, $instance);

        $this->render_widget($instance);
    }

    public function visibility_hook_format() {
        return $this->widget_base.'_visible_{filter_name}';
    }

    public function the_init($instance, $args) {

    }

    public function the_results($instance) {
        return false;
    }

    abstract public function the_form($instance);

    abstract public function the_render($instance, $results = false);

    abstract public function store_instance($instance);

    public function get_list_user_visibility() {
        return array(
            'all' => __("Everyone", "d4plib"),
            'users' => __("Logged in users", "d4plib"),
            'visitors' => __("Not logged in visitors", "d4plib"),
            'roles' => __("Users with specified roles", "d4plib"),
            'caps' => __("Users with specified capabilities", "d4plib")
        );
    }

    public function get_list_order() {
        return array(
            'DESC' => __("Descending", "d4plib"),
            'ASC' => __("Ascending", "d4plib")
        );
    }
}
