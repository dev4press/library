<?php

namespace Dev4Press\Core\Admin\Menu;

use Dev4Press\Core\Admin\Plugin as BasePlugin;

abstract class Plugin extends BasePlugin {
    public function current_url($with_subpanel = true) {
        $page = 'admin.url?page='.$this->plugin.'-'.$this->panel;

        if ($with_subpanel && isset($this->subpanel) && $this->subpanel !== false && $this->subpanel != '') {
            $page.= '&subpanel='.$this->subpanel;
        }

        return self_admin_url($page);
    }

    public function main_url() {
        return self_admin_url('admin.url?page='.$this->plugin.'-dashboard');
    }

    public function admin_menu() {
        $parent = $this->plugin.'-dashboard';

        add_menu_page(
            $this->plugin_title,
            $this->plugin_menu,
            $this->menu_cap,
            $parent,
            array($this, 'admin_panel'),
            gdpol()->svg_icon);

        foreach($this->menu_items as $item => $data) {
            $this->page_ids[] = add_submenu_page($parent,
                $this->plugin_title.': '.$data['title'],
                $data['title'],
                $this->menu_cap,
                $this->plugin.'-'.$item,
                array($this, 'admin_panel'));
        }

        $this->admin_load_hooks();
    }

    public function current_screen($screen) {
        $this->screen_id = $screen->id;

        $parts = explode('_page_', $this->screen_id, 2);
        $panel = isset($parts[1]) && substr($parts[1], 0, strlen($this->plugin)) == $this->plugin ? substr($parts[1], strlen($this->plugin) + 1) : '';

        if (!empty($panel)) {
            if (isset($this->menu_items[$panel])) {
                $this->page = true;
                $this->panel = $panel;

                if (isset($_GET['subpanel']) && !empty($_GET['subpanel'])) {
                    $this->subpanel = d4p_sanitize_slug($_GET['subpanel']);
                }

                $this->install_or_update();
                $this->load_postget_back();
            }
        }

        $this->global_admin_notices();
    }

    public function admin_panel() {
        $panel = $this->panel_object();
        $class = $panel->class;

        $this->object = $class::instance($this);

        d4p_print_r($this);
    }
}
