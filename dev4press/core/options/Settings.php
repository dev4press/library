<?php

namespace Dev4Press\Core\Options;

abstract class Settings {
    protected $settings;

    public function __construct() {
        $this->init();
    }

    public function get($panel, $group = '') {
        if ($group == '') {
            return $this->settings[$panel];
        }
        else {
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
