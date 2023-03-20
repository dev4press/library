<?php

/*
Name:    Dev4Press\v40\Core\Admin\Table
Version: v4.0
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>
*/

namespace Dev4Press\v40\WordPress\Admin;

use Dev4Press\v40\Core\Plugins\DBLite;
use Dev4Press\v40\Core\Quick\Sanitize;
use WP_List_Table;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Table extends WP_List_Table {
	public $total = 0;

	public $_request_args = array();
	public $_sanitize_orderby_fields = array();
	public $_checkbox_field = '';
	public $_table_class_name = '';

	public function __construct( $args = array() ) {
		parent::__construct( $args );

		$this->process_request_args();
	}

	protected function get_views() : array {
		return array();
	}

	public function rows_per_page() : int {
		return 0;
	}

	public function get_request_arg( $name, $default = '' ) {
		$value = $this->_request_args[ $name ] ?? $default;

		if ( $name == 'paged' ) {
			if ( empty( $value ) || ! is_numeric( $value ) || $value <= 0 ) {
				$value = 1;
			}

			$value = absint( $value );
		}

		return $value;
	}

	public function get_columns() : array {
		return array();
	}

	public function single_row( $item ) {
		$classes = $this->get_row_classes( $item );

		echo '<tr' . ( empty( $classes ) ? '' : ' class="' . join( ' ', $classes ) . '"' ) . '>';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

	protected function extra_tablenav( $which ) {
		if ( $which == 'top' ) {
			$this->filter_block_top();
		} else if ( $which == 'bottom' ) {
			$this->filter_block_bottom();
		}
	}

	protected function get_row_classes( $item ) : array {
		return array();
	}

	protected function filter_block_top() {

	}

	protected function filter_block_bottom() {

	}

	protected function prepare_column_headers() {
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
	}

	protected function get_bulk_actions() : array {
		return array();
	}

	protected function process_request_args() {
		$this->_request_args = array();
	}

	protected function get_table_classes() : array {
		$classes = parent::get_table_classes();

		if ( ! empty( $this->_table_class_name ) ) {
			$classes[] = $this->_table_class_name;
		}

		return $classes;
	}

	protected function get_sortable_columns() : array {
		return array();
	}

	protected function column_default( $item, $column_name ) : string {
		return (string) $item->$column_name;
	}

	protected function column_cb( $item ) : string {
		$key = $this->_checkbox_field;

		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item->$key );
	}

	protected function query_items( array $sql, int $per_page, bool $do_order = true, bool $do_limit = true, string $index_field = '' ) {
		if ( $do_order ) {
			$sql['order'] = $this->get_request_arg( 'orderby' ) . " " . $this->get_request_arg( 'order' );
		}

		if ( $do_limit ) {
			$paged  = $this->get_request_arg( 'paged' );
			$offset = absint( ( $paged - 1 ) * $per_page );

			$sql['limit'] = $offset . ', ' . $per_page;
		}

		$query = $this->db()->build_query( $sql );

		if ( empty( $index_field ) ) {
			$this->items = $this->db()->run( $query );
		} else {
			$this->items = $this->db()->run_and_index( $query, $index_field );
		}

		$total_rows = $this->db()->get_found_rows();

		$this->set_pagination_args( array(
			'total_items' => $total_rows,
			'total_pages' => ceil( $total_rows / $per_page ),
			'per_page'    => $per_page,
		) );
	}

	protected function db() : ?DBLite {
		return null;
	}

	protected function _get_field( $name, $default = '' ) {
		$value = isset( $_GET[ $name ] ) && ! empty( $_GET[ $name ] ) ? $_GET[ $name ] : $default;

		switch ( $name ) {
			case 'orderby':
				$value = Sanitize::basic( $value );

				if ( ! in_array( $value, $this->_sanitize_orderby_fields ) ) {
					$value = $default;
				}
				break;
			case 'order':
				$value = strtoupper( Sanitize::slug( $value ) );

				if ( ! in_array( $value, array( 'ASC', 'DESC' ) ) ) {
					$value = $default;
				}
				break;
			case 'paged':
				$value = Sanitize::absint( $value );
				break;
			case 's':
				$value = Sanitize::basic( $value );
				break;
		}

		return $value;
	}
}