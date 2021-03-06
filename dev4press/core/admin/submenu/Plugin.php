<?php

namespace Dev4Press\v36\Core\Admin\Submenu;

use Dev4Press\v36\Core\Admin\Plugin as BasePlugin;
use function Dev4Press\v36\Functions\sanitize_slug;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Plugin extends BasePlugin {
	public $variant = 'submenu';

	protected $menu = 'options-general.php';

	public function main_url() : string {
		return self_admin_url( $this->menu . '?page=' . $this->plugin );
	}

	public function current_url( $with_subpanel = true ) : string {
		$url = $this->main_url();

		if ( $this->panel !== false && $this->panel != '' ) {
			$url .= '&panel=' . $this->panel;
		}

		if ( $with_subpanel && isset( $this->subpanel ) && $this->subpanel !== false && $this->subpanel != '' ) {
			$url .= '&subpanel=' . $this->subpanel;
		}

		return $url;
	}

	public function panel_url( $panel = 'dashboard', $subpanel = '' ) : string {
		$url = $this->main_url();

		$url .= '&panel=' . $panel;

		if ( ! empty( $subpanel ) && $subpanel != 'index' ) {
			$url .= '&subpanel=' . $subpanel;
		}

		return $url;
	}

	public function admin_menu() {
		$this->page_ids[] = add_submenu_page( $this->menu, $this->plugin_title, $this->plugin_menu, $this->menu_cap, $this->plugin, array(
			$this,
			'admin_panel'
		) );

		$this->admin_load_hooks();
	}

	public function current_screen( $screen ) {
		if ( ! empty( $this->page_ids[0] ) && $screen->id == $this->page_ids[0] ) {
			$this->page = true;
		}

		if ( $this->page ) {
			if ( isset( $_GET['panel'] ) && ! empty( $_GET['panel'] ) ) {
				$this->panel = sanitize_slug( $_GET['panel'] );
			} else {
				$this->panel = 'dashboard';
			}

			if ( isset( $_GET['subpanel'] ) && ! empty( $_GET['subpanel'] ) ) {
				$this->subpanel = sanitize_slug( $_GET['subpanel'] );
			}

			$this->screen_setup();
		}
	}
}
