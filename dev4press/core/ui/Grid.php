<?php
/**
 * Name:    Dev4Press\v53\Core\UI\Grid
 * Version: v5.3
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

namespace Dev4Press\v53\Core\UI;

use Dev4Press\v53\Core\Plugins\DBLite;
use Dev4Press\v53\Core\Quick\Sanitize;
use Dev4Press\v53\Core\Quick\URL;
use Dev4Press\v53\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Grid {
	protected string $default_orderby = 'id';
	protected int $items_per_page = 20;
	protected bool $show_search = true;
	protected string $prefix = 'd4plib';
	protected string $grid_classes = '';
	protected array $table_columns = array();
	protected array $sortable_columns = array();

	protected array $sortables = array(
		'up'    => '▲',
		'down'  => '▼',
		'first' => '«',
		'prev'  => '‹',
		'next'  => '›',
		'last'  => '»',
	);
	protected array $vars = array();
	protected array $filters = array();

	protected $pager;
	protected $sql;
	protected $items;
	protected $total;

	protected string $current_url;

	public function __construct() {
		$this->current_url = URL::current_url();

		$this->parse_args();
		$this->table_init();
	}

	public function prepare() {

	}

	public function display() {
		?>
        <div class="d4p-grid-table-wrapper <?php echo esc_attr( $this->grid_classes ); ?>">
            <form method="get">
				<?php $this->filter(); ?>
                <table class="d4p-grid-table">
					<?php $this->header(); ?>
                    <tbody>
					<?php $this->rows(); ?>
                    </tbody>
                </table>
				<?php $this->pager(); ?>
            </form>
        </div>
		<?php
	}

	public function has_items() : bool {
		return ! empty( $this->items );
	}

	protected function db() : ?DBLite {
		return null;
	}

	protected function get_period_dropdown( $column, $table ) : array {
		global $wp_locale;

		$sql    = $this->get_period_dropdown_sql( $column, $table );
		$months = $this->db()->run( $sql );

		$list = array(
			''              => __( 'All Logged', 'd4plib' ),
			'last-hour'     => __( 'Last hour', 'd4plib' ),
			'last-half-day' => __( 'Last 12 hours', 'd4plib' ),
			'last-day'      => __( 'Last day', 'd4plib' ),
			'last-week'     => __( 'Last 7 days', 'd4plib' ),
			'last-month'    => __( 'Last 30 days', 'd4plib' ),
			'last-year'     => __( 'Last 365 days', 'd4plib' ),
		);

		foreach ( $months as $row ) {
			if ( $row->month > 0 && $row->year > 0 ) {
				$month = zeroise( $row->month, 2 );
				$year  = $row->year;

				if ( ! isset( $list[ $year ] ) ) {
					$list[ $year ] = $year;
				}

				/* translators: Table dropdown for periods. %1$s: Month. %2$s: Year. */
				$list[ $year . '-' . $month ] = sprintf( __( '%1$s %2$s', 'd4plib' ), $wp_locale->get_month( $month ), $year );
			}
		}

		return $list;
	}

	protected function query_items( array $sql, bool $do_order = true, bool $do_limit = true, string $index_field = '' ) {
		if ( $do_order ) {
			$sql['order'] = ( $this->sortable_columns[$this->filters['orderby']] ?? $this->default_orderby ) . ' ' . $this->filters['order'];
		}

		if ( $do_limit ) {
			$paged  = $this->filters['pg'];
			$offset = absint( ( $paged - 1 ) * $this->items_per_page );

			$sql['limit'] = $offset . ', ' . $this->items_per_page;
		}

		$query = $this->db()->build_query( $sql );

		if ( empty( $index_field ) ) {
			$this->items = $this->db()->run( $query );
		} else {
			$this->items = $this->db()->run_and_index( $query, $index_field );
		}

		$this->total = $this->db()->get_found_rows();

		$this->complete();
	}

	protected function get_period_dropdown_sql( $column, $table ) : string {
		return "SELECT DISTINCT YEAR($column) AS year, MONTH($column) AS month FROM $table ORDER BY $column DESC";
	}

	protected function timestamp_to_date( $value, string $split = '<br/>' ) : string {
		$timestamp = Library::instance()->datetime()->timestamp_gmt_to_local( $value );

		return gmdate( 'Y.m.d', $timestamp ) . $split. '@ ' . gmdate( 'H:i:s', $timestamp );
	}

	protected function no_items() {
		esc_html_e( 'No items found.', 'd4plib' );
	}

	protected function current_page() {
		if ( empty( $this->filters['pg'] ) || ! is_numeric( $this->filters['pg'] ) || $this->filters['pg'] <= 0 ) {
			return 1;
		}

		return $this->filters['pg'];
	}

	protected function parse_args() {
		$this->filters['order']   = isset( $_GET['order'] ) && strtoupper( $_GET['order'] ) === 'ASC' ? 'ASC' : 'DESC'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		$this->filters['orderby'] = ! empty( $_GET['orderby'] ) ? Sanitize::text( $_GET['orderby'] ) : $this->default_orderby; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		$this->filters['search']  = ! empty( $_GET['search'] ) ? Sanitize::text( $_GET['search'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		$this->filters['pg']      = ! empty( $_GET['pg'] ) ? absint( $_GET['pg'] ) : 1; // phpcs:ignore WordPress.Security.NonceVerification

		foreach ( $this->vars as $key => $method ) {
			$real = $this->prefix . '-' . $key;

			if ( ! empty( $_GET[ $real ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
				$this->filters[ $key ] = Sanitize::$method( $_GET[ $real ] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
			}
		}
	}

	protected function orderby_value() {
		$columns = $this->sortable_columns;

		return $columns[ $this->filters['orderby'] ] ?? $this->filters['orderby'];
	}

	protected function filter() {
		echo '<div class="d4p-grid-filter">';

		$this->filter_elements();

		if ( $this->show_search ) {
			echo '<input placeholder="' . esc_attr__( 'Search keywords...', 'd4plib' ) . '" type="text" name="search" value="' . esc_attr( $this->filters['search'] ) . '" />';
		}

		echo '<input type="submit" value="' . esc_attr__( 'Filter', 'd4plib' ) . '"/>';
		echo '</div>';
	}

	protected function pager() {
		$current     = $this->pager['current_page'];
		$total_pages = $this->pager['total_pages'];
		$total_items = $this->pager['total_items'];
		$current_url = $this->current_url;

		$page_links = array();

		if ( $total_items > 0 ) {
			$total_pages_before = '<span class="paging-input">';
			$total_pages_after  = '</span></span>';

			$disable_first = false;
			$disable_last  = false;
			$disable_prev  = false;
			$disable_next  = false;

			if ( 1 == $current ) {
				$disable_first = true;
				$disable_prev  = true;
			}
			if ( $total_pages == $current ) {
				$disable_last = true;
				$disable_next = true;
			}

			if ( $disable_first ) {
				$page_links[] = '<span class="tablenav-pages-navspan nav-button disabled" aria-hidden="true">' . esc_html( $this->sortables['first'] ) . '</span>';
			} else {
				$page_links[] = sprintf(
					"<a class='first-page nav-button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
					esc_url( remove_query_arg( 'pg', $current_url ) ),
					esc_html__( 'First page', 'd4plib' ),
					esc_html( $this->sortables['first'] )
				);
			}

			if ( $disable_prev ) {
				$page_links[] = '<span class="tablenav-pages-navspan nav-button disabled" aria-hidden="true">' . esc_html( $this->sortables['prev'] ) . '</span>';
			} else {
				$page_links[] = sprintf(
					"<a class='prev-page nav-button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
					esc_url( add_query_arg( 'pg', max( 1, $current - 1 ), $current_url ) ),
					esc_html__( 'Previous page', 'd4plib' ),
					esc_html( $this->sortables['prev'] )
				);
			}

			$html_current_page = sprintf(
				"%s<input class='current-page' id='current-page-selector' type='number' step='1' min='1' max='%d' name='pg' value='%s' aria-describedby='table-paging' /><span class='tablenav-paging-text'>",
				'<label for="current-page-selector" class="screen-reader-text">' . esc_html__( 'Current Page', 'd4plib' ) . '</label>',
				esc_html( $total_pages ),
				esc_html( $current )
			);

			$html_total_pages = sprintf( "<span class='total-pages'>%s</span>", number_format_i18n( $total_pages ) );
			$page_links[]     = $total_pages_before . sprintf(
				/* translators: Grid control pager. %1$s: Current page. %2$s: Total number of pages. */
					_x( '%1$s of %2$s', 'paging', 'd4plib' ),
					$html_current_page,
					$html_total_pages
				) . $total_pages_after;

			if ( $disable_next ) {
				$page_links[] = '<span class="tablenav-pages-navspan nav-button disabled" aria-hidden="true">' . $this->sortables['next'] . '</span>';
			} else {
				$page_links[] = sprintf(
					"<a class='next-page nav-button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
					esc_url( add_query_arg( 'pg', min( $total_pages, $current + 1 ), $current_url ) ),
					__( 'Next page', 'd4plib' ),
					$this->sortables['next']
				);
			}

			if ( $disable_last ) {
				$page_links[] = '<span class="tablenav-pages-navspan nav-button disabled" aria-hidden="true">' . $this->sortables['last'] . '</span>';
			} else {
				$page_links[] = sprintf(
					"<a class='last-page nav-button' href='%s'><span class='screen-reader-text'>%s</span><span aria-hidden='true'>%s</span></a>",
					esc_url( add_query_arg( 'pg', $total_pages, $current_url ) ),
					__( 'Last page', 'd4plib' ),
					$this->sortables['last']
				);
			}
		}

		$output = '<span class="displaying-num">' . sprintf(
			/* translators: Grid control items. %s: Number of items. */
				_n( '%s item', '%s items', $total_items, 'd4plib' ),
				number_format_i18n( $total_items )
			) . '</span>';

		if ( ! empty( $page_links ) ) {
			$output .= "\n<span class='pagination-links'>" . implode( "\n", $page_links ) . '</span>';
		}

		echo "<div class='d4p-grid-pager'>$output</div>"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	protected function header() {
		?>
        <thead>
        <tr>
			<?php

			$current_url = remove_query_arg( 'pg', $this->current_url );

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

				echo '<th scope="col" class="' . esc_attr( join( ' ', $class ) ) . '">' . $column_label . '</th>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
		echo '<tr class="' . Sanitize::html_classes( $this->row_class( $item ) ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		$columns = $this->table_columns;

		foreach ( $columns as $column_name => $column_label ) {
			echo '<td data-label="' . esc_attr( $column_label ) . '" class="column-' . esc_attr( $column_name ) . '">';
			echo '<div class="cell-wrapper">';
			if ( method_exists( $this, 'column_' . $column_name ) ) {
				call_user_func( array( $this, 'column_' . $column_name ), $item );
			} else {
				$this->column_default( $item, $column_name );
			}
			echo '</div>';
			echo '</td>';
		}

		echo '</tr>';
	}

	protected function row_class( $item ) : string {
		return '';
	}

	protected function filter_elements() {

	}

	protected function column_default( $item, $column_name ) {

	}

	protected function complete() {
		$this->pager = array(
			'per_page'     => $this->items_per_page,
			'total_items'  => $this->total,
			'total_pages'  => ceil( $this->total / $this->items_per_page ),
			'current_page' => $this->filters['pg'],
		);
	}

	abstract protected function table_init();
}
