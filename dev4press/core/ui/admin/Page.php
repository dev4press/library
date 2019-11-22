<?php

namespace Dev4Press\Core\UI;

if (!defined('ABSPATH')) { exit; }

abstract class Page {
    protected $type = '';
    protected $code = '';

    public function __construct() {

    }

    /** @return Page */
    public static function instance() {
        static $instance = array();

        $class = get_called_class();

        if (!isset($instance[$class])) {
            $instance[$class] = new $class();
        }

        return $instance[$class];
    }
}
