<?php

namespace Dev4Press\Core\UI;

if (!defined('ABSPATH')) { exit; }

abstract class Render {
    public function __construct() {

    }

    /** @return Render */
    public static function instance() {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class();
        }

        return $instance[$class];
    }

    public function icon_class() {

    }

    public function icon() {

    }
}
