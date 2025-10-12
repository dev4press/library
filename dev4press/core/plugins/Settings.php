<?php
/**
 * Name:    Dev4Press\v55\Core\Plugins\Settings
 * Version: v5.5
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2025 Milan Petrovic (email: support@dev4press.com)
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

namespace Dev4Press\v55\Core\Plugins;

use Dev4Press\v55\Core\DateTime;
use Dev4Press\v55\Core\Helpers\DB;
use Dev4Press\v55\Core\Quick\WPR;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Settings {
	public string $base = 'd4p';
	public string $plugin = '';

	public string $scope = 'blog';

	public bool $has_db = false;

	public array $current = array();
	public array $settings = array();
	public array $legacy = array();
	public array $temp = array();
	public array $changed = array();

	public array $skip_update = array();
	public array $skip_export = array();
	public array $network_groups = array();
	public Information $info;

	public function __construct() {
		$this->constructor();

		if ( $this->info->is_pro() ) {
			$this->settings['license'] = array(
				'code'      => '',
				'info'      => array(),
				'last'      => array(),
				'check'     => 0,
				'record'    => 'empty',
				'dashboard' => 0,
			);
		}
	}

	public static function instance() : static {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function i() : Information {
		return $this->info;
	}

	public function __get( string $name ) {
		$get = explode( '_', $name, 2 );

		return $this->get( $get[1], $get[0] );
	}

	public function hook( string $name, bool $with_scope = false ) : string {
		return $with_scope ? $this->base . '_' . $this->scope . '_' . $name : $this->base . '_' . $name;
	}

	public function init() : void {
		if ( $this->scope == 'network' ) {
			if ( is_multisite() ) {
				$this->_force_load_sitemeta();
			} else {
				$this->_force_load_options();
			}
		}

		$this->current['info'] = $this->_settings_get( 'info' );

		do_action( $this->hook( 'settings_init' ) );
		do_action( $this->hook( 'settings_init', true ) );

		$installed = is_array( $this->current['info'] ) && isset( $this->current['info']['build'] );

		if ( ! $installed ) {
			$this->_install();
		} else {
			$update = $this->current['info']['build'] != $this->info->build;

			if ( $update ) {
				$this->_update();
			} else {
				$groups = $this->_groups();

				foreach ( $groups as $key ) {
					$this->current[ $key ] = $this->_settings_get( $key );

					if ( in_array( $key, $this->network_groups ) && ! is_array( $this->current[ $key ] ) ) {
						$this->current[ $key ] = $this->_settings_get( $key, 'blog' );

						$this->_settings_update( $key, $this->current[ $key ] );
					}

					if ( ! is_array( $this->current[ $key ] ) ) {
						$data = $this->group( $key );

						if ( ! is_null( $data ) ) {
							$this->current[ $key ] = $data;

							$this->_settings_update( $key, $data );
						}
					}
				}
			}
		}

		do_action( $this->hook( 'settings_loaded', true ) );
		do_action( $this->hook( 'settings_loaded' ) );
	}

	public function group( string $group ) {
		return $this->settings[ $group ] ?? null;
	}

	public function exists( string $name, string $group = 'settings' ) : bool {
		if ( isset( $this->current[ $group ][ $name ] ) ) {
			return true;
		} else if ( isset( $this->settings[ $group ][ $name ] ) ) {
			return true;
		} else {
			return false;
		}
	}

	public function storage_get( string $name, bool $get_defaults = false ) : array {
		return $this->prefix_get( $name . '__', 'storage', $get_defaults );
	}

	public function feature_get( string $name, bool $get_defaults = false ) : array {
		return $this->prefix_get( $name . '__', 'features', $get_defaults );
	}

	public function prefix_get( string $prefix, string $group = 'settings', bool $get_defaults = false ) : array {
		$settings = array_merge( array_keys( $this->settings[ $group ] ), array_keys( $this->current[ $group ] ) );

		$results = array();

		foreach ( $settings as $key ) {
			if ( str_starts_with( $key, $prefix ) ) {
				$new = substr( $key, strlen( $prefix ) );

				$results[ $new ] = $get_defaults ? $this->get_default( $key, $group ) : $this->get( $key, $group );
			}
		}

		return $results;
	}

	public function group_get( string $group, bool $get_defaults = false ) {
		if ( $get_defaults ) {
			return $this->settings[ $group ];
		}

		$default = $this->settings[ $group ] ?? array();
		$current = $this->current[ $group ] ?? array();

		return wp_parse_args( $current, $default );
	}

	public function register_group( string $group ) : void {
		$this->settings[ $group ] = array();
	}

	public function register( string $group, string $name, mixed $value ) : void {
		$this->settings[ $group ][ $name ] = $value;
	}

	public function register_bulk( string $group, array $values, string $prefix = '' ) : void {
		foreach ( $values as $name => $value ) {
			$this->settings[ $group ][ $prefix . $name ] = $value;
		}
	}

	public function get_default( string $name, string $group = 'settings', $default = null ) {
		return $this->settings[ $group ][ $name ] ?? $default;
	}

	public function raw_get( string $name, string $group = 'settings', $default = null ) {
		return $this->current[ $group ][ $name ] ?? ( $this->settings[ $group ][ $name ] ?? $default );
	}

	public function get( string $name, string $group = 'settings', $default = null ) {
		return apply_filters( $this->base . '_' . $this->scope . '_settings_get', $this->raw_get( $name, $group, $default ), $name, $group );
	}

	public function set( string $name, mixed $value, string $group = 'settings', bool $save = false, bool $silent = false ) : void {
		$old = $this->current[ $group ][ $name ] ?? null;

		$this->current[ $group ][ $name ] = $value;

		if ( ! $silent && $old != $value ) {
			do_action( $this->hook( 'settings_value_changed' ), $name, $group, $old, $value );

			if ( ! isset( $this->changed[ $group ] ) ) {
				$this->changed[ $group ] = array();
			}

			$this->changed[ $group ][ $name ] = array(
				'old' => $old,
				'new' => $value,
			);
		}

		if ( $save ) {
			$this->save( $group, $silent );
		}
	}

	public function set_group( string $group, array $values, bool $save = false ) : void {
		$this->current[ $group ] = $values;

		if ( $save ) {
			$this->save( $group );
		}
	}

	public function bulk( array $values, string $group = 'settings', bool $save = false, bool $silent = false, string $prefix = '' ) : void {
		foreach ( $values as $name => $value ) {
			$this->set( $prefix . $name, $value, $group, false, true );
		}

		if ( $save ) {
			$this->save( $group, $silent );
		}
	}

	public function save( string $group, bool $silent = false ) : void {
		$this->_settings_update( $group, $this->current[ $group ] );

		if ( ! $silent ) {
			do_action( $this->hook( 'settings_saved_to_db_' . $group ), $this->changed[ $group ] ?? array() );

			if ( $group == 'license' ) {
				$this->_license_control();
			}
		}
	}

	public function is_install() : bool {
		return (bool) $this->get( 'install', 'info' );
	}

	public function is_update() : bool {
		return (bool) $this->get( 'update', 'info' );
	}

	public function mark_for_update() : void {
		$this->current['info']['update'] = true;

		$this->save( 'info' );
	}

	public function remove_by_prefix( string $prefix, string $group, bool $save = true, bool $silent = false ) : void {
		$keys = array_keys( $this->current[ $group ] );

		foreach ( $keys as $key ) {
			if ( str_starts_with( $key, $prefix ) ) {
				unset( $this->current[ $group ][ $key ] );
			}
		}

		if ( $save ) {
			$this->save( $group, $silent );
		}
	}

	public function remove_plugin_settings() : void {
		$this->_settings_delete( 'info' );

		foreach ( $this->_groups() as $group ) {
			$this->_settings_delete( $group );
		}
	}

	public function remove_plugin_settings_by_group( string $group ) : void {
		$this->_settings_delete( $group );
	}

	public function import_from_object( object|array $import, array $list = array(), bool $silent = false ) : void {
		if ( empty( $list ) ) {
			$list = $this->_groups();
		}

		$import = (array) $import;

		foreach ( $import as $key => $data ) {
			if ( in_array( $key, $list ) ) {
				$this->current[ $key ] = (array) $data;

				$this->save( $key, $silent );;
			}
		}
	}

	public function import_from_secure_json( array $import, array $list = array(), bool $silent = false ) : bool {
		$name = $import['name'] ?? false;
		$ctrl = $import['ctrl'] ?? false;
		$raw  = $import['data'] ?? false;

		$data = gzuncompress( base64_decode( urldecode( $raw ) ) ); // phpcs:ignore Generic.PHP.ForbiddenFunctions

		if ( $ctrl && $data && strlen( $ctrl ) == 64 ) {
			$ctrl        = substr( $ctrl, 8, 32 );
			$size_import = mb_strlen( $data );
			$ctrl_import = $name === false ? md5( $data . $size_import ) : md5( $data . $this->i()->code . $size_import );

			if ( $ctrl_import === $ctrl ) {
				$data = json_decode( $data, true );
				$this->import_from_object( $data, $list, $silent );

				return true;
			}
		}

		return false;
	}

	public function export_to_json( array $list = array() ) : bool|string {
		return wp_json_encode( $this->_settings_to_array( $list ) );
	}

	public function export_to_secure_json( array $list = array() ) : bool|string {
		$export = $this->_settings_to_array( $list );

		$encoded = wp_json_encode( $export );

		if ( $encoded === false ) {
			return false;
		}

		$size = mb_strlen( $encoded );
		$name = $this->i()->code;

		$export = array(
			'name' => $name,
			'ctrl' => strtolower( wp_generate_password( 8, false ) ) . md5( $encoded . $name . $size ) . strtolower( wp_generate_password( 24, false ) ),
			'data' => urlencode( base64_encode( gzcompress( $encoded, 9 ) ) ), // phpcs:ignore Generic.PHP.ForbiddenFunctions
		);

		return wp_json_encode( $export, JSON_PRETTY_PRINT );
	}

	public function file_version() : string {
		return $this->i()->version . '.' . $this->i()->build;
	}

	public function plugin_version() : string {
		return 'v' . $this->i()->version . '_b' . $this->i()->build;
	}

	public function reset_feature( string $name, bool $save = true ) : bool {
		$defaults = $this->feature_get( $name, true );

		if ( ! empty( $defaults ) ) {
			foreach ( $defaults as $key => $value ) {
				$this->set( $name . '__' . $key, $value, 'features' );
			}

			if ( $save ) {
				$this->save( 'features' );
			}

			return true;
		}

		return false;
	}

	public function get_installed_db_version() {
		return $this->current['core']['db_version'] ?? 0;
	}

	public function updated_db_version( $version ) : void {
		$this->set( 'db_version', $version, 'core', true );
	}

	protected function _name( string $name, string $force_scope = '' ) : string {
		return 'd4p_' . $this->_group_scope( $name, $force_scope ) . '_' . $this->info->code . '_' . $name;
	}

	protected function _install() : void {
		$this->current = $this->_merge();

		$this->current['info'] = $this->info->to_array();

		$this->current['info']['install'] = true;
		$this->current['info']['update']  = false;

		if ( isset( $this->current['core'] ) ) {
			$this->current['core']['installed'] = DateTime::instance()->mysql_date();
		}

		foreach ( $this->current as $key => $data ) {
			$this->_settings_update( $key, $data );
		}

		if ( $this->has_db ) {
			$this->_db();
		}
	}

	protected function _update() : void {
		$old_build = $this->current['info']['build'];

		$this->current['info'] = $this->info->to_array();

		$this->current['info']['install']  = false;
		$this->current['info']['update']   = true;
		$this->current['info']['previous'] = $old_build;

		$this->_settings_update( 'info', $this->current['info'] );

		$settings = $this->_merge();

		foreach ( $this->legacy as $key ) {
			$settings[ $key ] = array();
		}

		foreach ( $settings as $key => $data ) {
			$now = $this->_settings_get( $key );

			if ( $now !== false && is_array( $now ) ) {
				$this->temp[ $key ] = $now;
			}

			if ( ! in_array( $key, $this->legacy ) ) {
				if ( ! is_array( $now ) ) {
					$now = $data;
				} else if ( ! in_array( $key, $this->skip_update ) ) {
					$now = $this->_upgrade( $now, $data );
				}

				$this->current[ $key ] = $now;

				if ( $key == 'core' ) {
					$this->current['core']['updated'] = DateTime::instance()->mysql_date();
				}

				$this->_settings_update( $key, $now );
			} else {
				$this->_settings_delete( $key );
			}
		}

		if ( $this->info->is_pro() ) {
			$this->_license_schedule();
		}

		if ( $this->has_db ) {
			$this->_db();
		}

		$this->_migrate();
	}

	protected function _db() : void {
		$installed_version = $this->get_installed_db_version();
		$current_version   = $this->_install_db()->current_version();

		if ( $current_version > $installed_version ) {
			$this->_install_db()->install();

			$this->updated_db_version( $current_version );
		}
	}

	protected function _upgrade( array $old, array $new ) : array {
		foreach ( $new as $key => $value ) {
			if ( ! array_key_exists( $key, $old ) ) {
				$old[ $key ] = $value;
			}
		}

		$unset = array();
		foreach ( $old as $key => $value ) {
			if ( ! array_key_exists( $key, $new ) ) {
				$unset[] = $key;
			}
		}

		if ( ! empty( $unset ) ) {
			foreach ( $unset as $key ) {
				unset( $old[ $key ] );
			}
		}

		return $old;
	}

	protected function _groups() : array {
		return array_keys( $this->settings );
	}

	protected function _merge() : array {
		return $this->settings;
	}

	protected function _force_load_sitemeta() : void {
		$core_options = array( $this->_name( 'info' ) );

		foreach ( $this->_groups() as $group ) {
			$core_options[] = $this->_name( $group );
		}

		$site_id = get_current_network_id();
		$options = DB::instance()->prepare_in_list( $core_options );
		$query   = DB::instance()->prepare( 'SELECT `meta_key`, `meta_value` FROM ' . DB::instance()->sitemeta . " WHERE `meta_key` IN ($options) AND `site_id` = %d", $site_id );
		$options = DB::instance()->get_results( $query );

		foreach ( $options as $option ) {
			$key   = $option->meta_key;
			$cache = "$site_id:$key";

			$option->meta_value = maybe_unserialize( $option->meta_value );

			wp_cache_set( $cache, $option->meta_value, 'site-options' );
		}
	}

	protected function _force_load_options() : void {
		$core_options = array( $this->_name( 'info' ) );

		foreach ( $this->_groups() as $group ) {
			$core_options[] = $this->_name( $group );
		}

		$options = DB::instance()->prepare_in_list( $core_options );
		$options = DB::instance()->get_results( 'SELECT `option_name`, `option_value` FROM ' . DB::instance()->options . " WHERE `option_name` IN ($options)" );

		foreach ( $options as $option ) {
			$option->option_value = maybe_unserialize( $option->option_value );

			wp_cache_set( $option->option_name, $option->option_value, 'options' );
		}
	}

	protected function _settings_get( string $name, string $force_scope = '' ) {
		$scope = $this->_group_scope( $name, $force_scope );
		$_name = $this->_name( $name, $force_scope );

		if ( $scope == 'network' ) {
			return get_site_option( $_name );
		} else {
			return get_option( $_name );
		}
	}

	protected function _settings_delete( string $name, string $force_scope = '' ) : void {
		$scope = $this->_group_scope( $name, $force_scope );
		$_name = $this->_name( $name, $force_scope );

		if ( $scope == 'network' ) {
			delete_site_option( $_name );
		} else {
			delete_option( $_name );
		}
	}

	protected function _settings_update( string $name, mixed $data ) : void {
		$scope = $this->_group_scope( $name );
		$_name = $this->_name( $name );

		if ( $scope == 'network' ) {
			update_site_option( $_name, $data );
		} else {
			update_option( $_name, $data, 'yes' );
		}
	}

	protected function _settings_to_array( array $list = array() ) : array {
		if ( empty( $list ) ) {
			$list = $this->_groups();
			$list = array_diff( $list, $this->skip_export );
		}

		$data = array(
			'info' => $this->current['info'],
		);

		foreach ( $list as $name ) {
			$data[ $name ] = $this->current[ $name ];
		}

		return $data;
	}

	protected function _group_scope( string $name, string $force_scope = '' ) : string {
		$scope = empty( $force_scope ) ? $this->scope : $force_scope;

		if ( empty( $force_scope ) && $scope == 'blog' && in_array( $name, $this->network_groups ) ) {
			$scope = 'network';
		}

		return $scope;
	}

	protected function _license_action() : string {
		return $this->plugin . '-license-validation';
	}

	protected function _license_control() : void {
		if ( isset( $this->changed['license']['code'] ) ) {
			if ( empty( $this->changed['license']['code']['new'] ) ) {
				$this->bulk( array(
					'info'   => array(),
					'last'   => array(),
					'check'  => time(),
					'record' => 'empty',
				), 'license', true, true );
			} else {
				$this->_license_schedule();
			}
		}
	}

	protected function _license_schedule() : void {
		if ( ! empty( $this->plugin ) ) {
			$this->set( 'record', 'in-progress', 'license', true, true );

			do_action( $this->_license_action() );

			if ( ! WPR::is_scheduled_single( $this->_license_action() ) ) {
				wp_schedule_single_event( time() + 5, $this->_license_action() );
			}
		}
	}

	protected function _install_db() {
	}

	protected function _migrate() {
	}

	abstract protected function constructor();
}
