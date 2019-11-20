<?php

namespace Dev4Press\Core\Options;

class Process {
    public $bool_values = array(true, false);

    public $base = 'd4pvalue';
    public $settings;

    function __construct($settings) {
        $this->settings = $settings;
    }

    public function process($request = false) {
        $list = array();

        if ($request === false) {
            $request = $_REQUEST;
        }

        foreach ($this->settings as $setting) {
            if ($setting->type != '_') {
                $post = isset($request[$this->base][$setting->type]) ? $request[$this->base][$setting->type] : array();

                $list[$setting->type][$setting->name] = $this->process_single($setting, $post);
            }
        }

        return $list;
    }

    public function slug_slashes($key) {
        $key = strtolower($key);
        $key = preg_replace('/[^a-z0-9.\/_\-]/', '', $key);

        return $key;
    }

    public function process_single($setting, $post) {
        $input = $setting->input;
        $key = $setting->name;
        $value = null;

        switch ($input) {
            case 'skip':
            case 'info':
            case 'hr':
            case 'custom':
                $value = null;
                break;
            case 'expandable_raw':
                $value = array();

                foreach ($post[$key] as $id => $data) {
                    if ($id > 0) {
                        $_val = trim(stripslashes($data['value']));

                        if ($_val != '') {
                            $value[] = $_val;
                        }
                    }
                }
                break;
            case 'expandable_text':
                $value = array();

                foreach ($post[$key] as $id => $data) {
                    if ($id > 0) {
                        $_val = d4p_sanitize_basic($data['value']);

                        if (!empty($_val)) {
                            $value[] = $_val;
                        }
                    }
                }
                break;
            case 'expandable_pairs':
                $value = array();

                foreach ($post[$key] as $id => $data) {
                    if ($id > 0) {
                        $_key = d4p_sanitize_basic($data['key']);
                        $_val = d4p_sanitize_basic($data['value']);

                        if (!empty($_key) && !empty($_val)) {
                            $value[$_key] = $_val;
                        }
                    }
                }
                break;
            case 'x_by_y':
                $value = d4p_sanitize_basic($post[$key]['x']).'x'.d4p_sanitize_basic($post[$key]['y']);
                break;
            case 'html':
            case 'code':
            case 'text_html':
            case 'text_rich':
                $value = d4p_sanitize_html($post[$key]);
                break;
            case 'bool':
                $value = isset($post[$key]) ? $this->bool_values[0] : $this->bool_values[1];
                break;
            case 'number':
                $value = floatval($post[$key]);
                break;
            case 'integer':
                $value = intval($post[$key]);
                break;
            case 'absint':
                $value = absint($post[$key]);
                break;
            case 'images':
                if (!isset($post[$key])) {
                    $value = array();
                } else {
                    $value = (array)$post[$key];
                    $value = array_map('intval', $value);
                    $value = array_filter($value);
                }
                break;
            case 'image':
                $value = absint($post[$key]);
                break;
            case 'listing':
                $value = d4p_split_textarea_to_list(stripslashes($post[$key]));
                break;
            case 'media':
                $value = 0;
                if ($post[$key] != '') {
                    $value = absint(substr($post[$key], 3));
                }
                break;
            case 'checkboxes':
            case 'checkboxes_hierarchy':
            case 'select_multi':
            case 'group_multi':
                if (!isset($post[$key])) {
                    $value = array();
                } else {
                    $value = (array)$post[$key];
                    $value = array_map('d4p_sanitize_basic', $value);
                }
                break;
            case 'slug':
                $value = d4p_sanitize_slug($post[$key]);
                break;
            case 'slug_ext':
                $value = d4p_sanitize_key_expanded($post[$key]);
                break;
            case 'slug_slash':
                $value = $this->slug_slashes($post[$key]);
                break;
            case 'link':
                $value = d4p_sanitize_basic($post[$key]);
                break;
            case 'email':
                $value = sanitize_email($post[$key]);
                break;
            default:
            case 'text':
            case 'textarea':
            case 'password':
            case 'color':
            case 'block':
            case 'hidden':
            case 'select':
            case 'radios':
            case 'radios_hierarchy':
            case 'group':
                $value = d4p_sanitize_basic($post[$key]);
                break;
        }

        return $value;
    }
}