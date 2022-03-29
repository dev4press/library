<?php

namespace Dev4Press\v38\Core\Admin\Menu;

use Dev4Press\v38\Core\Admin\Plugin as BasePlugin;
use Dev4Press\v38\Core\Quick\Sanitize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Plugin extends BasePlugin {
	public $variant = 'menu';

	public function main_url() : string {
		return self_admin_url( 'admin.php?page=' . $this->plugin . '-dashboard' );
	}

	public function current_url( $with_subpanel = true ) : string {
		$page = 'admin.php?page=' . $this->plugin . '-' . $this->panel;

		if ( $with_subpanel && isset( $this->subpanel ) && $this->subpanel !== false && $this->subpanel != '' ) {
			$page .= '&subpanel=' . $this->subpanel;
		}

		return self_admin_url( $page );
	}

	public function panel_url( $panel = 'dashboard', $subpanel = '' ) : string {
		$page = 'admin.php?page=' . $this->plugin . '-' . $panel;

		if ( ! empty( $subpanel ) && $subpanel != 'index' ) {
			$page .= '&subpanel=' . $subpanel;
		}

		return self_admin_url( $page );
	}

	public function admin_menu() {
		$parent = $this->plugin . '-dashboard';

		add_menu_page(
			$this->plugin_title,
			$this->plugin_menu,
			$this->menu_cap,
			$parent,
			array( $this, 'admin_panel' ),
			$this->svg_icon() );

		foreach ( $this->menu_items as $item => $data ) {
			$this->page_ids[] = add_submenu_page( $parent,
				$this->plugin_title . ': ' . $data['title'],
				$data['title'],
				$this->menu_cap,
				$this->plugin . '-' . $item,
				array( $this, 'admin_panel' ) );
		}

		$this->admin_load_hooks();
	}

	public function current_screen( $screen ) {
		$this->screen_id = $screen->id;

		$parts = explode( '_page_', $this->screen_id, 2 );
		$panel = isset( $parts[1] ) && substr( $parts[1], 0, strlen( $this->plugin ) ) == $this->plugin ? substr( $parts[1], strlen( $this->plugin ) + 1 ) : '';

		if ( ! empty( $panel ) ) {
			if ( isset( $this->menu_items[ $panel ] ) ) {
				$this->page  = true;
				$this->panel = $panel;

				if ( isset( $_GET['subpanel'] ) && ! empty( $_GET['subpanel'] ) ) {
					$this->subpanel = Sanitize::slug( $_GET['subpanel'] );
				}

				$this->screen_setup();
			}
		}

		$this->global_admin_notices();
	}
}
