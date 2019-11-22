<?php

namespace Dev4Press\Core\UI;

if (!defined('ABSPATH')) { exit; }

class Loader {
    public $libraries = array(
        'js' => array(
            'clipboard' => array('path' => 'libraries/clipboard.min.js', 'min' => false),
            'cookies' => array('path' => 'libraries/cookies.min.js', 'min' => false),
            'wp-color-picker-alpha' => array('path' => 'libraries/wp-color-picker-alpha.min.js', 'min' => false, 'req' => array('wp-color-picker')),
            'alphanumeric' => array('path' => 'libraries/jquery.alphanumeric.min.js', 'min' => false, 'req' => array('jquery')),
            'are-you-sure' => array('path' => 'libraries/jquery.are-you-sure.min.js', 'min' => false, 'req' => array('jquery')),
            'fitvids' => array('path' => 'libraries/jquery.fitvids.min.js', 'min' => false, 'req' => array('jquery')),
            'jqeasycharcounter' => array('path' => 'libraries/jquery.jqeasycharcounter.min.js', 'min' => false, 'req' => array('jquery')),
            'limitkeypress' => array('path' => 'libraries/jquery.limitkeypress.min.js', 'min' => false, 'req' => array('jquery')),
            'numeric' => array('path' => 'libraries/jquery.numeric.min.js', 'min' => false, 'req' => array('jquery')),
            'select' => array('path' => 'libraries/jquery.select.min.js', 'min' => false, 'req' => array('jquery')),
            'textrange' => array('path' => 'libraries/jquery.textrange.min.js', 'min' => false, 'req' => array('jquery'))
        ),
        'css' => array(

        )
    );

    public function __construct() {

    }

    public static function instance() {
        static $_d4p_lib_loader = null;

        if (!isset($_d4p_lib_loader)) {
            $_d4p_lib_loader = new Loader();
        }

        return $_d4p_lib_loader;
    }

    public function enqueue($type, $name) {

    }
}
