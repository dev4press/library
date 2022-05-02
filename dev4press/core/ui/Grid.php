<?php

/*
Name:    Dev4Press\v38\Core\UI\Grid
Version: v3.8
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

namespace Dev4Press\v38\Core\UI;

use Dev4Press\v38\Core\Quick\Sanitize;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Grid {
	protected $default_orderby = 'id';
	protected $items_per_page = 20;
	protected $show_search = true;
	protected $prefix = 'd4plib';
	protected $table_classes = '';
	protected $table_columns = array();
	protected $sortable_columns = array();

	protected $sortables = array(
		'up'   => '▲',
		'down' => '▼'
	);
	protected $vars = array();
	protected $filters = array();

	protected $pager;
	protected $sql;
	protected $items;
	protected $total;

	protected $current_url;

	public function __construct() {
		$this->current_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$this->parse_args();
		$this->table_init();
	}

	public function prepare() {

	}

	public function display() {
		?>
        <table class="d4p-grid-table <?php echo $this->table_classes; ?>">
			<?php $this->header(); ?>
            <tbody>
			<?php $this->rows(); ?>
            </tbody>
        </table>
		<?php
	}

	public function has_items() : bool {
		return ! empty( $this->items );
	}

	protected function table_init() {

	}

	protected function no_items() {
		_e( 'No items found.' );
	}

	protected function current_page() {
		if ( empty( $this->filters['paged'] ) || ! is_numeric( $this->filters['paged'] ) || $this->filters['paged'] <= 0 ) {
			return 1;
		}

		return $this->filters['paged'];
	}

	protected function parse_args() {
		$this->filters['order']   = isset( $_GET['order'] ) && strtoupper( $_GET['order'] ) === 'ASC' ? 'ASC' : 'DESC';
		$this->filters['orderby'] = isset( $_GET['orderby'] ) && ! empty( $_GET['orderby'] ) ? Sanitize::basic( $_GET['orderby'] ) : $this->default_orderby;
		$this->filters['search']  = isset( $_GET['search'] ) && ! empty( $_GET['search'] ) ? Sanitize::basic( $_GET['search'] ) : '';
		$this->filters['paged']   = isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ? Sanitize::absint( $_GET['paged'] ) : 1;

		foreach ( $this->vars as $key => $method ) {
			$real = $this->prefix . '-' . $key;

			if ( isset( $_GET[ $real ] ) ) {
				$this->filters[ $key ] = Sanitize::$method( $_GET[ $real ] );
			}
		}
	}

	protected function orderby_value() {
		$columns = $this->sortable_columns;

		return $columns[ $this->filters['orderby'] ] ?? $this->filters['orderby'];
	}

	protected function header() {
		?>
        <thead>
        <tr>
			<?php

			$current_url = remove_query_arg( 'paged', $this->current_url );

			$columns  = $this->table_columns;
			$sortable = $this->sortable_columns;

			foreach ( $columns as $column_key => $column_label ) {
				$class = array( 'grid-column', 'column-' . $column_key );

				if ( isset( $sortable[ $column_key ] ) ) {
					if ( $column_key === $this->filters['orderby'] ) {
						$class[] = 'sorted';
						$class[] = strtolower( $this->filters['order'] );

						$order = $this->filters['order'] === 'ASC' ? 'DESC' : 'ASC';
					} else {
						$class[] = 'sortable';
						$class[] = 'desc';

						$order = 'DESC';
					}

					$icon = $order == 'DESC' ? $this->sortables['up'] : $this->sortables['down'];
					$url  = add_query_arg( 'orderby', $column_key, $current_url );
					$url  = add_query_arg( 'order', strtolower( $order ), $url );

					$column_label = sprintf( '<a href="%s"><span>%s</span><span class="sorting-icon">%s</span></a>', esc_url( $url ), $column_label, $icon );
				}

				echo '<th scope="col" class="' . join( ' ', $class ) . '">' . $column_label . '</th>';
			}

			?>
        </tr>
        </thead>
		<?php
	}

	protected function rows() {
		if ( $this->has_items() ) {
			foreach ( $this->items as $item ) {
				$this->row( $item );
			}
		} else {
			echo '<tr class="no-items"><td class="colspanchange" colspan="' . count( $this->table_columns ) . '">';
			$this->no_items();
			echo '</td></tr>';
		}
	}

	protected function row( $item ) {
		echo '<tr>';

		$columns = $this->table_columns;

		foreach ( $columns as $column_name => $column_label ) {
			echo '<td data-label="' . esc_attr( $column_label ) . '" class="column-' . $column_name . '">';
			if ( method_exists( $this, 'column_' . $column_name ) ) {
				call_user_func( array( $this, 'column_' . $column_name ), $item );
			} else {
				$this->column_default( $item, $column_name );
			}
			echo '</td>';
		}

		echo '</tr>';
	}

	protected function column_default( $item, $column_name ) {

	}

	protected function complete() {
		$this->pager = array(
			'per_page'    => $this->items_per_page,
			'total_items' => $this->total,
			'total_pages' => ceil( $this->total / $this->items_per_page )
		);
	}
}
