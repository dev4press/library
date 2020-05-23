<?php

namespace Dev4Press\Core\Shared;

class Enqueue {
    static private $_current_instance = null;

    private $_enqueue_prefix = 'd4plib3-';
    private $_url;
    private $_rtl;
    private $_debug;

    private $_locales = array();
    private $_actuals = array(
        'js' => array(),
        'css' => array()
    );

    private $_libraries = array(
        'js' => array(
            'flatpickr' => array('lib' => true, 'path' => 'flatpickr/', 'file' => 'flatpickr.min', 'ver' => '4.6.3', 'ext' => 'js', 'min' => false, 'min_locale' => true, 'locales' => array('de', 'es', 'fr', 'it', 'nl', 'pl', 'pt', 'ru', 'sr')),
            'flatpickr-confirm-date' => array('lib' => true, 'path' => 'flatpickr/plugins', 'file' => 'confirm-date', 'ver' => '4.6.3', 'ext' => 'js', 'min' => true, 'int' => array('flatpickr')),
            'flatpickr-month-select' => array('lib' => true, 'path' => 'flatpickr/plugins', 'file' => 'month-select', 'ver' => '4.6.3', 'ext' => 'js', 'min' => true, 'int' => array('flatpickr')),
            'flatpickr-range' => array('lib' => true, 'path' => 'flatpickr/plugins', 'file' => 'range', 'ver' => '4.6.3', 'ext' => 'js', 'min' => true, 'int' => array('flatpickr')),
            'clipboard' => array('lib' => true, 'path' => '', 'file' => 'clipboard.min', 'ver' => '2.0.4', 'ext' => 'js', 'min' => false),
            'cookies' => array('lib' => true, 'path' => '', 'file' => 'cookies.min', 'ver' => '2.2.1', 'ext' => 'js', 'min' => false),
            'alphanumeric' => array('lib' => true, 'path' => '', 'file' => 'jquery.alphanumeric.min', 'ver' => '2017', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'mark' => array('lib' => true, 'path' => '', 'file' => 'jquery.mark.min', 'ver' => '8.11.1', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'fitvids' => array('lib' => true, 'path' => '', 'file' => 'jquery.fitvids.min', 'ver' => '1.2.0', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'jqeasycharcounter' => array('lib' => true, 'path' => '', 'file' => 'jquery.jqeasycharcounter.min', 'ver' => '1.0', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'limitkeypress' => array('lib' => true, 'path' => '', 'file' => 'jquery.limitkeypress.min', 'ver' => '2016', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'numeric' => array('lib' => true, 'path' => '', 'file' => 'jquery.numeric.min', 'ver' => '1.4.1', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'select' => array('lib' => true, 'path' => '', 'file' => 'jquery.select.min', 'ver' => '2.2.6', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'textrange' => array('lib' => true, 'path' => '', 'file' => 'jquery.textrange.min', 'ver' => '1.4.0', 'ext' => 'js', 'min' => false, 'req' => array('jquery'))
        ),
        'css' => array(
            'flatpickr' => array('lib' => true, 'path' => 'flatpickr/', 'file' => 'flatpickr.min', 'ver' => '4.6.3', 'ext' => 'css', 'min' => false),
            'flatpickr-confirm-date' => array('lib' => true, 'path' => 'flatpickr/plugins', 'file' => 'confirm-date', 'ver' => '4.6.3', 'ext' => 'css', 'min' => true, 'int' => array('flatpickr')),
            'flatpickr-month-select' => array('lib' => true, 'path' => 'flatpickr/plugins', 'file' => 'month-select', 'ver' => '4.6.3', 'ext' => 'css', 'min' => true, 'int' => array('flatpickr'))
        )
    );

    public function __construct($base_url) {
        $this->_url = trailingslashit($base_url);

        add_action('init', array($this, 'start'), 5);
    }

    /** @return Enqueue */
    public static function init($base_url = '') {
        if (is_null(self::$_current_instance)) {
            self::$_current_instance = new Enqueue($base_url);
        }

        return self::$_current_instance;
    }

    /** @return Enqueue */
    public static function i() {
        return self::$_current_instance;
    }

    public function prefix() {
        return $this->_enqueue_prefix;
    }

    public function locale() {
        return apply_filters('plugin_locale', get_user_locale());
    }

    public function locale_js_code($script) {
        $locale = $this->locale();

        if (!empty($locale) && isset($this->_libraries['js'][$script]['locales'])) {
            $code = strtolower(substr($locale, 0, 2));

            if (in_array($code, $this->_libraries['js'][$script]['locales'])) {
                return $code;
            }
        }

        return false;
    }

    public function registered_locale($script) {
        return isset($this->_locales[$script]) ? $this->_locales[$script] : false;
    }

    public function start() {
        $this->_rtl = is_rtl();
        $this->_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;

        do_action('d4plib_shared_enqueue_prepare');

        $this->register_styles();
        $this->register_scripts();
    }

    public function add_css($name, $args = array()) {
        $this->_libraries['css'][$name] = $args;
    }

    public function add_js($name, $args = array()) {
        $this->_libraries['js'][$name] = $args;
    }

    public function is_rtl() {
        return $this->_rtl;
    }

    public function register_styles() {
        foreach ($this->_libraries['css'] as $name => $args) {
            $code = $args['lib'] ? $this->_enqueue_prefix.$name : $name;
            $req = isset($args['req']) ? $args['req'] : array();

            if (isset($args['int']) && !empty($args['int'])) {
                foreach ($args['int'] as $lib) {
                    $req[] = $this->_actuals['css'][$lib];
                }
            }

            wp_register_style($code, $this->url($args), $req, $args['ver']);

            $this->_actuals['css'][$name] = $code;
        }
    }

    public function register_scripts() {
        foreach ($this->_libraries['js'] as $name => $args) {
            $code = $args['lib'] ? $this->_enqueue_prefix.$name : $name;
            $req = isset($args['req']) ? $args['req'] : array();
            $footer = isset($args['footer']) ? $args['footer'] : true;

            if (isset($args['int']) && !empty($args['int'])) {
                foreach ($args['int'] as $lib) {
                    $req[] = $this->_actuals['js'][$lib];
                }
            }

            wp_register_script($code, $this->url($args), $req, $args['ver'], $footer);

            $this->_actuals['js'][$name] = $code;

            if (isset($args['locales'])) {
                $_locale = $this->locale_js_code($name);

                if ($_locale !== false) {
                    $this->_locales[$name] = $_locale;
                    $loc_code = $code.'-'.$_locale;

                    wp_register_script($loc_code, $this->url($args, $_locale), array($code), $args['ver'], $footer);

                    $this->_actuals['js'][$name] = $loc_code;
                }
            }
        }
    }

    private function url($obj, $locale = null) {
        $url = $obj['lib'] ? trailingslashit($this->_url.'resources/libraries/'.$obj['path']) : trailingslashit($obj['url']);

        if (is_null($locale)) {
            $min = $obj['min'];
            $url .= $obj['file'];
        } else {
            $min = $obj['min_locale'];
            $url .= 'l10n/'.$locale;
        }

        if ($min && !$this->_debug) {
            $url .= '.min';
        }

        $url .= '.'.$obj['ext'];

        return $url;
    }
}
