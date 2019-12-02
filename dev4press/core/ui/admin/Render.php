<?php

namespace Dev4Press\Core\UI\Admin;

if (!defined('ABSPATH')) { exit; }

class Render {
    public function __construct() { }

    /** @return Render */
    public static function instance() {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class();
        }

        return $instance[$class];
    }

    public function icon_class($name, $modifiers = array(), $extra_class = '') {
        $class = '';
        $dashicons = false;

        if (substr($name, 0, 9) == 'dashicons') {
            $dashicons = true;
            $class = 'dashicons '.$name;
        } else {
            $class = 'd4p-icon d4p-'.$name;
        }

        if (!empty($modifiers) && !$dashicons) {
            $modifiers = (array)$modifiers;

            foreach ($modifiers as $key) {
                $class.= ' '.'d4p-icon'.'-'.$key;
            }
        }

        if (!empty($extra_class)) {
            $class.= ' '.$extra_class;
        }

        return $class;
    }

    public function icon($name = '', $modifiers = array(), $extra_class = '') {
        if (empty($name)) {
            return '';
        }

        $icon = '<i aria-hidden="true" class="%s"></i> ';
        $class = $this->icon_class($name, $modifiers, $extra_class);

        return sprintf($icon, $class);
    }

    public function settings_break($label, $icon = '') {
        $break = $this->div_break();
        $break.= '<div class="d4p-panel-break d4p-clearfix">';
        $break.= '<h1 id="settings-break-'.sanitize_key($label).'">'.$this->icon($icon).$label.'</h1>';
        $break.= '</div>';
        $break.= $this->div_break();

        return $break;
    }

    public function settings_group_break($label, $icon = '') {
        $break = $this->div_break();
        $break.= '<div class="d4p-panel-group-break d4p-clearfix">';
        $break.= '<h2 id="settings-group-break-'.sanitize_key($label).'">'.$this->icon($icon).$label.'</h2>';
        $break.= '</div>';
        $break.= $this->div_break();

        return $break;
    }

    public function div_break() {
        return '<div style="clear: both"></div>';
    }
}
