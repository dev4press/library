<?php

/*
Name:    d4pLib_Admin_Functions
Version: v3.5
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2021 Milan Petrovic (email: support@dev4press.com)

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

use Dev4Press\Core\UI\Walker\CheckboxRadio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'd4p_split_textarea_to_list' ) ) {
	function d4p_split_textarea_to_list( $value, $empty_lines = false ) {
		$elements = preg_split( "/[\n\r]/", $value );

		if ( ! $empty_lines ) {
			$results = array();

			foreach ( $elements as $el ) {
				if ( trim( $el ) != '' ) {
					$results[] = $el;
				}
			}

			return $results;
		} else {
			return $elements;
		}
	}
}

if ( ! function_exists( 'd4p_html_id_from_name' ) ) {
	function d4p_html_id_from_name( $name, $id = '' ) {
		if ( $id == '' ) {
			$id = str_replace( ']', '', $name );
			$id = str_replace( '[', '_', $id );
		} else if ( $id == '_' ) {
			$id = '';
		}

		return $id;
	}
}

if ( ! function_exists( 'd4p_render_select' ) ) {
	function d4p_render_select( $values, $args = array(), $attr = array() ) {
		$defaults = array(
			'selected' => '',
			'name'     => '',
			'id'       => '',
			'class'    => '',
			'style'    => '',
			'multi'    => false,
			'echo'     => true,
			'readonly' => false
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * @var string $selected
		 * @var string $name
		 * @var string $id
		 * @var string $class
		 * @var string $style
		 * @var bool   $multi
		 * @var bool   $echo
		 * @var bool   $readonly
		 */
		extract( $args );

		$render      = '';
		$attributes  = array();
		$selected    = is_null( $selected ) ? array_keys( $values ) : (array) $selected;
		$associative = ! d4p_is_array_associative( $values );
		$id          = d4p_html_id_from_name( $name, $id );

		if ( $class != '' ) {
			$attributes[] = 'class="' . esc_attr( $class ) . '"';
		}

		if ( $style != '' ) {
			$attributes[] = 'style="' . esc_attr( $style ) . '"';
		}

		if ( $multi ) {
			$attributes[] = 'multiple';
		}

		if ( $readonly ) {
			$attributes[] = 'readonly';
		}

		foreach ( $attr as $key => $value ) {
			$attributes[] = $key . '="' . esc_attr( $value ) . '"';
		}

		$name = $multi ? $name . '[]' : $name;

		if ( $id != '' ) {
			$attributes[] = 'id="' . esc_attr( $id ) . '"';
		}

		if ( $name != '' ) {
			$attributes[] = 'name="' . esc_attr( $name ) . '"';
		}

		$render .= '<select ' . join( ' ', $attributes ) . '>';
		foreach ( $values as $value => $display ) {
			$real_value = $associative ? $display : $value;

			$sel    = in_array( $real_value, $selected ) ? ' selected="selected"' : '';
			$render .= '<option value="' . esc_attr( $value ) . '"' . $sel . '>' . esc_html( $display ) . '</option>';
		}
		$render .= '</select>';

		if ( $echo ) {
			echo $render;
		} else {
			return $render;
		}
	}
}

