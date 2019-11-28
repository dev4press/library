<?php

namespace Dev4Press\Core\Admin\Submenu;

use Dev4Press\Core\Admin\Plugin as BasePlugin;

abstract class Plugin extends BasePlugin {
    protected $menu = 'options-general.php';

    public function current_url($with_subpanel = true) {
        $page = $this->main_url();

        if ($this->panel !== false && $this->panel != '') {
            $page.= '&panel='.$this->panel;
        }

        if ($with_subpanel && isset($this->subpanel) && $this->subpanel !== false && $this->subpanel != '') {
            $page.= '&subpanel='.$this->subpanel;
        }

        return self_admin_url($page);
    }

    public function main_url() {
        return self_admin_url($this->menu.'?page='.$this->plugin);
    }

    public function admin_menu() {

    }

    public function current_screen($screen) {

    }
}
