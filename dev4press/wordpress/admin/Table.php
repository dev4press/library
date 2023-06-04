<?php

/*
Name:    Dev4Press\v42\Core\Admin\Table
Version: v4.2
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

namespace Dev4Press\v42\WordPress\Admin;

use Dev4Press\v42\Core\Helpers\DB;
use Dev4Press\v42\Core\Plugins\DBLite;
use Dev4Press\v42\Core\Quick\Sanitize;
use WP_List_Table;
use function Dev4Press\v42\Functions\panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Table extends WP_List_Table {
	public $total = 0;

	public $_request_args = array();
	public $_sanitize_orderby_fields = array();
	public $_checkbox_field = '';
	public $_table_class_name = '';
	public $_self_nonce_key = '';
	public $_table_primary_column = '';

	public function __construct( $args = array() ) {
		parent::__construct( $args );

		$this->process_request_args();

		if ( ! empty( $this->_table_primary_column ) ) {
			add_filter( 'list_table_primary_column', array( $this, 'change_primary_column' ) );
		}
	}

	public function change_primary_column( $column ) {
		return $this->_table_primary_column;
	}

	public function rows_per_page() : int {
		return 20;
	}

	public function get_request_arg( $name, $default = '' ) {
		$value = $this->_request_args[ $name ] ?? $default;

		if ( $name == 'paged' ) {
			if ( empty( $value ) || ! is_numeric( $value ) || $value <= 0 ) {
				$value = 1;
			}

			$value = Sanitize::absint( $value );
		}

		return $value;
	}

	public function get_columns() : array {
		return array();
	}

	public function single_row( $item ) {
		$classes = $this->get_row_classes( $item );

		echo '<tr' . ( empty( $classes ) ? '' : ' class="' . esc_attr( join( ' ', $classes ) ) . '"' ) . '>';
		$this->single_row_columns( $item );
		echo '</tr>';
	}

	protected function db() : ?DBLite {
		return null;
	}

	protected function get_views() : array {
		return array();
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
		$this->_column_headers = array(
			$this->get_columns(),
			get_hidden_columns( $this->screen ),
			$this->get_sortable_columns(),
			$this->get_primary_column_name()
		);
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

	public function get_period_dropdown( $column, $table ) : array {
		global $wp_locale;

		$sql    = "SELECT DISTINCT YEAR($column) AS year, MONTH($column) AS month FROM $table ORDER BY $column DESC";
		$months = $this->db()->run( $sql );

		$list = array(
			''              => __( "All Logged" ),
			'last-hour'     => __( "Last hour" ),
			'last-half-day' => __( "Last 12 hours" ),
			'last-day'      => __( "Last day" ),
			'last-week'     => __( "Last 7 days" ),
			'last-month'    => __( "Last 30 days" ),
			'last-year'     => __( "Last 365 days" )
		);

		foreach ( $months as $row ) {
			if ( $row->month > 0 && $row->year > 0 ) {
				$month = zeroise( $row->month, 2 );
				$year  = $row->year;

				if ( ! isset( $list[ $year ] ) ) {
					$list[ $year ] = $year;
				}

				$list[ $year . '-' . $month ] = sprintf( __( "%s %s", "gd-security-toolbox" ), $wp_locale->get_month( $month ), $year );
			}
		}

		return $list;
	}

	protected function column_default( $item, $column_name ) : string {
		return (string) $item->$column_name;
	}

	protected function column_cb( $item ) : string {
		$key = $this->_checkbox_field;

		return sprintf( '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args[ 'singular' ], $item->$key );
	}

	protected function query_items( array $sql, int $per_page, bool $do_order = true, bool $do_limit = true, string $index_field = '' ) {
		if ( $do_order ) {
			$sql[ 'order' ] = $this->get_request_arg( 'orderby' ) . " " . $this->get_request_arg( 'order' );
		}

		if ( $do_limit ) {
			$paged  = $this->get_request_arg( 'paged' );
			$offset = Sanitize::absint( ( $paged - 1 ) * $per_page );

			$sql[ 'limit' ] = $offset . ', ' . $per_page;
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

	protected function _get_field( $name, $default = '' ) {
		$value = ! empty( $_GET[ $name ] ) ? $_GET[ $name ] : $default;

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

	protected function _get_period_where( string $period, string $column ) : string {
		if ( substr( $period, 0, 5 ) == 'last-' ) {
			$periods = array(
				'hour'     => "1 HOUR",
				'half-day' => "12 HOUR",
				'day'      => "1 DAY",
				'week'     => "7 DAY",
				'month'    => "30 DAY",
				'year'     => "365 DAY"
			);

			$key = substr( $period, 5 );

			if ( isset( $periods[ $key ] ) ) {
				$interval = $periods[ $key ];

				return "$column > DATE_SUB(NOW(), INTERVAL $interval)";
			}
		} else if ( strlen( $period ) == 4 ) {
			return $this->db()->prepare( "YEAR($column) = %d", $period );
		} else {
			$date = explode( '-', $period );

			if ( count( $date ) == 2 ) {
				return $this->db()->prepare( "YEAR($column) = %d AND MONTH($column) = %d", $date[ 0 ], $date[ 1 ] );
			}
		}

		return '1=1';
	}

	protected function _get_search_where( array $fields, string $s ) : string {
		$search = '%' . DB::instance()->wpdb()->esc_like( $s ) . '%';
		$where  = array();

		foreach ( $fields as $field ) {
			$where[] = DB::instance()->prepare( "$field LIKE %s", $search );
		}

		return '(' . join( ' OR ', $where ) . ')';
	}

	protected function _self( $args, $getback = false, $nonce = null ) : string {
		$url = panel()->a()->current_url();
		$url .= '&' . $args;

		if ( $getback ) {
			$url .= '&' . panel()->a()->v() . '=getback';
			$url .= '&_wpnonce=' . ( $nonce ?? wp_create_nonce( $this->_self_nonce_key ) );
			$url .= '&_wp_http_referer=' . esc_url( remove_query_arg( '_wpnonce', wp_get_referer() ) );
		}

		return $url;
	}
}
