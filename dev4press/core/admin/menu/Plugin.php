<?php

namespace Dev4Press\Core\Admin\Menu;

use Dev4Press\Core\Admin\Plugin as BasePlugin;

abstract class Plugin extends BasePlugin {
    public $menu_items;

    public function current_url($with_panel = true, $with_task = true) {

    }

    public function main_url() {

    }
}