if ( ! function_exists( 'd4p_render_grouped_select' ) ) {
	function d4p_render_grouped_select( $values, $args = array(), $attr = array() ) {
		$defaults = array(
			'selected' => '',
			'name'     => '',
			'id'       => '',
			'class'    => '',
			'style'    => '',
			'multi'    => false,
			'echo'     => true,
			'readonly' => false
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * @var string $selected
		 * @var string $name
		 * @var string $id
		 * @var string $class
		 * @var string $style
		 * @var bool   $multi
		 * @var bool   $echo
		 * @var bool   $readonly
		 */
		extract( $args );

		$render     = '';
		$attributes = array();
		$selected   = (array) $selected;
		$id         = d4p_html_id_from_name( $name, $id );

		if ( $class != '' ) {
			$attributes[] = 'class="' . esc_attr( $class ) . '"';
		}

		if ( $style != '' ) {
			$attributes[] = 'style="' . esc_attr( $style ) . '"';
		}

		if ( $multi ) {
			$attributes[] = 'multiple';
		}

		if ( $readonly ) {
			$attributes[] = 'readonly';
		}

		foreach ( $attr as $key => $value ) {
			$attributes[] = $key . '="' . esc_attr( $value ) . '"';
		}

		$name = $multi ? $name . '[]' : $name;

		if ( $id != '' ) {
			$attributes[] = 'id="' . esc_attr( $id ) . '"';
		}

		if ( $name != '' ) {
			$attributes[] = 'name="' . esc_attr( $name ) . '"';
		}

		$render .= '<select ' . join( ' ', $attributes ) . '>';
		foreach ( $values as $group ) {
			$render .= '<optgroup label="' . $group['title'] . '">';
			foreach ( $group['values'] as $value => $display ) {
				$sel    = in_array( $value, $selected ) ? ' selected="selected"' : '';
				$render .= '<option value="' . esc_attr( $value ) . '"' . $sel . '>' . esc_html( $display ) . '</option>';
			}
			$render .= '</optgroup>';
		}
		$render .= '</select>';

		if ( $echo ) {
			echo $render;
		} else {
			return $render;
		}
	}
}

if ( ! function_exists( 'd4p_render_check_radios' ) ) {
	function d4p_render_check_radios( $values, $args = array() ) {
		$defaults = array(
			'selected' => '',
			'name'     => '',
			'id'       => '',
			'class'    => '',
			'multi'    => true,
			'echo'     => true,
			'readonly' => false
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * @var mixed  $selected
		 * @var string $name
		 * @var string $id
		 * @var string $class
		 * @var bool   $multi
		 * @var bool   $echo
		 * @var bool   $readonly
		 */
		extract( $args );

		$wrapper_class = 'd4p-setting-checkboxes';

		if ( $class != '' ) {
			$wrapper_class .= ' ' . esc_attr( $class );
		}

		$render      = '<div class="' . $wrapper_class . '">';
		$selected    = (array) $selected;
		$associative = d4p_is_array_associative( $values );
		$id          = d4p_html_id_from_name( $name, $id );
		$name        = $multi ? $name . '[]' : $name;

		if ( $multi ) {
			$render .= '<div class="d4p-check-uncheck">';

			$render .= '<a href="#checkall" class="d4p-check-all"><i class="d4p-icon d4p-ui-check-box"></i> ' . __( "Check All", "d4plib" ) . '</a>';
			$render .= '<a href="#uncheckall" class="d4p-uncheck-all"><i class="d4p-icon d4p-ui-box"></i> ' . __( "Uncheck All", "d4plib" ) . '</a>';

			$render .= '</div>';
		}

		$render .= '<div class="d4p-content-wrapper">';
		foreach ( $values as $key => $title ) {
			$real_value = $associative ? $key : $title;

			$attributes = array(
				'type="' . ( $multi ? 'checkbox' : 'radio' ) . '"',
				'value="' . $real_value . '"',
				'class="widefat"'
			);

			if ( $id != '' ) {
				$attributes[] = 'id="' . esc_attr( $id . '-' . $key ) . '"';
			}

			if ( $name != '' ) {
				$attributes[] = 'name="' . esc_attr( $name ) . '"';
			}

			if ( in_array( $real_value, $selected ) ) {
				$attributes[] = 'checked="checked"';
			}

			if ( $readonly ) {
				$attributes[] = 'readonly';
			}

			$render .= sprintf( '<label><input %s />%s</label>',
				join( ' ', $attributes ),
				esc_html( $title ) );
		}
		$render .= '</div>';

		$render .= '</div>';

		if ( $echo ) {
			echo $render;
		} else {
			return $render;
		}
	}
}

if ( ! function_exists( 'd4p_render_check_radios_with_hierarchy' ) ) {
	function d4p_render_check_radios_with_hierarchy( $values, $args = array() ) {
		$defaults = array(
			'selected' => '',
			'name'     => '',
			'id'       => '',
			'class'    => '',
			'style'    => '',
			'multi'    => true,
			'echo'     => true,
			'readonly' => false
		);

		$args = wp_parse_args( $args, $defaults );

		/**
		 * @var string $selected
		 * @var string $name
		 * @var string $id
		 * @var string $class
		 * @var string $style
		 * @var bool   $multi
		 * @var bool   $echo
		 * @var bool   $readonly
		 */
		extract( $args );

		$render      = '<div class="d4p-setting-checkboxes-hierarchy">';
		$attributes  = array();
		$selected    = (array) $selected;
		$associative = d4p_is_array_associative( $values );
		$id          = d4p_html_id_from_name( $name, $id );

		if ( $class != '' ) {
			$attributes[] = 'class="' . esc_attr( $class ) . '"';
		}

		if ( $style != '' ) {
			$attributes[] = 'style="' . esc_attr( $style ) . '"';
		}

		if ( $readonly ) {
			$attributes[] = 'readonly';
		}

		if ( $id != '' ) {
			$attributes[] = 'id="' . esc_attr( $id ) . '"';
		}

		if ( $name != '' ) {
			$attributes[] = 'name="' . esc_attr( $name ) . '"';
		}

		if ( $multi ) {
			$render .= '<div class="d4p-check-uncheck">';

			$render .= '<a href="#checkall" class="d4p-check-all"><i class="d4p-icon d4p-ui-check-box"></i> ' . esc_html__( "Check All", "d4plib" ) . '</a>';
			$render .= '<a href="#uncheckall" class="d4p-uncheck-all"><i class="d4p-icon d4p-ui-box"></i> ' . esc_html__( "Uncheck All", "d4plib" ) . '</a>';

			$render .= '</div>';
		}

		$walker = new CheckboxRadio();
		$input  = $multi ? 'checkbox' : 'radio';

		$render .= '<div class="d4p-content-wrapper">';
		$render .= '<ul class="d4p-wrapper-hierarchy">';
		$render .= $walker->walk( $values, 0, array(
			'input'    => $input,
			'id'       => $id,
			'name'     => $name,
			'selected' => $selected
		) );
		$render .= '</ul>';
		$render .= '</div>';

		$render .= '</div>';

		if ( $echo ) {
			echo $render;
		} else {
			return $render;
		}
	}
}

if ( ! function_exists( 'd4p_render_toggle_block' ) ) {
	function d4p_render_toggle_block( $title, $content, $classes = array() ) {
		$render = '<div class="d4p-section-toggle ' . join( ' ', $classes ) . '">';
		$render .= '<div class="d4p-toggle-title">';
		$render .= '<i class="fa fa-caret-down fa-fw"></i> ' . $title;
		$render .= '</div>';
		$render .= '<div class="d4p-toggle-content" style="display: none;">';
		$render .= $content;
		$render .= '</div>';
		$render .= '</div>';

		return $render;
	}
}

if ( ! function_exists( 'd4p_list_css_size_units' ) ) {
	function d4p_list_css_size_units() {
		return array(
			'px'   => 'px',
			'%'    => '%',
			'em'   => 'em',
			'rem'  => 'rem',
			'in'   => 'in',
			'cm'   => 'cm',
			'mm'   => 'mm',
			'pt'   => 'pt',
			'pc'   => 'pc',
			'ex'   => 'ex',
			'ch'   => 'ch',
			'vw'   => 'vw',
			'vh'   => 'vh',
			'vmin' => 'vmin',
			'vmax' => 'vmax'
		);
	}
}
