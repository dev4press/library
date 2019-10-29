<?php

/*
Name:    d4pLib - Customizer - Check Color
Version: v2.8.2
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2019 Milan Petrovic (email: support@dev4press.com)

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

if (!defined('ABSPATH')) exit;

class d4p_customizer_ctrl_alpha_color extends WP_Customize_Control {
    public $type = 'd4p-ctrl-alpha-color';

    public $attributes = "";
    public $defaultPalette = array(
        '#000000',
        '#ffffff',
        '#dd3333',
        '#dd9933',
        '#eeee22',
        '#81d742',
        '#1e73be',
        '#8224e3',
    );

    public function __construct($manager, $id, $args = array(), $options = array()) {
        parent::__construct($manager, $id, $args);

        $this->attributes.= 'data-default-color="'.esc_attr($this->value()).'"';
        $this->attributes.= 'data-alpha="'.(isset($this->input_attrs['alpha']) ? $this->input_attrs['alpha'] : 'true').'"';
        $this->attributes.= 'data-reset-alpha="'.(isset($this->input_attrs['resetalpha']) ? $this->input_attrs['resetalpha'] : 'true').'"';
        $this->attributes.= 'data-custom-width="0"';
    }

    public function to_json() {
        parent::to_json();

        $this->json['colorpickerpalette'] = isset( $this->input_attrs['palette'] ) ? $this->input_attrs['palette'] : $this->defaultPalette;
    }

    public function render_content() {
        ?>
        <div class="wpcolorpicker_alpha_color_control">
            <?php if (!empty($this->label)) { ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php } ?>
            <?php if (!empty($this->description)) { ?>
                <span class="customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php } ?>
            <input type="text" class="color-picker" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($this->value()); ?>" class="customize-control-colorpicker-alpha-color" <?php echo $this->attributes; ?> <?php $this->link(); ?> />
        </div>
        <?php
    }
}
