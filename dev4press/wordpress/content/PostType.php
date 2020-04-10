<?php

/*
Name:    Dev4Press\WordPress\Content\PostType
Version: v3.0.1
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

namespace Dev4Press\WordPress\Content;

if (!defined('ABSPATH')) {
    exit;
}

trait PostType {
    public function generate_labels($singular, $plural) {
        $labels = array(
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'name_admin_bar' => $singular
        );

        $labels['add_new'] = _x("Add New", "Post type label", "gd-knowledge-base");
        $labels['add_new_item'] = sprintf(_x("Add New %s", "Post type label", "gd-knowledge-base"), $singular);
        $labels['edit_item'] = sprintf(_x("Edit %s", "Post type label", "gd-knowledge-base"), $singular);
        $labels['new_item'] = sprintf(_x("New %s", "Post type label", "gd-knowledge-base"), $singular);
        $labels['view_item'] = sprintf(_x("View %s", "Post type label", "gd-knowledge-base"), $singular);
        $labels['search_items'] = sprintf(_x("Search %s", "Post type label", "gd-knowledge-base"), $plural);
        $labels['not_found'] = sprintf(_x("No %s found", "Post type label", "gd-knowledge-base"), $plural);
        $labels['not_found_in_trash'] = sprintf(_x("No %s found in Trash", "Post type label", "gd-knowledge-base"), $plural);
        $labels['parent_item_colon'] = sprintf(_x("Parent %s:", "Post type label", "gd-knowledge-base"), $plural);
        $labels['all_items'] = sprintf(_x("All %s", "Post type label", "gd-knowledge-base"), $plural);
        $labels['archives'] = sprintf(_x("%s Archives", "Post type label", "gd-knowledge-base"), $singular);
        $labels['name_admin_bar'] = $singular;
        $labels['menu_name'] = $plural;
        $labels['insert_into_item'] = sprintf(_x("Insert into %s", "Post type label", "gd-knowledge-base"), $singular);
        $labels['uploaded_to_this_item'] = sprintf(_x("Uploaded to this %s", "Post type label", "gd-knowledge-base"), $singular);
        $labels['featured_image'] = _x("Featured Image", "Post type label", "gd-knowledge-base");
        $labels['set_featured_image'] = _x("Set featured Image", "Post type label", "gd-knowledge-base");
        $labels['remove_featured_image'] = _x("Remove featured Image", "Post type label", "gd-knowledge-base");
        $labels['use_featured_image'] = _x("Use featured Image", "Post type label", "gd-knowledge-base");
        $labels['filter_items_list'] = sprintf(_x("Filter %s list", "Post type label", "gd-knowledge-base"), $plural);
        $labels['items_list_navigation'] = sprintf(_x("%s list navigation", "Post type label", "gd-knowledge-base"), $plural);
        $labels['items_list'] = sprintf(_x("%s list", "Post type label", "gd-knowledge-base"), $plural);
        $labels['view_items'] = sprintf(_x("View %s", "Post type label", "gd-knowledge-base"), $plural);
        $labels['attributes'] = sprintf(_x("%s Attributes", "Post type label", "gd-knowledge-base"), $singular);
        $labels['item_published'] = sprintf(_x("%s published", "Post type label", "gd-knowledge-base"), $singular);
        $labels['item_published_privately'] = sprintf(_x("%s published privately", "Post type label", "gd-knowledge-base"), $singular);
        $labels['item_reverted_to_draft'] = sprintf(_x("%s reverted to draft", "Post type label", "gd-knowledge-base"), $singular);
        $labels['item_scheduled'] = sprintf(_x("%s scheduled", "Post type label", "gd-knowledge-base"), $singular);
        $labels['item_updated'] = sprintf(_x("%s updated", "Post type label", "gd-knowledge-base"), $singular);

        return $labels;
    }
}
