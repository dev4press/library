<?php

/*
Name:    Dev4Press\Core\Options\Render
Version: v3.1.1
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace Dev4Press\Core\Options;

use Dev4Press\Core\UI\Walker\CheckboxRadio;

if (!defined('ABSPATH')) {
    exit;
}

class Render {
    public $base = 'd4pvalue';
    public $kb = 'https://support.dev4press.com/kb/%type%/%url%/';

    public $panel;
    public $groups;

    public function __construct($base) {
        $this->base = $base;
    }

    /** @return \Dev4Press\Core\Options\Render */
    public static function instance($base = 'd4pvalue') {
        static $render = array();

        if (!isset($render[$base])) {
            $render[$base] = new Render($base);
        }

        return $render[$base];
    }

    public function prepare($panel, $groups) {
        $this->panel = $panel;
        $this->groups = $groups;

        return $this;
    }

    public function render() {
        foreach ($this->groups as $group => $obj) {
            if (isset($obj['type']) && $obj['type'] == 'separator') {
                echo '<div class="d4p-group-separator">';
                echo '<h3><span>'.$obj['label'].'</span></h3>';
                echo '</div>';
            } else {
                $args = isset($obj['args']) ? $obj['args'] : array();

                $classes = array('d4p-group', 'd4p-group-'.$group);

                if (isset($args['hidden']) && $args['hidden']) {
                    $classes[] = 'd4p-hidden-group';
                }

                if (isset($args['class']) && $args['class'] != '') {
                    $classes[] = $args['class'];
                }

                echo '<div class="'.join(' ', $classes).'" id="d4p-group-'.$group.'">';
                $kb = isset($obj['kb']) ? str_replace('%url%', $obj['kb']['url'], $this->kb) : '';

                if ($kb != '') {
                    $type = isset($obj['kb']['type']) ? $obj['kb']['type'] : 'article';
                    $kb = str_replace('%type%', $type, $kb);

                    $kb = '<a class="d4p-kb-group" href="'.$kb.'" target="_blank" rel="noopener">'.$obj['kb']['label'].'</a>';
                }

                echo '<h3>'.$obj['name'].$kb.'</h3>';
                echo '<div class="d4p-group-inner">';

                if (isset($obj['settings'])) {
                    $obj['sections'] = array('label' => '', 'name' => '', 'class' => '', 'settings' => $obj['settings']);
                    unset($obj['settings']);
                }

                foreach ($obj['sections'] as $section) {
                    $this->render_section($section, $group);
                }

                echo '</div>';
                echo '</div>';
            }
        }
    }

    private function get_id($name, $id = '') {
        if (!empty($id)) {
            return $id;
        }

        return str_replace('[', '_', str_replace(']', '', $name));
    }

    private function render_section($section, $group) {
        $class = 'd4p-settings-section';

        if (!empty($section['name'])) {
            $class .= ' d4p-section-'.$section['name'];
        }

        echo '<div class="'.$class.'">';

        if (!empty($section['label'])) {
            echo '<h4><span>'.$section['label'].'</span></h4>';
        }

        echo '<table class="form-table d4p-settings-table">';
        echo '<tbody>';

        foreach ($section['settings'] as $setting) {
            $this->render_option($setting, $group);
        }

        echo '</tbody>';
        echo '</table>';

        echo '</div>';
    }

    private function render_option($setting, $group) {
        $name_base = $this->base.'['.$setting->type.']['.$setting->name.']';
        $id_base = $this->get_id($name_base);
        $call_function = array($this, 'draw_'.$setting->input);

        $name = !empty($setting->name) ? $setting->name : 'element-'.$setting->input;

        $wrapper_class = 'd4p-settings-item-row-'.$name;

        if (isset($setting->args['wrapper_class']) && !empty($setting->args['wrapper_class'])) {
            $wrapper_class .= ' '.$setting->args['wrapper_class'];
        }

        $class = 'd4p-setting-wrapper d4p-setting-'.$setting->input;

        if (isset($setting->args['class']) && !empty($setting->args['class'])) {
            $class .= ' '.$setting->args['class'];
        }

        if ($setting->input == 'custom') {
            echo '<tr valign="top" class="d4p-settings-option-custom '.$wrapper_class.'">';
            echo '<td colspan="2">';

            echo '<div class="'.$class.'">';
            echo $setting->notice;
            echo '</div>';

            echo '</td>';
            echo '</tr>';
        } else if ($setting->input == 'hidden') {
            do_action('d4p_settings_group_hidden_top', $setting, $group);

            $this->call($call_function, $setting, $name_base, $id_base);

            do_action('d4p_settings_group_hidden_bottom', $setting, $group);
        } else {
            $wrapper_class = 'd4p-settings-option-'.($setting->input == 'info' ? 'info' : 'item').' '.$wrapper_class;

            echo '<tr class="'.$wrapper_class.'">';

            if (isset($setting->args['readonly']) && $setting->args['readonly']) {
                $class .= 'd4p-setting-disabled';
            }

            echo '<th scope="row">';
            if (empty($setting->name)) {
                echo '<span>'.$setting->title.'</span>';
            } else {
                echo '<span id="'.$id_base.'__label">'.$setting->title.'</span>';
            }
            echo '</th><td>';
            echo '<div class="'.$class.'">';

            do_action('d4p_settings_group_top', $setting, $group);

            $this->call($call_function, $setting, $name_base, $id_base);

            if ($setting->notice != '' && $setting->input != 'info') {
                echo '<div class="d4p-description">'.$setting->notice.'</div>';
            }

            do_action('d4p_settings_group_bottom', $setting, $group);

            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
    }

    private function call($call_function, $setting, $name_base, $id_base) {
        call_user_func($call_function, $setting, $setting->value, $name_base, $id_base);
    }

    private function _pair_element($name, $id, $i, $value, $element, $hide = false) {
        echo '<div class="pair-element-'.$i.'" style="display: '.($hide ? 'none' : 'block').'">';
        echo '<label>'.$element->args['label_key'].':</label>';
        echo '<input type="text" name="'.esc_attr($name).'[key]" id="'.esc_attr($id).'_key" value="'.esc_attr($value['key']).'" class="widefat" />';

        echo '<label>'.$element->args['label_value'].':</label>';
        echo '<input type="text" name="'.esc_attr($name).'[value]" id="'.esc_attr($id).'_value" value="'.esc_attr($value['value']).'" class="widefat" />';

        echo '<a role="button" class="button-secondary" href="#">'.$element->args['label_buttom_remove'].'</a>';
        echo '</div>';
    }

    private function _text_element($name, $id, $i, $value, $element, $hide = false) {
        echo '<li class="exp-text-element exp-text-element-'.$i.'" style="display: '.($hide ? 'none' : 'list-item').'">';

        $button = isset($element->args['label_buttom_remove']) && $element->args['label_buttom_remove'] != '';
        $button_width = isset($element->args['width_button_remove']) ? intval($element->args['width_button_remove']) : 100;
        $type = isset($element->args['type']) && !empty($element->args['type']) ? $element->args['type'] : 'text';

        $style_input = '';
        $style_button = '';
        if ($button) {
            $style_input = ' style="width: calc(100% - '.($button_width + 10).'px);"';
            $style_button = ' style="width: '.$button_width.'px;"';
        }

        echo '<input aria-labelledby="'.$id.'__label" type="'.$type.'" name="'.esc_attr($name).'[value]" id="'.esc_attr($id).'_value" value="'.esc_attr($value).'" class="widefat"'.$style_input.' />';

        if ($button) {
            echo '<a role="button" class="button-secondary" href="#"'.$style_button.'>'.$element->args['label_buttom_remove'].'</a>';
        }

        echo '</li>';
    }

    private function draw_text($element, $value, $name_base, $id_base, $type = 'text') {
        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';
        $placeholder = isset($element->args['placeholder']) && !empty($element->args['placeholder']) ? ' placeholder="'.$element->args['placeholder'].'"' : '';
        $type = isset($element->args['type']) && !empty($element->args['type']) ? $element->args['type'] : $type;

        echo sprintf('<input aria-labelledby="%s__label"%s%s type="%s" name="%s" id="%s" value="%s" class="widefat" />',
            $id_base, $readonly, $placeholder, $type, esc_attr($name_base), esc_attr($id_base), esc_attr($value));
    }

    private function draw_html($element, $value, $name_base, $id_base) {
        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';

        echo sprintf('<textarea aria-labelledby="%s__label"%s name="%s" id="%s" class="widefat">%s</textarea>',
            $id_base, $readonly, esc_attr($name_base), esc_attr($id_base), esc_textarea($value));
    }

    private function draw_number($element, $value, $name_base, $id_base) {
        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';

        $min = isset($element->args['min']) ? ' min="'.esc_attr(floatval($element->args['min'])).'"' : '';
        $max = isset($element->args['max']) ? ' max="'.esc_attr(floatval($element->args['max'])).'"' : '';
        $step = isset($element->args['step']) ? ' step="'.esc_attr(floatval($element->args['step'])).'"' : '';

        echo sprintf('<input aria-labelledby="%s__label"%s type="number" name="%s" id="%s" value="%s" class="widefat"%s%s%s />',
            $id_base, $readonly, esc_attr($name_base), esc_attr($id_base), esc_attr($value), $min, $max, $step);

        if (isset($element->args['label_unit'])) {
            echo '<span class="d4p-field-unit">'.$element->args['label_unit'].'</span>';
        }
    }

    private function draw_integer($element, $value, $name_base, $id_base) {
        if (!isset($element->args['step'])) {
            $element->args['step'] = 1;
        }

        $this->draw_number($element, $value, $name_base, $id_base);
    }

    private function draw_checkboxes_hierarchy($element, $value, $name_base, $id_base, $multiple = true) {
        switch ($element->source) {
            case 'function':
                $data = call_user_func($element->data);
                break;
            default:
            case '':
            case 'array':
                $data = $element->data;
                break;
        }

        $value = is_null($value) || $value === true ? array_keys($data) : (array)$value;

        if ($multiple) {
            $this->part_check_uncheck_all();
        }

        $walker = new CheckboxRadio();
        $input = $multiple ? 'checkbox' : 'radio';

        echo '<div class="d4p-content-wrapper">';
        echo '<ul class="d4p-wrapper-hierarchy">';
        echo $walker->walk($data, 0, array('input' => $input, 'id' => $id_base, 'name' => $name_base, 'selected' => $value));
        echo '</ul>';
        echo '</div>';
    }

    private function draw_checkboxes($element, $value, $name_base, $id_base, $multiple = true) {
        switch ($element->source) {
            case 'function':
                $data = call_user_func($element->data);
                break;
            default:
            case '':
            case 'array':
                $data = $element->data;
                break;
        }

        $value = is_null($value) || $value === true ? array_keys($data) : (array)$value;
        $associative = d4p_is_array_associative($data);

        if ($multiple) {
            $this->part_check_uncheck_all();
        }

        foreach ($data as $key => $title) {
            $real_value = $associative ? $key : $title;
            $sel = in_array($real_value, $value) ? ' checked="checked"' : '';

            echo sprintf('<label><input type="%s" value="%s" name="%s%s"%s class="widefat" />%s</label>',
                $multiple ? 'checkbox' : 'radio', esc_attr($real_value), esc_attr($name_base), $multiple == 1 ? '[]' : '', $sel, $title);
        }
    }

    private function draw_group_multi($element, $value, $name_base, $id_base, $multiple = true) {
        switch ($element->source) {
            case 'function':
                $data = call_user_func($element->data);
                break;
            default:
                $data = $element->data;
                break;
        }

        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';

        d4p_render_grouped_select($data, array('selected' => $value, 'readonly' => $readonly, 'name' => $name_base, 'id' => $id_base, 'class' => 'widefat', 'multi' => $multiple));
    }

    private function draw_select_multi($element, $value, $name_base, $id_base, $multiple = true) {
        switch ($element->source) {
            case 'function':
                $data = call_user_func($element->data);
                break;
            default:
                $data = $element->data;
                break;
        }

        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';

        d4p_render_select($data, array('selected' => $value, 'readonly' => $readonly, 'name' => $name_base, 'id' => $id_base, 'class' => 'widefat', 'multi' => $multiple), array('aria-labelledby' => $id_base.'__label'));
    }

    private function draw_expandable_text($element, $value, $name_base, $id_base = '') {
        echo '<ol>';

        $this->_text_element($name_base.'[0]', $id_base.'_0', 0, '', $element, true);

        $i = 1;

        if (array($value) && !empty($value)) {
            foreach ($value as $val) {
                $this->_text_element($name_base.'['.$i.']', $id_base.'_'.$i, $i, $val, $element);
                $i++;
            }
        }

        echo '</ol>';

        $label = isset($element->args['label_button_add']) ? $element->args['label_button_add'] : __("Add New Value", "d4plib");

        echo '<a role="button" class="button-primary" href="#">'.$label.'</a>';
        echo '<input type="hidden" value="'.esc_attr($i).'" class="d4p-next-id" />';
    }

    private function draw_info($element, $value, $name_base, $id_base = '') {
        echo $element->notice;
    }

    private function draw_images($element, $value, $name_base, $id_base = '') {
        $value = (array)$value;

        echo '<a role="button" href="#" class="button d4plib-button-inner d4plib-images-add"><i aria-hidden="true" class="d4p-icon d4p-ui-photo"></i> '.__("Add Image", "d4plib").'</a>';

        echo '<div class="d4plib-selected-image" data-name="'.$name_base.'">';

        echo '<div style="display: '.(empty($value) ? "block" : "none").'" class="d4plib-images-none"><span class="d4plib-image-name">'.__("No images selected.", "d4plib").'</span></div>';

        foreach ($value as $id) {
            $image = get_post($id);
            $title = '('.$image->ID.') '.$image->post_title;
            $media = wp_get_attachment_image_src($id, 'full');
            $url = $media[0];

            echo "<div class='d4plib-images-image'>";
            echo "<input type='hidden' value='".esc_attr($id)."' name='".esc_attr($name_base)."[]' />";
            echo "<a class='button d4plib-button-action d4plib-images-remove' aria-label='".__("Remove", "d4plib")."'><i aria-hidden='true' class='d4p-icon d4p-ui-cancel'></i></a>";
            echo "<a class='button d4plib-button-action d4plib-images-preview' aria-label='".__("Preview", "d4plib")."'><i aria-hidden='true' class='d4p-icon d4p-ui-search'></i></a>";
            echo "<span class='d4plib-image-name'>".$title."</span>";
            echo "<img src='".$url."' />";
            echo "</div>";
        }

        echo '</div>';
    }

    private function draw_image($element, $value, $name_base, $id_base = '') {
        echo sprintf('<input class="d4plib-image" type="hidden" name="%s" id="%s" value="%s" />',
            $name_base, $id_base, esc_attr($value));

        echo '<a role="button" href="#" class="button d4plib-button-inner d4plib-image-add"><i aria-hidden="trye" class="d4p-icon d4p-ui-photo"></i> '.__("Select Image", "d4plib").'</a>';
        echo '<a role="button" style="display: '.($value > 0 ? "inline-block" : "none").'" href="#" class="button d4plib-button-inner d4plib-image-preview"><i aria-hidden="true" class="d4p-icon d4p-ui-search"></i> '.__("Show Image", "d4plib").'</a>';
        echo '<a role="button" style="display: '.($value > 0 ? "inline-block" : "none").'" href="#" class="button d4plib-button-inner d4plib-image-remove"><i aria-hidden="true" class="d4p-icon d4p-ui-cancel"></i> '.__("Clear Image", "d4plib").'</a>';

        echo '<div class="d4plib-selected-image">';
        $title = __("Image not selected.", "d4plib");
        $url = '';

        if ($value > 0) {
            $image = get_post($value);
            $title = '('.$image->ID.') '.$image->post_title;
            $media = wp_get_attachment_image_src($value, 'full');
            $url = $media[0];
        }

        echo '<span class="d4plib-image-name">'.$title.'</span>';
        echo '<img src="'.$url.'" />';
        echo '</div>';
    }

    private function draw_hidden($element, $value, $name_base, $id_base = '') {
        echo sprintf('<input type="hidden" name="%s" id="%s" value="%s" />',
            esc_attr($name_base), esc_attr($id_base), esc_attr($value));
    }

    private function draw_bool($element, $value, $name_base, $id_base = '') {
        $selected = $value == 1 || $value === true ? ' checked="checked"' : '';
        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly="readonly" disabled="disabled"' : '';
        $label = isset($element->args['label']) && $element->args['label'] != '' ? $element->args['label'] : __("Enabled", "d4plib");
        $value = isset($element->args['value']) && $element->args['value'] != '' ? $element->args['value'] : 'on';

        echo sprintf('<label for="%s"><input%s type="checkbox" name="%s" id="%s"%s class="widefat" value="%s" /><span class="d4p-accessibility-show-for-sr">%s: </span>%s</label>',
            esc_attr($id_base), $readonly, esc_attr($name_base), esc_attr($id_base), $selected, $value, $element->title, $label);
    }

    private function draw_x_by_y($element, $value, $name_base, $id_base, $cls = '') {
        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';

        $pairs = explode('x', $value);

        echo sprintf('<label for="%s_x"><span class="d4p-accessibility-show-for-sr">%s - X: </span></label><input%s type="text" name="%s[x]" id="%s_x" value="%s" class="widefat" />',
            $id_base, $element->title, $readonly, esc_attr($name_base), esc_attr($id_base), esc_attr($pairs[0]));
        echo ' x ';
        echo sprintf('<label for="%s_y"><span class="d4p-accessibility-show-for-sr">%s - Y: </span></label><input%s type="text" name="%s[y]" id="%s_y" value="%s" class="widefat" />',
            $id_base, $element->title, $readonly, esc_attr($name_base), esc_attr($id_base), esc_attr($pairs[1]));
    }

    private function draw_listing($element, $value, $name_base, $id_base) {
        $this->draw_html($element, join(D4P_EOL, $value), $name_base, $id_base);
    }

    private function draw_code($element, $value, $name_base, $id_base) {
        $mode = isset($element->args['mode']) && $element->args['mode'] ? $element->args['mode'] : 'htmlmixed';

        wp_enqueue_code_editor(array('type' => 'text/html'));

        $this->draw_html($element, $value, $name_base, $id_base);

        echo '<script type="text/javascript">
(function($){
    $(function(){
        var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};

        editorSettings.codemirror = _.extend({}, editorSettings.codemirror, 
            { indentWithTabs: false, indentUnit: 2, tabSize: 2, mode: \''.$mode.'\' }
        );

        var editor = wp.codeEditor.initialize($(\'#'.$id_base.'\'), editorSettings);                        
    });
})(jQuery);
</script>';
    }

    private function draw_textarea($element, $value, $name_base, $id_base) {
        $this->draw_html($element, $value, $name_base, $id_base);
    }

    private function draw_slug($element, $value, $name_base, $id_base) {
        $this->draw_text($element, $value, $name_base, $id_base);
    }

    private function draw_slug_ext($element, $value, $name_base, $id_base) {
        $this->draw_text($element, $value, $name_base, $id_base);
    }

    private function draw_slug_slash($element, $value, $name_base, $id_base) {
        $this->draw_text($element, $value, $name_base, $id_base);
    }

    private function draw_link($element, $value, $name_base, $id_base) {
        if (!isset($element->args['placeholder'])) {
            $element->args['placeholder'] = 'http://';
        }

        $this->draw_text($element, $value, $name_base, $id_base, 'url');
    }

    private function draw_email($element, $value, $name_base, $id_base) {
        $this->draw_text($element, $value, $name_base, $id_base, 'email');
    }

    private function draw_text_html($element, $value, $name_base, $id_base) {
        $this->draw_text($element, $value, $name_base, $id_base);
    }

    private function draw_password($element, $value, $name_base, $id_base) {
        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';
        $autocomplete = isset($element->args['autocomplete']) ? d4p_sanitize_slug($element->args['autocomplete']) : 'off';

        echo sprintf('<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="password" name="%s" id="%s" value="%s" class="widefat" autocomplete="'.$autocomplete.'" />',
            $id_base, $element->title, $readonly, esc_attr($name_base), esc_attr($id_base), esc_attr($value));
    }

    private function draw_file($element, $value, $name_base, $id_base) {
        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';

        echo sprintf('<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="file" name="%s" id="%s" value="%s" class="widefat" />',
            $id_base, $element->title, $readonly, esc_attr($name_base), esc_attr($id_base), esc_attr($value));
    }

    private function draw_color($element, $value, $name_base, $id_base) {
        $readonly = isset($element->args['readonly']) && $element->args['readonly'] ? ' readonly' : '';

        echo sprintf('<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="text" name="%s" id="%s" value="%s" class="widefat d4p-color-picker" />',
            $id_base, $element->title, $readonly, esc_attr($name_base), esc_attr($id_base), esc_attr($value));
    }

    private function draw_absint($element, $value, $name_base, $id_base) {
        $this->draw_integer($element, $value, $name_base, $id_base);
    }

    private function draw_select($element, $value, $name_base, $id_base) {
        $this->draw_select_multi($element, $value, $name_base, $id_base, false);
    }

    private function draw_group($element, $value, $name_base, $id_base) {
        $this->draw_group_multi($element, $value, $name_base, $id_base, false);
    }

    private function draw_radios_hierarchy($element, $value, $name_base, $id_base) {
        $this->draw_checkboxes_hierarchy($element, $value, $name_base, $id_base, false);
    }

    private function draw_radios($element, $value, $name_base, $id_base) {
        $this->draw_checkboxes($element, $value, $name_base, $id_base, false);
    }

    private function draw_expandable_pairs($element, $value, $name_base, $id_base = '') {
        $this->_pair_element($name_base.'[0]', $id_base.'_0', 0, array('key' => '', 'value' => ''), $element, true);

        $i = 1;
        foreach ($value as $key => $val) {
            $this->_pair_element($name_base.'['.$i.']', $id_base.'_'.$i, $i, array('key' => $key, 'value' => $val), $element);
            $i++;
        }

        echo '<a role="button" class="button-primary" href="#">'.$element->args['label_button_add'].'</a>';
        echo '<input type="hidden" value="'.esc_attr($i).'" class="d4p-next-id" />';
    }

    private function draw_expandable_raw($element, $value, $name_base, $id_base = '') {
        $this->draw_expandable_text($element, $value, $name_base, $id_base);
    }

    private function part_check_uncheck_all() {
        echo '<div class="d4p-check-uncheck">';

        echo '<a href="#checkall" class="d4p-check-all"><i class="d4p-icon d4p-ui-check-box"></i> '.__("Check All", "d4plib").'</a>';
        echo '<a href="#uncheckall" class="d4p-uncheck-all"><i class="d4p-icon d4p-ui-box"></i> '.__("Uncheck All", "d4plib").'</a>';

        echo '</div>';
    }
}