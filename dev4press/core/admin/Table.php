<?php

/*
Name:    Dev4Press\v39\Core\Admin\Table
Version: v3.9
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2022 Milan Petrovic (email: support@dev4press.com)

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

namespace Dev4Press\v39\Core\Admin;

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
		return $this->_request_args[ $name ] ?? $default;
	}

	protected function prepare_column_headers() {
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
	}

	public function get_row_classes( $item ) : array {
		return array();
	}

	public function get_columns() : array {
		return array();
	}

	protected function get_bulk_actions() : array {
		return array();
	}

	public function single_row( $item ) {
		$classes = $this->get_row_classes( $item );

		echo '<tr' . ( empty( $classes ) ? '' : ' class="' . join( ' ', $classes ) . '"' ) . '>';
		$this->single_row_columns( $item );
		echo '</tr>';
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

	public function sanitize_field( $name, $value, $default = '' ) {
		switch ( $name ) {
			case 'orderby':
				if ( in_array( $value, $this->_sanitize_orderby_fields ) ) {
					return $value;
				} else {
					return $default;
				}
			case 'order':
				$value = strtoupper( $value );

				if ( in_array( $value, array( 'ASC', 'DESC' ) ) ) {
					return $value;
				} else {
					return $default;
				}
		}
	}
}
