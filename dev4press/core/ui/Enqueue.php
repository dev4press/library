<?php

namespace Dev4Press\Core\UI;

if (!defined('ABSPATH')) { exit; }

final class Enqueue {
    private $_debug = false;
    private $_url;

    /** @var \Dev4Press\Core\Admin\Plugin|\Dev4Press\Core\Admin\Menu\Plugin|\Dev4Press\Core\Admin\Submenu\Plugin */
    private $_admin;

    private $_loaded = array(
        'js' => array(),
        'css' => array()
    );

    private $_libraries = array(
        'js' => array(
            'admin' => array('path' => 'js/', 'file' => 'admin', 'ext' => 'js', 'min' => true),
            'meta' => array('path' => 'js/', 'file' => 'meta', 'ext' => 'js', 'min' => true),
            'media' => array('path' => 'js/', 'file' => 'media', 'ext' => 'js', 'min' => true),
            'ctrl' => array('path' => 'js/', 'file' => 'ctrl', 'ext' => 'js', 'min' => true),
            'helpers' => array('path' => 'js/', 'file' => 'helpers', 'ext' => 'js', 'min' => true),
            'customizer' => array('path' => 'js/', 'file' => 'customizer', 'ext' => 'js', 'min' => true),
            'widgets' => array('path' => 'js/', 'file' => 'widgets', 'ext' => 'js', 'min' => true),
            'wizard' => array('path' => 'js/', 'file' => 'wizard', 'ext' => 'js', 'min' => true),
            'clipboard' => array('path' => 'libraries/', 'file' => 'clipboard.min', 'ver' => '2.0.4', 'ext' => 'js', 'min' => false),
            'cookies' => array('path' => 'libraries/', 'file' => 'cookies.min', 'ver' => '2.2.1', 'ext' => 'js', 'min' => false),
            'wp-color-picker-alpha' => array('path' => 'libraries/', 'file' => 'wp-color-picker-alpha.min', 'ver' => '2.1.3', 'ext' => 'js', 'min' => false, 'req' => array('wp-color-picker')),
            'alphanumeric' => array('path' => 'libraries/', 'file' => 'jquery.alphanumeric.min', 'ver' => '2017', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'are-you-sure' => array('path' => 'libraries/', 'file' => 'jquery.are-you-sure.min', 'ver' => '1.9.0', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'fitvids' => array('path' => 'libraries/', 'file' => 'jquery.fitvids.min', 'ver' => '1.2.0', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'jqeasycharcounter' => array('path' => 'libraries/', 'file' => 'jquery.jqeasycharcounter.min', 'ver' => '1.0', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'limitkeypress' => array('path' => 'libraries/', 'file' => 'jquery.limitkeypress.min', 'ver' => '2016', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'numeric' => array('path' => 'libraries/', 'file' => 'jquery.numeric.min', 'ver' => '1.4.1', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'select' => array('path' => 'libraries/', 'file' => 'jquery.select.min', 'ver' => '2.2.6', 'ext' => 'js', 'min' => false, 'req' => array('jquery')),
            'textrange' => array('path' => 'libraries/', 'file' => 'jquery.textrange.min', 'ver' => '1.4.0', 'ext' => 'js', 'min' => false, 'req' => array('jquery'))
        ),
        'css' => array(
            'flags' => array('path' => 'css/', 'file' => 'flags', 'ext' => 'css', 'min' => true),
            'font' => array('path' => 'css/', 'file' => 'font', 'ext' => 'css', 'min' => true),
            'grid' => array('path' => 'css/', 'file' => 'grid', 'ext' => 'css', 'min' => true),
            'ctrl' => array('path' => 'css/', 'file' => 'ctrl', 'ext' => 'css', 'min' => true),
            'meta' => array('path' => 'css/', 'file' => 'meta', 'ext' => 'css', 'min' => true),
            'shared' => array('path' => 'css/', 'file' => 'shared', 'ext' => 'css', 'min' => true),
            'widgets' => array('path' => 'css/', 'file' => 'widgets', 'ext' => 'css', 'min' => true),
            'customizer' => array('path' => 'css/', 'file' => 'customizer', 'ext' => 'css', 'min' => true),
            'admin' => array('path' => 'css/', 'file' => 'admin', 'ext' => 'css', 'min' => true, 'int' => array('shared')),
            'wizard' => array('path' => 'css/', 'file' => 'wizard', 'ext' => 'css', 'min' => true, 'int' => array('admin')),
            'rtl' => array('path' => 'css/', 'file' => 'rtl', 'ext' => 'css', 'min' => true, 'int' => array('admin'))
        )
    );

    public function __construct($base_url, $admin) {
        $this->_url = $base_url;
        $this->_admin = $admin;

        $this->_debug = $this->_admin->is_debug;
    }

    /** @return \Dev4Press\Core\UI\Enqueue */
    public static function instance($base_url, $admin) {
        static $_d4p_lib_loader = array();

        if (!isset($_d4p_lib_loader[$base_url])) {
            $_d4p_lib_loader[$base_url] = new Enqueue($base_url, $admin);
        }

        return $_d4p_lib_loader[$base_url];
    }

    /** @return \Dev4Press\Core\UI\Enqueue */
    public function js($name) {
        $this->add('js', $name);

        return $this;
    }

    /** @return \Dev4Press\Core\UI\Enqueue */
    public function css($name) {
        $this->add('css', $name);

        return $this;
    }

    public function wp($includes = array()) {
        $defaults = array('dialog' => false, 'color_picker' => false, 'media' => false);
        $includes = shortcode_atts($defaults, $includes);

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-form');

        if ($includes['dialog'] === true) {
            wp_enqueue_script('wpdialogs');
            wp_enqueue_style('wp-jquery-ui-dialog');
        }

        if ($includes['color_picker'] === true) {
            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style('wp-color-picker');
        }

        if ($includes['media'] === true) {
            wp_enqueue_media();
        }

        return $this;
    }

    private function add($type, $name) {
        if (isset($this->_libraries[$type][$name])) {
            if (!$this->is_added($type, $name)) {
                $obj = $this->_libraries[$type][$name];
                $req = isset($obj['req']) ? $obj['req'] : array();

                if (isset($obj['int']) && !empty($obj['int'])) {
                    foreach ($obj['int'] as $lib) {
                        $req[] = 'd4plib-'.$lib;

                        if (!$this->is_added($type, $lib)) {
                            $this->add($type, $lib);
                        }
                    }
                }

                $handle = 'd4plib-'.$name;
                $url = $this->url($obj);
                $ver = isset($obj['ver']) ? $obj['ver'] : D4P_VERSION;
                $footer = isset($obj['footer ']) ? $obj['footer '] : true;

                $this->enqueue($type, $handle, $url, $req, $ver, $footer);

                $this->_loaded[$type][] = $name;

                if ($name == 'admin') {
                    $this->localize_admin();
                }

                if ($name == 'media') {
                    $this->localize_media();
                }
            }
        }
    }

    private function url($obj) {
        $path = trailingslashit('resources/'.$obj['path']).$obj['file'];

        if ($obj['min'] && !$this->_debug) {
            $path.= '.min';
        }

        $path.= '.'.$obj['ext'];

        return trailingslashit($this->_url).$path;
    }

    private function is_added($type, $name) {
        return in_array($name, $this->_loaded[$type]);
    }

    private function enqueue($type, $handle, $url, $req, $version, $footer = true) {
        if ($type == 'js') {
            wp_enqueue_script($handle, $url, $req, $version, $footer);
        } else if ($type == 'css') {
            wp_enqueue_style($handle, $url, $req, $version);
        }
    }

    private function localize_admin() {
        wp_localize_script('d4plib-admin', 'd4plib_admin_data', array(
            'dialogs' => array(
                'icons' => array(
                    'ok' => '<i class="fa fa-check fa-fw" aria-hidden="true"></i> ',
                    'cancel' => '<i class="fa fa-times fa-fw" aria-hidden="true"></i> ',
                    'delete' => '<i class="fa fa-trash fa-fw" aria-hidden="true"></i> ',
                    'disable' => '<i class="fa fa-ban fa-fw" aria-hidden="true"></i> ',
                    'empty' => '<i class="fa fa-eraser fa-fw" aria-hidden="true"></i> ',
                ),
                'buttons' => array(
                    'ok' => __("OK", "gd-topic-polls"),
                    'cancel' => __("Cancel", "gd-topic-polls"),
                    'delete' => __("Delete", "gd-topic-polls"),
                    'disable' => __("Disable", "gd-topic-polls"),
                    'empty' => __("Empty", "gd-topic-polls")
                ),
                'titles' => array(
                    'areyousure' => __("Are you sure you want to do this?", "gd-topic-polls")
                ),
                'content' => array(
                    'pleasewait' => __("Please Wait...", "gd-topic-polls")
                )
            )
        ));
    }

    private function localize_media() {
        wp_localize_script('d4plib-media', 'd4plib_media_data', array(
            'strings' => array(
                'image_remove' => __("Remove", "gd-topic-polls"),
                'image_preview' => __("Preview", "gd-topic-polls"),
                'image_title' => __("Select Image", "gd-topic-polls"),
                'image_button' => __("Use Selected Image", "gd-topic-polls"),
                'image_not_selected' => __("Image not selected.", "gd-topic-polls"),
                'are_you_sure' => __("Are you sure you want to do this?", "gd-topic-polls")
            ),
            'icons' => array(
                'remove' => "<i aria-hidden='true' class='fa fa-ban'></i>",
                'preview' => "<i aria-hidden='true' class='fa fa-search'></i>"
            )
        ));
    }
}
