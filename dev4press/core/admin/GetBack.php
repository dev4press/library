<?php

/*
Name:    Dev4Press\Core\Admin\GetBack
Version: v3.0
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

namespace Dev4Press\Core\Admin;

abstract class GetBack {
    /** @var \Dev4Press\Core\Admin\Plugin|\Dev4Press\Core\Admin\Menu\Plugin|\Dev4Press\Core\Admin\Submenu\Plugin */
    private $admin = null;

    public function __construct($admin) {
        $this->admin = $admin;

        $this->process();
    }

    public function a() {
        return $this->admin;
    }

    protected function process() {
        if ($this->a()->panel == 'dashboard') {
            if (isset($_GET['action']) && $_GET['action'] == 'dismiss-topic-prefix') {
                $this->front_dismiss_plugin('notice_gdtox_hide');
            }

            if (isset($_GET['action']) && $_GET['action'] == 'dismiss-topic-polls') {
                $this->front_dismiss_plugin('notice_gdpol_hide');
            }

            if (isset($_GET['action']) && $_GET['action'] == 'dismiss-bbpress-toolbox') {
                $this->front_dismiss_plugin('notice_gdbbx_hide');
            }

            if (isset($_GET['action']) && $_GET['action'] == 'dismiss-power-search') {
                $this->front_dismiss_plugin('notice_gdpos_hide');
            }

            if (isset($_GET['action']) && $_GET['action'] == 'dismiss-quantum-theme') {
                $this->front_dismiss_plugin('notice_gdqnt_hide');
            }

            if (isset($_GET['action']) && $_GET['action'] == 'dismiss-members-directory') {
                $this->front_dismiss_plugin('notice_gdmed_hide');
            }
        }

        if ($this->a()->panel == 'tools') {
            if (isset($_GET['run']) && $_GET['run'] == 'export') {
                $this->tools_export();
            }
        }
    }

    protected function front_dismiss_plugin($option) {
        $this->a()->settings()->set($option, true, 'core', true);

        wp_redirect($this->a()->current_url(false));
        exit;
    }

    protected function tools_export() {
        check_ajax_referer('dev4press-plugin-'.$this->a()->plugin_prefix);

        if (!d4p_is_current_user_admin()) {
            wp_die(__("Only administrators can use export features.", "d4plib"));
        }

        $data = $this->a()->settings()->export_to_secure_json();

        if ($data !== false) {
            $export_date = date('Y-m-d-H-m-s');
            $export_name = $this->a()->plugin.'-settings-'.$export_date.'.json';

            header('Content-type: application/force-download');
            header('Content-Disposition: attachment; filename="'.$export_name.'"');

            die($data);
        } else {
            wp_redirect();
            exit;
        }
    }

    protected function is_single_action($name) {
        return isset($_GET['single-action']) && $_GET['single-action'] == $name;
    }

    protected function is_bulk_action() {
        return (isset($_GET['action']) && $_GET['action'] != '-1') || (isset($_GET['action2']) && $_GET['action2'] != '-1');
    }

    protected function get_bulk_action() {
        $action = isset($_GET['action']) && $_GET['action'] != '' && $_GET['action'] != '-1' ? $_GET['action'] : '';

        if ($action == '') {
            $action = isset($_GET['action2']) && $_GET['action2'] != '' && $_GET['action2'] != '-1' ? $_GET['action2'] : '';
        }

        return $action;
    }
}
