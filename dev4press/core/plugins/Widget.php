<?php

namespace Dev4Press\Core\Plugins;

abstract class Widget extends \WP_Widget {
    public $selective_refresh = true;
    public $results_cachable = false;

    public $widget_base = '';
    public $widget_name = '';
    public $widget_description = '';

    public $widget_id = '';

    public $defaults = array(
        'title' => 'Base Widget Class',
        '_display' => 'all',
        '_cached' => 0,
        '_hook' => '',
        '_class' => ''
    );

    public function __construct($id_base = false, $name = '', $widget_options = array(), $control_options = array()) {
        $defaults = array(
            'customize_selective_refresh' => $this->selective_refresh,
            'classname' => 'widget-'.$this->widget_base,
            'description' => $this->widget_description
        );

        $widget_options = wp_parse_args($widget_options, $defaults);
        $control_options = empty($control_options) ? array() : $control_options;

        parent::__construct($this->widget_base, $this->widget_name, $widget_options, $control_options);
    }

    public function get_defaults() {
        return $this->defaults;
    }

    public function form($instance) {
        $instance = wp_parse_args((array)$instance, $this->get_defaults());


    }

    public function widget($args, $instance) {

    }

    public function title($instance) {
        return $instance['title'];
    }

    public function is_visible($instance) {
        return true;
    }

    private function _widget_id($args) {
        $this->widget_id = str_replace(array('-', '_'), array('', ''), $args['widget_id']);
    }

    private function _cache_key($instance) {
        $this->cache_active = $this->_cache_active($instance);

        if ($this->cache_active) {
            $copy = $instance;
            unset($copy['_cached']);

            $this->cache_key = $this->cache_prefix.'_'.md5($this->widget_base.'_'.serialize($copy));
        }
    }

    private function _cache_active($instance) {
        $this->cache_time = isset($instance['_cached']) ? intval($instance['_cached']) : 0;

        return $this->cache_time > 0;
    }

    private function _cached_data($instance) {
        if ($this->cache_method == 'data' && $this->cache_active && $this->cache_key !== '') {
            $results = get_transient($this->cache_key);

            if ($results === false) {
                $results = $this->results($instance);
                set_transient($this->cache_key, $results, $this->cache_time * 3600);
            }

            return $results;
        } else {
            return $this->results($instance);
        }
    }

    protected function widget_output($args, $instance) {
        extract($args, EXTR_SKIP);

        ob_start();

        $results = $this->_cached_data($instance);
        echo $before_widget;

        if (isset($instance['title']) && $instance['title'] != '') {
            echo $before_title;
            echo $this->title($instance);
            echo $after_title;
        }

        echo $this->render($results, $instance);
        echo $after_widget;

        $render = ob_get_contents();
        ob_end_clean();

        return $render;
    }
}
