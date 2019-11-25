<?php

namespace Dev4Press\Core\Admin\Submenu;

use Dev4Press\Core\Admin\Plugin as BasePlugin;

abstract class Plugin extends BasePlugin {
    protected $menu = 'options-general.php';

    public function current_url($with_panel = true, $with_task = true) {
        $page = $this->menu.'?page='.$this->plugin;

        if ($with_panel && $this->panel !== false && $this->panel != '') {
            $page.= '&panel='.$this->panel;
        }

        if ($with_task && isset($this->task) && $this->task !== false && $this->task != '') {
            $page.= '&task='.$this->task;
        }

        return self_admin_url($page);
    }

    public function main_url() {
        return $this->menu.'?page='.$this->plugin;
    }
}
