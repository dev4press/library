<?php

/*
Name:    Dev4Press\WordPress\Customizer\Section\Link
Version: v3.1.4
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

namespace Dev4Press\WordPress\Customizer\Section;

use WP_Customize_Section;

if (!defined('ABSPATH')) {
    exit;
}

class Link extends WP_Customize_Section {
    public $type = 'd4p-section-link';

    public $url = '';
    public $backcolor = '';
    public $textcolor = '';

    protected function render() {
        $_back = !empty($this->backcolor) ? esc_attr($this->backcolor) : '#ffffff';
        $_text = !empty($this->textcolor) ? esc_attr($this->textcolor) : '#555d66';

        ?>
        <li id="accordion-section-<?php echo esc_attr($this->type); ?>"
                class="d4p-link-section accordion-section control-section control-section-<?php echo esc_attr($this->id); ?> cannot-expand">
            <h3 class="d4p-link-section-title" <?php echo ' style="color:'.$_text.';border-left-color:'.$_back.';border-right-color:'.$_back.';"'; ?>>
                <a href="<?php echo esc_url($this->url); ?>" rel="noopener"
                        target="_blank"<?php echo ' style="background-color:'.$_back.';color:'.$_text.';"'; ?>><?php echo esc_html($this->title); ?></a>
            </h3>
        </li>
        <?php

    }
}
