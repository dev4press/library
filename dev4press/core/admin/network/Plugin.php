<?php

namespace Dev4Press\v42\Core\Admin\Network;

use Dev4Press\v42\Core\Admin\Menu\Plugin as BasePlugin;
use Dev4Press\v42\Core\Quick\Sanitize;
use Dev4Press\v42\Core\UI\Enqueue;
use Dev4Press\v42\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Plugin extends BasePlugin {
	public $is_multisite = false;

	public function plugins_loaded() {
		$this->is_debug = WordPress::instance()->is_script_debug();

		$this->enqueue = Enqueue::instance( $this );

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu_items' ), 1 );

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
			if ( isset( $parts[ 1 ] ) ) {
				$parts[ 1 ] = substr( $parts[ 1 ], 0, strlen( $parts[ 1 ] ) - 8 );
				$panel      = substr( $parts[ 1 ], 0, strlen( $this->plugin ) ) == $this->plugin ? substr( $parts[ 1 ], strlen( $this->plugin ) + 1 ) : '';

				if ( ! empty( $panel ) ) {
					if ( isset( $this->menu_items[ $panel ] ) ) {
						$this->page  = true;
						$this->panel = $panel;

						if ( ! empty( $_GET[ 'subpanel' ] ) ) {
							$this->subpanel = Sanitize::slug( $_GET[ 'subpanel' ] );
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
