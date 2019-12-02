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

        if (in_array($panel, array('index', 'full'))) {
            foreach ($this->settings as $panel) {
                foreach ($panel as $obj) {
                    $list = array_merge($list, $this->settings_from_panel($obj));
                }
            }
        } else {
            foreach ($this->settings[$panel] as $obj) {
                $list = array_merge($list, $this->settings_from_panel($obj));
            }
        }

        return $list;
    }

    protected function settings_from_panel($obj) {
        $list = array();

        if (isset($obj['settings'])) {
            $obj['sections'] = array('label' => '', 'name' => '', 'class' => '', 'settings' => $obj['settings']);
            unset($obj['settings']);
        }

        foreach ($obj['sections'] as $s) {
            foreach ($s['settings'] as $o) {
                if (!empty($o->type)) {
                    $list[] = $o;
                }
            }
        }

        return $list;
    }

    abstract protected function init();
    abstract protected function value($name, $group = 'settings', $default = null);
}
