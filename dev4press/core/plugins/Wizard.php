<?php
/**
 * Name:    Dev4Press\v43\Core\Plugins\Wizard
 * Version: v4.3
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4Press Library
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
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

namespace Dev4Press\v43\Core\Plugins;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Wizard {
	public $panel = false;
	public $panels = array();
	public $default = array();

	public function __construct() {
		$this->init_panels();
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function setup_panel( $panel ) {
		$this->panel = $panel;

		if ( ! isset( $this->panels[ $panel ] ) || $panel === false || is_null( $panel ) ) {
			$this->panel = 'intro';
		}
	}

	public function current_panel() {
		return $this->panel;
	}

	public function panels_index() : array {
		return array_keys( $this->panels );
	}

	public function next_panel() {
		$panel = $this->current_panel();
		$all   = $this->panels_index();

		$index = array_search( $panel, $all );
		$next  = $index + 1;

		if ( $next == count( $all ) ) {
			$next = 0;
		}

		return $all[ $next ];
	}

	public function is_last_panel() : bool {
		$panel = $this->current_panel();
		$all   = $this->panels_index();

		$index = array_search( $panel, $all );

		return $index + 1 == count( $all );
	}

	public function get_form_action() : string {
		return $this->a()->panel_url( 'wizard', $this->current_panel() );
	}

	public function get_form_nonce_key( $panel ) : string {
		return $this->a()->plugin_prefix . '-wizard-nonce-' . $panel;
	}

	public function get_form_nonce() {
		return wp_create_nonce( $this->get_form_nonce_key( $this->current_panel() ) );
	}

	public function panel_postback() {
		$post = $_POST[ $this->a()->plugin_prefix ]['wizard'];
		$goto = $this->a()->panel_url();

		$this->setup_panel( $post['_page'] );

		if ( wp_verify_nonce( $post['_nonce'], $this->get_form_nonce_key( $this->current_panel() ) ) ) {
			$data = isset( $post[ $this->current_panel() ] ) ? (array) $post[ $this->current_panel() ] : array();

			switch ( $this->current_panel() ) {
				case 'intro':
				case 'editors':
				case 'features':
				case 'integrations':
				case 'finish':
					$this->postback_default( $this->current_panel(), $data );
					break;
			}

			if ( $this->current_panel() != 'finish' ) {
				$goto = $this->a()->panel_url( 'wizard', $this->next_panel() );
			}
		} else {
			$goto = $this->a()->panel_url( 'wizard', $this->current_panel() );
		}

		wp_redirect( $goto );
		exit;
	}

	public function render_hidden_elements() {
		$_name = $this->a()->plugin_prefix . '[wizard]';

		?>

        <input type="hidden" name="<?php echo $_name; ?>[_nonce]" value="<?php echo $this->get_form_nonce(); ?>"/>
        <input type="hidden" name="<?php echo $_name; ?>[_page]" value="<?php echo $this->current_panel(); ?>"/>
        <input type="hidden" name="<?php echo $this->a()->plugin_prefix; ?>_handler" value="postback"/>
        <input type="hidden" name="option_page" value="<?php echo $this->a()->plugin; ?>-wizard"/>

		<?php
	}

	public function render_yes_no( $panel, $name, $value = 'yes' ) {
		$_name = $this->a()->plugin_prefix . '[wizard][' . $panel . '][' . $name . ']';
		$_id   = $this->a()->plugin_prefix . '-wizard-' . $panel . '-' . $name;

		?>

        <span>
			<input type="radio" name="<?php echo esc_attr( $_name ); ?>" value="yes" id="<?php echo esc_attr( $_id ); ?>-yes"<?php echo $value == 'yes' ? ' checked' : ''; ?>/>
			<label for="<?php echo esc_attr( $_id ); ?>-yes"><?php esc_html_e( 'Yes', 'd4plib' ); ?></label>
		</span>
        <span>
			<input type="radio" name="<?php echo esc_attr( $_name ); ?>" value="no" id="<?php echo esc_attr( $_id ); ?>-no"<?php echo $value == 'no' ? ' checked' : ''; ?>/>
			<label for="<?php echo esc_attr( $_id ); ?>-no"><?php esc_html_e( 'No', 'd4plib' ); ?></label>
		</span>

		<?php
	}

	protected function postback_default( $panel, $data ) {
		$map    = $this->default[ $panel ] ?? array();
		$groups = array();

		foreach ( $map as $key => $settings ) {
			$value = $data[ $key ] ?? 'no';
			$value = in_array( $value, array( 'yes', 'no' ) ) ? $value : 'no';

			foreach ( $settings as $s ) {
				$group = $s[0];
				$keys  = (array) $s[1];
				$set   = $s[2][ $value ];

				if ( ! in_array( $group, $groups ) ) {
					$groups[] = $group;
				}

				foreach ( $keys as $key ) {
					$this->a()->settings()->set( $key, $set, $group );
				}
			}
		}

		foreach ( $groups as $group ) {
			$this->a()->settings()->save( $group );
		}
	}

	/** @return \Dev4Press\v42\Core\Admin\Plugin */
	abstract public function a();

	abstract protected function init_panels();
}
