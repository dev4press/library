<?php

namespace Dev4Press\Core\Admin\Network\Menu;

use Dev4Press\Core\Admin\Menu\Plugin as BasePlugin;
use Dev4Press\Core\UI\Enqueue;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Plugin extends BasePlugin {
	public $is_multisite = false;

	public function __construct() {
		if ( is_multisite() ) {
			$this->is_multisite = true;
		}

		$this->consturctor();

		if ( $this->is_multisite ) {
			add_filter( 'network_admin_plugin_action_links', array( $this, 'plugin_actions' ), 10, 2 );
		} else {
			add_filter( 'plugin_action_links', array( $this, 'plugin_actions' ), 10, 2 );
		}

		add_filter( 'plugin_row_meta', array( $this, 'plugin_links' ), 10, 2 );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 20 );
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ), 20 );
	}

	public function main_url() {
		return network_admin_url( 'admin.php?page=' . $this->plugin . '-dashboard' );
	}

	public function current_url( $with_subpanel = true ) {
		$page = 'admin.php?page=' . $this->plugin . '-' . $this->panel;

		if ( $with_subpanel && isset( $this->subpanel ) && $this->subpanel !== false && $this->subpanel != '' ) {
			$page .= '&subpanel=' . $this->subpanel;
		}

		return network_admin_url( $page );
	}

	public function plugins_loaded() {
		$this->is_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		$this->is_rtl   = is_rtl();

		$this->enqueue = Enqueue::instance( $this->url . 'd4plib/', $this );

		add_action( 'admin_init', array( $this, 'admin_init' ) );

		if ( $this->is_multisite ) {
			add_action( 'network_admin_menu', array( $this, 'admin_menu' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		add_action( 'current_screen', array( $this, 'current_screen' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function global_admin_notices() {
		if ( $this->settings()->is_install() ) {
			if ( $this->is_multisite ) {
				add_action( 'network_admin_notices', array( $this, 'install_notice' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'install_notice' ) );
			}
		}

		if ( $this->settings()->is_update() ) {
			if ( $this->is_multisite ) {
				add_action( 'network_admin_notices', array( $this, 'update_notice' ) );
			} else {
				add_action( 'admin_notices', array( $this, 'update_notice' ) );
			}
		}
	}

	public function current_screen( $screen ) {
		if ( $this->is_multisite ) {
			$this->screen_id = $screen->id;

			$parts = explode( '_page_', $this->screen_id, 2 );
			if ( isset( $parts[1] ) ) {
				$parts[1] = substr( $parts[1], 0, strlen( $parts[1] ) - 8 );
				$panel    = substr( $parts[1], 0, strlen( $this->plugin ) ) == $this->plugin ? substr( $parts[1], strlen( $this->plugin ) + 1 ) : '';

				if ( ! empty( $panel ) ) {
					if ( isset( $this->menu_items[ $panel ] ) ) {
						$this->page  = true;
						$this->panel = $panel;

						if ( isset( $_GET['subpanel'] ) && ! empty( $_GET['subpanel'] ) ) {
							$this->subpanel = d4p_sanitize_slug( $_GET['subpanel'] );
						}

						$this->screen_setup();
					}
				}

				$this->global_admin_notices();
			}
		} else {
			parent::current_screen( $screen );
		}
	}
}
