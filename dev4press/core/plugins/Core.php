<?php
/**
 * Name:    Dev4Press\v56\Core\Plugins\Core
 * Version: v5.6
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2026 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v56\Core\Plugins;

use Dev4Press\v56\API\Store;
use Dev4Press\v56\Core\DateTime;
use Dev4Press\v56\Core\Quick\BBP;
use Dev4Press\v56\Core\Quick\KSES;
use Dev4Press\v56\Library;
use Dev4Press\v56\WordPress;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Core {
	public bool $is_debug = false;

	public bool $widgets = false;
	public bool $enqueue = false;
	public bool $features = false;
	public bool $license = false;

	public string $cap = 'activate_plugins';
	public string $svg_icon = '';
	public string $plugin = '';
	public string $plugin_prefix = '';
	public string $url = '';
	public string $path = '';

	protected array $_system_requirements = array();
	protected array $_widget_instance = array();
	protected int $_plugins_loaded_priority = 10;
	protected int $_after_setup_theme_priority = 10;
	protected string $_library_code = 'v56';

	protected function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), $this->_plugins_loaded_priority );
		add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ), $this->_after_setup_theme_priority );
	}

	/** @deprecated 5.5.0 Use self::i() instead. To be removed in 5.7.0. */
	public static function instance() : static {
		return static::i();
	}

	public static function i() : static {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function datetime() : DateTime {
		return Library::i()->datetime();
	}

	public function edition() : string {
		if ( $this->license ) {
			return $this->fs()->can_use_premium_code__premium_only() ? 'pro' : 'lite';
		}

		return 'free';
	}

	public function edition_label( bool $only_pro = false ) : string {
		$edition = $this->edition();
		$label   = $edition == 'pro' ? 'Pro' : ( $edition == 'lite' ? 'Lite' : 'Free' );

		return ( $only_pro && $edition == 'pro' ) || ! $only_pro ? $label : '';
	}

	public function plugins_loaded() : void {
		$this->is_debug = WordPress::i()->is_script_debug();

		if ( $this->widgets === true || ! empty( $this->widgets ) ) {
			add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		}

		if ( $this->enqueue ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		$this->load_textdomain();

		$this->_system_requirements = $this->check_system_requirements();

		if ( ! empty( $this->_system_requirements ) ) {
			if ( is_admin() ) {
				add_action( 'admin_notices', array( $this, 'system_requirements_notices' ) );
			} else {
				$this->deactivate();
			}
		} else {
			$this->init_capabilities();
			$this->run();
		}
	}

	public function load_textdomain() : void {
		load_plugin_textdomain( $this->plugin, false, $this->plugin . '/languages' );
		load_plugin_textdomain( 'd4plib', false, $this->plugin . '/' . Library::i()->base_path() . '/languages' );
	}

	public function init_capabilities() : void {
		$role = get_role( 'administrator' );

		if ( ! is_null( $role ) ) {
			$role->add_cap( $this->cap );
		} else {
			$this->cap = 'activate_plugins';
		}
	}

	public function plugin_name() : string {
		return $this->plugin . '/' . $this->plugin . '.php';
	}

	public function deactivate() : void {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		deactivate_plugins( $this->plugin_name() );
	}

	public function recommend() : string {
		return Store::i()->render( $this->plugin );
	}

	public function after_setup_theme() : void {
	}

	public function widgets_init() {
	}

	public function enqueue_scripts() {
	}

	public function system_requirements_notices() : void {
		$plugin   = $this->s()->i()->name();
		$versions = array();

		foreach ( $this->_system_requirements as $req ) {
			if ( $req[1] == 0 ) {
				/* translators: Plugin activation system requirements notice, not active. %1$s: Requirement Name. %2$s: Requirements Version. %3$s: Requirement Name with markup. */
				$versions[] = sprintf( _x( '%1$s version %2$s (%3$s is not active on your website)', 'System requirement version', 'd4plib' ), $req[0], '<strong>' . $req[2] . '</strong>', '<strong style="color: #900;">' . $req[0] . '</strong>' );
			} else {
				/* translators: Plugin activation system requirements notice, not valid. %1$s: Requirement Name. %2$s: Requirements Version. %3$s: Active version. */
				$versions[] = sprintf( _x( '%1$s version %2$s (your system runs version %3$s)', 'System requirement version', 'd4plib' ), $req[0], '<strong>' . $req[2] . '</strong>', '<strong style="color: #900;">' . $req[1] . '</strong>' );
			}
		}

		$render = '<div class="notice notice-error"><p>';
		/* translators: Plugin activation system requirements notice, failed. %1$s: Plugin Name. %2$s: Requirements list. */
		$render .= sprintf( _x( 'System requirements check for %1$s failed. This plugin requires %2$s. The plugin will now be disabled.', 'System requirement notice', 'd4plib' ), '<strong>' . $plugin . '</strong>', join( ', ', $versions ) );
		$render .= '</p></div>';

		echo KSES::standard( $render ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$this->deactivate();
	}

	public function store_widget_instance( $instance ) : void {
		$this->_widget_instance = (array) $instance;
	}

	public function widget_instance() : array {
		return $this->_widget_instance;
	}

	protected function check_system_requirements() : array {
		if ( DEV4PRESS_NO_SYSREQ_CHECK ) {
			return array();
		}

		global $wpdb;

		$list = array();

		$cms = $this->s()->i()->requirement_version( WordPress::i()->cms() );

		if ( WordPress::i()->is_version_equal_or_higher( $cms ) === false ) {
			$list[] = array( WordPress::i()->cms_title(), WordPress::i()->version(), $cms );
		}

		$php = $this->s()->i()->requirement_version( 'php' );

		if ( version_compare( Library::i()->php_version(), $php, '>=' ) === false ) {
			$list[] = array( 'PHP', Library::i()->php_version(), $php );
		}

		$mysql = $this->s()->i()->requirement_version( 'mysql' );

		if ( version_compare( $wpdb->db_version(), $mysql, '>=' ) === false ) {
			$list[] = array( 'MySQL', $wpdb->db_version(), $mysql );
		}

		$bbpress = $this->s()->i()->requirement_version( 'bbpress' );

		if ( $bbpress !== false ) {
			if ( BBP::is_active() ) {
				$installed = bbp_get_version();

				if ( version_compare( $installed, $bbpress, '>=' ) === false ) {
					$list[] = array( 'bbPress', $installed, $bbpress );
				}
			} else {
				$list[] = array( 'bbPress', 0, $bbpress );
			}
		}

		return $list;
	}

	public function hook( string $name ) : string {
		return $this->plugin_prefix . '_' . $name;
	}

	public function fs() {
		return null;
	}

	abstract public function run();

	/** @return NULL|\Dev4Press\v56\Core\Plugins\Settings */
	abstract public function s();

	/** @return NULL|\Dev4Press\v56\Core\Plugins\Settings */
	abstract public function b();

	/** @return NULL|\Dev4Press\v56\Core\Features\Load */
	abstract public function f();
}
