<?php

/*
Name:    Dev4Press\WordPress\Customizer\Control\Divider
Version: v2.9.0
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

namespace Dev4Press\WordPress\Customizer\Control;

use Dev4Press\WordPress\Customizer\Control;

if (!defined('ABSPATH')) { exit; }

class Divider extends Control {
    public $type = 'd4p-ctrl-divider';

    protected function render_content() {
        ?>
        <hr/>
        <?php if (!empty($this->label)) : ?>
            <label for="_customize-input-<?php echo esc_attr($this->id); ?>"
                   class="customize-control-title"><?php echo esc_html($this->label); ?></label>
        <?php endif;
        if (!empty($this->description)) : ?>
            <span id="_customize-description-<?php echo esc_attr($this->id); ?>"
                  class="description customize-control-description"><?php echo $this->description; ?></span>
        <?php endif;
    }
}
