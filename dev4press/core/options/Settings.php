<?php

namespace Dev4Press\Core\Options;

abstract class Settings {
    protected $settings;

    public function __construct() {
        $this->init();
    }

    /** @return \Dev4Press\Core\Options\Settings */
    public static function instance() {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class();
        }

        return $instance[$class];
    }

    public function all() {
        return $this->settings;
    }

    public function get($panel, $group = '') {
        if ($group == '') {
            return $this->settings[$panel];
        } else {
            return $this->settings[$panel][$group];
        }
    }

    public function settings($panel) {
        $list = array();

        foreach ($this->settings[$panel] as $obj) {
            foreach ($obj['settings'] as $o) {
                $list[] = $o;
            }
        }

        return $list;
    }

    abstract protected function init();
    abstract protected function value($name, $group = 'settings', $default = null);
}
