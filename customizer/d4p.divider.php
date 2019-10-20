<?php

/*
Name:    d4pLib - Customizer - Divider
Version: v2.8.1
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

class d4p_customizer_ctrl_divider extends WP_Customize_Control {
    protected function render_content() {
        $input_id         = '_customize-input-' . $this->id;
        $description_id   = '_customize-description-' . $this->id;

        ?>
        <hr />
        <?php if ( ! empty( $this->label ) ) : ?>
            <label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
        <?php endif;
        if ( ! empty( $this->description ) ) : ?>
            <span id="<?php echo esc_attr( $description_id ); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
        <?php endif;
    }
}
