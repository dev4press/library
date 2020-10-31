<?php

/*
Name:    Dev4Press\Core\Options\Render
Version: v3.3
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace Dev4Press\Core\Options;

use Dev4Press\Core\UI\Walker\CheckboxRadio;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Render {
	public $base = 'd4pvalue';
	public $prefix = 'd4p';
	public $kb = 'https://support.dev4press.com/kb/%type%/%url%/';

	public $panel;
	public $groups;

	public function __construct( $base, $prefix = 'd4p' ) {
		$this->base   = $base;
		$this->prefix = $prefix;
	}

	/** @return \Dev4Press\Core\Options\Render */
	public static function instance( $base = 'd4pvalue', $prefix = 'd4p' ) {
		static $render = array();

		if ( ! isset( $render[ $base ] ) ) {
			$render[ $base ] = new Render( $base, $prefix );
		}

		return $render[ $base ];
	}

	public function prepare( $panel, $groups ) {
		$this->panel  = $panel;
		$this->groups = $groups;

		return $this;
	}

	public function call( $call_function, $setting, $name_base, $id_base ) {
		call_user_func( $call_function, $setting, $setting->value, $name_base, $id_base );
	}

	public function render() {
		foreach ( $this->groups as $group => $obj ) {
			if ( isset( $obj['type'] ) && $obj['type'] == 'separator' ) {
				echo '<div class="d4p-group-separator">';
				echo '<h3><span>' . $obj['label'] . '</span></h3>';
				echo '</div>';
			} else {
				$args = isset( $obj['args'] ) ? $obj['args'] : array();

				$classes = array( 'd4p-group', 'd4p-group-' . $group );

				if ( isset( $args['hidden'] ) && $args['hidden'] ) {
					$classes[] = 'd4p-hidden-group';
				}

				if ( isset( $args['class'] ) && $args['class'] != '' ) {
					$classes[] = $args['class'];
				}

				echo '<div class="' . join( ' ', $classes ) . '" id="d4p-group-' . $group . '">';
				$kb = isset( $obj['kb'] ) ? str_replace( '%url%', $obj['kb']['url'], $this->kb ) : '';

				if ( $kb != '' ) {
					$type = isset( $obj['kb']['type'] ) ? $obj['kb']['type'] : 'article';
					$kb   = str_replace( '%type%', $type, $kb );

					$kb = '<a class="d4p-kb-group" href="' . $kb . '" target="_blank" rel="noopener">' . $obj['kb']['label'] . '</a>';
				}

				echo '<h3>' . $obj['name'] . $kb . '</h3>';
				echo '<div class="d4p-group-inner">';

				if ( isset( $obj['settings'] ) ) {
					$obj['sections'] = array(
						array(
							'label'    => '',
							'name'     => '',
							'class'    => '',
							'settings' => $obj['settings']
						)
					);
					unset( $obj['settings'] );
				}

				foreach ( $obj['sections'] as $section ) {
					$this->render_section( $section, $group );
				}

				echo '</div>';
				echo '</div>';
			}
		}
	}

	protected function get_id( $name, $id = '' ) {
		if ( ! empty( $id ) ) {
			return $id;
		}

		return str_replace( '[', '_', str_replace( ']', '', $name ) );
	}

	protected function render_section( $section, $group ) {
		$class = 'd4p-settings-section';

		if ( ! empty( $section['name'] ) ) {
			$class .= ' d4p-section-' . $section['name'];
		}

		if ( ! empty( $section['class'] ) ) {
			$class .= ' ' . $section['class'];
		}

		echo '<div class="' . $class . '">';

		if ( ! empty( $section['label'] ) ) {
			echo '<h4><span>' . $section['label'] . '</span></h4>';
		}

		echo '<table class="form-table d4p-settings-table">';
		echo '<tbody>';

		foreach ( $section['settings'] as $setting ) {
			$this->render_option( $setting, $group );
		}

		echo '</tbody>';
		echo '</table>';

		echo '</div>';
	}

	protected function render_option( $setting, $group ) {
		$name_base     = $this->base . '[' . $setting->type . '][' . $setting->name . ']';
		$id_base       = $this->get_id( $name_base );
		$call_function = apply_filters( $this->prefix . '_render_option_call_back_for_' . $setting->input, array(
			$this,
			'draw_' . $setting->input
		), $this );

		$name = ! empty( $setting->name ) ? $setting->name : 'element-' . $setting->input;

		$wrapper_class = 'd4p-settings-item-row-' . $name;

		if ( isset( $setting->args['wrapper_class'] ) && ! empty( $setting->args['wrapper_class'] ) ) {
			$wrapper_class .= ' ' . $setting->args['wrapper_class'];
		}

		$class = 'd4p-setting-wrapper d4p-setting-' . $setting->input;

		if ( isset( $setting->args['class'] ) && ! empty( $setting->args['class'] ) ) {
			$class .= ' ' . $setting->args['class'];
		}

		if ( $setting->input == 'hidden' ) {
			do_action( 'd4p_settings_group_hidden_top', $setting, $group );

			$this->call( $call_function, $setting, $name_base, $id_base );

			do_action( 'd4p_settings_group_hidden_bottom', $setting, $group );
		} else {
			$data = array();

			if ( isset( $setting->args['data'] ) && is_array( $setting->args['data'] ) && ! empty( $setting->args['data'] ) ) {
				foreach ( $setting->args['data'] as $key => $value ) {
					$data[] = 'data-' . $key . '="' . $value . '"';
				}
			}

			if ( ! empty( $data ) ) {
				$data = ' ' . join( ' ', $data );
			} else {
				$data = '';
			}

			if ( $setting->input == 'custom' ) {
				echo '<tr' . $data . ' valign="top" class="d4p-settings-option-custom ' . $wrapper_class . '">';
				echo '<td colspan="2">';

				echo '<div class="' . $class . '">';
				echo $setting->notice;
				echo '</div>';

				echo '</td>';
				echo '</tr>';
			} else {
				$wrapper_class = 'd4p-settings-option-' . ( $setting->input == 'info' ? 'info' : 'item' ) . ' ' . $wrapper_class;

				echo '<tr' . $data . ' class="' . $wrapper_class . '">';

				if ( isset( $setting->args['readonly'] ) && $setting->args['readonly'] ) {
					$class .= 'd4p-setting-disabled';
				}

				echo '<th scope="row">';
				if ( empty( $setting->name ) ) {
					echo '<span>' . $setting->title . '</span>';
				} else {
					echo '<span id="' . $id_base . '__label">' . $setting->title . '</span>';
				}
				echo '</th><td>';
				echo '<div class="' . $class . '">';

				do_action( 'd4p_settings_group_top', $setting, $group );

				$this->call( $call_function, $setting, $name_base, $id_base );

				if ( $setting->notice != '' && $setting->input != 'info' ) {
					echo '<div class="d4p-description">' . $setting->notice . '</div>';
				}

				do_action( 'd4p_settings_group_bottom', $setting, $group );

				echo '</div>';
				echo '</td>';
				echo '</tr>';
			}
		}
	}

	protected function _pair_element( $name, $id, $i, $value, $element, $hide = false ) {
		echo '<div class="pair-element-' . $i . '" style="display: ' . ( $hide ? 'none' : 'block' ) . '">';
		echo '<label for="' . esc_attr( $id ) . '_key">' . $element->args['label_key'] . ':</label>';
		echo '<input type="text" name="' . esc_attr( $name ) . '[key]" id="' . esc_attr( $id ) . '_key" value="' . esc_attr( $value['key'] ) . '" class="widefat" />';

		echo '<label for="' . esc_attr( $id ) . '_value">' . $element->args['label_value'] . ':</label>';
		echo '<input type="text" name="' . esc_attr( $name ) . '[value]" id="' . esc_attr( $id ) . '_value" value="' . esc_attr( $value['value'] ) . '" class="widefat" />';

		echo '<a role="button" class="button-secondary" href="#">' . $element->args['label_buttom_remove'] . '</a>';
		echo '</div>';
	}

	protected function _text_element( $name, $id, $i, $value, $element, $hide = false ) {
		echo '<li class="exp-text-element exp-text-element-' . $i . '" style="display: ' . ( $hide ? 'none' : 'list-item' ) . '">';

		$button       = isset( $element->args['label_buttom_remove'] ) && $element->args['label_buttom_remove'] != '';
		$button_width = isset( $element->args['width_button_remove'] ) ? intval( $element->args['width_button_remove'] ) : 100;
		$type         = isset( $element->args['type'] ) && ! empty( $element->args['type'] ) ? $element->args['type'] : 'text';

		$style_input  = '';
		$style_button = '';
		if ( $button ) {
			$style_input  = ' style="width: calc(100% - ' . ( $button_width + 10 ) . 'px);"';
			$style_button = ' style="width: ' . $button_width . 'px;"';
		}

		echo '<input aria-labelledby="' . $id . '__label" type="' . $type . '" name="' . esc_attr( $name ) . '[value]" id="' . esc_attr( $id ) . '_value" value="' . esc_attr( $value ) . '" class="widefat"' . $style_input . ' />';

		if ( $button ) {
			echo '<a role="button" class="button-secondary" href="#"' . $style_button . '>' . $element->args['label_buttom_remove'] . '</a>';
		}

		echo '</li>';
	}

	protected function _datetime_element( $element, $value, $name_base, $id_base, $type = 'text', $class = '' ) {
		$readonly  = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';
		$min       = isset( $element->args['min'] ) ? ' min="' . esc_attr( $element->args['min'] ) . '"' : '';
		$max       = isset( $element->args['max'] ) ? ' max="' . esc_attr( $element->args['max'] ) . '"' : '';
		$flatpickr = isset( $element->args['flatpickr'] ) && $element->args['flatpickr'];
		$type      = $flatpickr ? 'text' : $type;
		$class     = 'widefat' . ( $flatpickr ? ' ' . $class : '' );

		echo sprintf( '<input aria-labelledby="%s__label" type="%s" name="%s" id="%s" value="%s" class="%s"%s%s%s />',
			$id_base, $type, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $value ), $class, $readonly, $min, $max );
	}

	protected function draw_date( $element, $value, $name_base, $id_base ) {
		$this->_datetime_element( $element, $value, $name_base, $id_base, 'date', 'd4p-input-field-date' );
	}

	protected function draw_time( $element, $value, $name_base, $id_base ) {
		$this->_datetime_element( $element, $value, $name_base, $id_base, 'time', 'd4p-input-field-time' );
	}

	protected function draw_month( $element, $value, $name_base, $id_base ) {
		$this->_datetime_element( $element, $value, $name_base, $id_base, 'month', 'd4p-input-field-month' );
	}

	protected function draw_datetime( $element, $value, $name_base, $id_base ) {
		$flatpickr = isset( $element->args['flatpickr'] ) && $element->args['flatpickr'];
		$class     = $flatpickr ? 'widefat d4p-input-field-datetime' : 'widefat';

		$this->draw_text( $element, $value, $name_base, $id_base, 'text', $class );
	}

	protected function draw_text( $element, $value, $name_base, $id_base, $type = 'text', $class = 'widefat' ) {
		$readonly    = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';
		$placeholder = isset( $element->args['placeholder'] ) && ! empty( $element->args['placeholder'] ) ? ' placeholder="' . $element->args['placeholder'] . '"' : '';
		$type        = isset( $element->args['type'] ) && ! empty( $element->args['type'] ) ? $element->args['type'] : $type;

		echo sprintf( '<input aria-labelledby="%s__label"%s%s type="%s" name="%s" id="%s" value="%s" class="%s" />',
			$id_base, $readonly, $placeholder, $type, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $value ), esc_attr( $class ) );
	}

	protected function draw_html( $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		echo sprintf( '<textarea aria-labelledby="%s__label"%s name="%s" id="%s" class="widefat">%s</textarea>',
			$id_base, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_textarea( $value ) );
	}

	protected function draw_number( $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		$min  = isset( $element->args['min'] ) ? ' min="' . esc_attr( floatval( $element->args['min'] ) ) . '"' : '';
		$max  = isset( $element->args['max'] ) ? ' max="' . esc_attr( floatval( $element->args['max'] ) ) . '"' : '';
		$step = isset( $element->args['step'] ) ? ' step="' . esc_attr( floatval( $element->args['step'] ) ) . '"' : '';

		echo sprintf( '<input aria-labelledby="%s__label"%s type="number" name="%s" id="%s" value="%s" class="widefat"%s%s%s />',
			$id_base, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $value ), $min, $max, $step );

		if ( isset( $element->args['label_unit'] ) ) {
			echo '<span class="d4p-field-unit">' . $element->args['label_unit'] . '</span>';
		}
	}

	protected function draw_integer( $element, $value, $name_base, $id_base ) {
		if ( ! isset( $element->args['step'] ) ) {
			$element->args['step'] = 1;
		}

		$this->draw_number( $element, $value, $name_base, $id_base );
	}

	protected function draw_checkboxes_hierarchy( $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
			case '':
			case 'array':
				$data = $element->data;
				break;
		}

		$value = is_null( $value ) || $value === true ? array_keys( $data ) : (array) $value;

		if ( $multiple ) {
			$this->part_check_uncheck_all();
		}

		$walker = new CheckboxRadio();
		$input  = $multiple ? 'checkbox' : 'radio';

		echo '<div class="d4p-content-wrapper">';
		echo '<ul class="d4p-wrapper-hierarchy">';
		echo $walker->walk( $data, 0, array(
			'input'    => $input,
			'id'       => $id_base,
			'name'     => $name_base,
			'selected' => $value
		) );
		echo '</ul>';
		echo '</div>';
	}

	protected function draw_checkboxes( $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
			case '':
			case 'array':
				$data = $element->data;
				break;
		}

		$value       = is_null( $value ) || $value === true ? array_keys( $data ) : (array) $value;
		$associative = d4p_is_array_associative( $data );

		if ( $multiple ) {
			$this->part_check_uncheck_all();
		}

		foreach ( $data as $key => $title ) {
			$real_value = $associative ? $key : $title;
			$sel        = in_array( $real_value, $value ) ? ' checked="checked"' : '';

			echo sprintf( '<label><input type="%s" value="%s" name="%s%s"%s class="widefat" />%s</label>',
				$multiple ? 'checkbox' : 'radio', esc_attr( $real_value ), esc_attr( $name_base ), $multiple == 1 ? '[]' : '', $sel, $title );
		}
	}

	protected function draw_group_multi( $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
				$data = $element->data;
				break;
		}

		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		d4p_render_grouped_select( $data, array(
			'selected' => $value,
			'readonly' => $readonly,
			'name'     => $name_base,
			'id'       => $id_base,
			'class'    => 'widefat',
			'multi'    => $multiple
		) );
	}

	protected function draw_select_multi( $element, $value, $name_base, $id_base, $multiple = true ) {
		switch ( $element->source ) {
			case 'function':
				$data = call_user_func( $element->data );
				break;
			default:
				$data = $element->data;
				break;
		}

		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		d4p_render_select( $data, array(
			'selected' => $value,
			'readonly' => $readonly,
			'name'     => $name_base,
			'id'       => $id_base,
			'class'    => 'widefat',
			'multi'    => $multiple
		), array( 'aria-labelledby' => $id_base . '__label' ) );
	}

	protected function draw_expandable_text( $element, $value, $name_base, $id_base = '' ) {
		echo '<ol>';

		$this->_text_element( $name_base . '[0]', $id_base . '_0', 0, '', $element, true );

		$i = 1;

		if ( array( $value ) && ! empty( $value ) ) {
			foreach ( $value as $val ) {
				$this->_text_element( $name_base . '[' . $i . ']', $id_base . '_' . $i, $i, $val, $element );
				$i ++;
			}
		}

		echo '</ol>';

		$label = isset( $element->args['label_button_add'] ) ? $element->args['label_button_add'] : __( "Add New Value", "d4plib" );

		echo '<a role="button" class="button-primary" href="#">' . $label . '</a>';
		echo '<input type="hidden" value="' . esc_attr( $i ) . '" class="d4p-next-id" />';
	}

	protected function draw_dropdown_categories( $element, $value, $name_base, $id_base = '' ) {
		$label_none   = isset( $element->args['label_none'] ) ? $element->args['label_none'] : ' ';
		$taxonomy     = isset( $element->args['taxonomy'] ) ? $element->args['taxonomy'] : 'category';
		$hierarchical = isset( $element->args['hierarchical'] ) ? $element->args['hierarchical'] : true;
		$child        = isset( $element->args['child_of'] ) ? $element->args['child_of'] : 0;
		$depth        = isset( $element->args['depth'] ) ? $element->args['depth'] : 0;
		$hide_empty   = isset( $element->args['hide_empty'] ) ? $element->args['hide_empty'] : false;

		$list = wp_dropdown_categories( array(
			'echo'              => false,
			'show_option_none'  => $label_none,
			'option_none_value' => 0,
			'hide_empty'        => $hide_empty,
			'hierarchical'      => $hierarchical,
			'child_of'          => $child,
			'depth'             => $depth,
			'name'              => $name_base,
			'class'             => 'widefat',
			'id'                => $id_base,
			'taxonomy'          => $taxonomy
		) );

		if ( empty( $list ) ) {
			d4p_render_select( array( '0' => __( "No items to show" ) ), array(
				'selected' => 0,
				'name'     => $name_base,
				'id'       => $id_base,
				'class'    => 'widefat'
			), array( 'aria-labelledby' => $id_base . '__label' ) );
		} else {
			echo $list;
		}
	}

	protected function draw_dropdown_pages( $element, $value, $name_base, $id_base = '' ) {
		$label_none = isset( $element->args['label_none'] ) ? $element->args['label_none'] : ' ';
		$post_type  = isset( $element->args['post_type'] ) ? $element->args['post_type'] : 'page';
		$child      = isset( $element->args['child_of'] ) ? $element->args['child_of'] : 0;
		$depth      = isset( $element->args['depth'] ) ? $element->args['depth'] : 0;

		$list = wp_dropdown_pages( array(
			'echo'              => false,
			'child_of'          => $child,
			'depth'             => $depth,
			'show_option_none'  => $label_none,
			'option_none_value' => 0,
			'selected'          => $value,
			'name'              => $name_base,
			'class'             => 'widefat',
			'id'                => $id_base,
			'post_type'         => $post_type
		) );

		if ( empty( $list ) ) {
			d4p_render_select( array( '0' => __( "No items to show" ) ), array(
				'selected' => 0,
				'name'     => $name_base,
				'id'       => $id_base,
				'class'    => 'widefat'
			), array( 'aria-labelledby' => $id_base . '__label' ) );
		} else {
			echo $list;
		}
	}

	protected function draw_info( $element, $value, $name_base, $id_base = '' ) {
		echo $element->notice;
	}

	protected function draw_images( $element, $value, $name_base, $id_base = '' ) {
		$value = (array) $value;

		echo '<a role="button" href="#" class="button d4plib-button-inner d4plib-images-add"><i aria-hidden="true" class="d4p-icon d4p-ui-photo"></i> ' . __( "Add Image", "d4plib" ) . '</a>';

		echo '<div class="d4plib-selected-image" data-name="' . $name_base . '">';

		echo '<div style="display: ' . ( empty( $value ) ? "block" : "none" ) . '" class="d4plib-images-none"><span class="d4plib-image-name">' . __( "No images selected.", "d4plib" ) . '</span></div>';

		foreach ( $value as $id ) {
			$image = get_post( $id );
			$title = '(' . $image->ID . ') ' . $image->post_title;
			$media = wp_get_attachment_image_src( $id, 'full' );
			$url   = $media[0];

			echo "<div class='d4plib-images-image'>";
			echo "<input type='hidden' value='" . esc_attr( $id ) . "' name='" . esc_attr( $name_base ) . "[]' />";
			echo "<a class='button d4plib-button-action d4plib-images-remove' aria-label='" . __( "Remove", "d4plib" ) . "'><i aria-hidden='true' class='d4p-icon d4p-ui-cancel'></i></a>";
			echo "<a class='button d4plib-button-action d4plib-images-preview' aria-label='" . __( "Preview", "d4plib" ) . "'><i aria-hidden='true' class='d4p-icon d4p-ui-search'></i></a>";
			echo "<span class='d4plib-image-name'>" . $title . "</span>";
			echo "<img src='" . $url . "' />";
			echo "</div>";
		}

		echo '</div>';
	}

	protected function draw_image( $element, $value, $name_base, $id_base = '' ) {
		echo sprintf( '<input class="d4plib-image" type="hidden" name="%s" id="%s" value="%s" />',
			$name_base, $id_base, esc_attr( $value ) );

		echo '<a role="button" href="#" class="button d4plib-button-inner d4plib-image-add"><i aria-hidden="trye" class="d4p-icon d4p-ui-photo"></i> ' . __( "Select Image", "d4plib" ) . '</a>';
		echo '<a role="button" style="display: ' . ( $value > 0 ? "inline-block" : "none" ) . '" href="#" class="button d4plib-button-inner d4plib-image-preview"><i aria-hidden="true" class="d4p-icon d4p-ui-search"></i> ' . __( "Show Image", "d4plib" ) . '</a>';
		echo '<a role="button" style="display: ' . ( $value > 0 ? "inline-block" : "none" ) . '" href="#" class="button d4plib-button-inner d4plib-image-remove"><i aria-hidden="true" class="d4p-icon d4p-ui-cancel"></i> ' . __( "Clear Image", "d4plib" ) . '</a>';

		echo '<div class="d4plib-selected-image">';
		$title = __( "Image not selected.", "d4plib" );
		$url   = '';

		if ( $value > 0 ) {
			$image = get_post( $value );
			$title = '(' . $image->ID . ') ' . $image->post_title;
			$media = wp_get_attachment_image_src( $value, 'full' );
			$url   = $media[0];
		}

		echo '<span class="d4plib-image-name">' . $title . '</span>';
		echo '<img src="' . $url . '" />';
		echo '</div>';
	}

	protected function draw_hidden( $element, $value, $name_base, $id_base = '' ) {
		echo sprintf( '<input type="hidden" name="%s" id="%s" value="%s" />',
			esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $value ) );
	}

	protected function draw_bool( $element, $value, $name_base, $id_base = '' ) {
		$selected = $value == 1 || $value === true ? ' checked="checked"' : '';
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly="readonly" disabled="disabled"' : '';
		$label    = isset( $element->args['label'] ) && $element->args['label'] != '' ? $element->args['label'] : __( "Enabled", "d4plib" );
		$value    = isset( $element->args['value'] ) && $element->args['value'] != '' ? $element->args['value'] : 'on';

		echo sprintf( '<label for="%s"><input%s type="checkbox" name="%s" id="%s"%s class="widefat" value="%s" /><span class="d4p-accessibility-show-for-sr">%s: </span>%s</label>',
			esc_attr( $id_base ), $readonly, esc_attr( $name_base ), esc_attr( $id_base ), $selected, $value, $element->title, $label );
	}

	protected function draw_range_absint( $element, $value, $name_base, $id_base ) {
		$this->draw_range_integer( $element, $value, $name_base, $id_base );
	}

	protected function draw_range_integer( $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		$pairs = explode( '=>', $value );

		echo sprintf( '<label for="%s_a"><span class="d4p-accessibility-show-for-sr">%s - A: </span></label><input%s type="number" name="%s[a]" id="%s_a" value="%s" class="widefat" />',
			$id_base, $element->title, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $pairs[0] ) );
		echo ' => ';
		echo sprintf( '<label for="%s_b"><span class="d4p-accessibility-show-for-sr">%s - B: </span></label><input%s type="number" name="%s[b]" id="%s_b" value="%s" class="widefat" />',
			$id_base, $element->title, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $pairs[1] ) );
	}

	protected function draw_x_by_y( $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		$pairs = explode( 'x', $value );

		echo sprintf( '<label for="%s_x"><span class="d4p-accessibility-show-for-sr">%s - X: </span></label><input%s type="number" name="%s[x]" id="%s_x" value="%s" class="widefat" />',
			$id_base, $element->title, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $pairs[0] ) );
		echo ' x ';
		echo sprintf( '<label for="%s_y"><span class="d4p-accessibility-show-for-sr">%s - Y: </span></label><input%s type="number" name="%s[y]" id="%s_y" value="%s" class="widefat" />',
			$id_base, $element->title, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $pairs[1] ) );
	}

	protected function draw_listing( $element, $value, $name_base, $id_base ) {
		$this->draw_html( $element, join( D4P_EOL, $value ), $name_base, $id_base );
	}

	protected function draw_code( $element, $value, $name_base, $id_base ) {
		$mode = isset( $element->args['mode'] ) && $element->args['mode'] ? $element->args['mode'] : 'htmlmixed';

		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		$this->draw_html( $element, $value, $name_base, $id_base );

		echo '<script type="text/javascript">
(function($){
    $(function(){
        var editorSettings = wp.codeEditor.defaultSettings ? _.clone(wp.codeEditor.defaultSettings) : {};

        editorSettings.codemirror = _.extend({}, editorSettings.codemirror, 
            { indentWithTabs: false, indentUnit: 2, tabSize: 2, mode: \'' . $mode . '\' }
        );

        var editor = wp.codeEditor.initialize($(\'#' . $id_base . '\'), editorSettings);                        
    });
})(jQuery);
</script>';
	}

	protected function draw_textarea( $element, $value, $name_base, $id_base ) {
		$this->draw_html( $element, $value, $name_base, $id_base );
	}

	protected function draw_slug( $element, $value, $name_base, $id_base ) {
		$this->draw_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_slug_ext( $element, $value, $name_base, $id_base ) {
		$this->draw_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_slug_slash( $element, $value, $name_base, $id_base ) {
		$this->draw_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_link( $element, $value, $name_base, $id_base ) {
		if ( ! isset( $element->args['placeholder'] ) ) {
			$element->args['placeholder'] = 'http://';
		}

		$this->draw_text( $element, $value, $name_base, $id_base, 'url' );
	}

	protected function draw_email( $element, $value, $name_base, $id_base ) {
		$this->draw_text( $element, $value, $name_base, $id_base, 'email' );
	}

	protected function draw_text_html( $element, $value, $name_base, $id_base ) {
		$this->draw_text( $element, $value, $name_base, $id_base );
	}

	protected function draw_password( $element, $value, $name_base, $id_base ) {
		$readonly     = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';
		$autocomplete = isset( $element->args['autocomplete'] ) ? d4p_sanitize_slug( $element->args['autocomplete'] ) : 'off';

		echo sprintf( '<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="password" name="%s" id="%s" value="%s" class="widefat" autocomplete="' . $autocomplete . '" />',
			$id_base, $element->title, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $value ) );
	}

	protected function draw_file( $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		echo sprintf( '<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="file" name="%s" id="%s" value="%s" class="widefat" />',
			$id_base, $element->title, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $value ) );
	}

	protected function draw_color( $element, $value, $name_base, $id_base ) {
		$readonly = isset( $element->args['readonly'] ) && $element->args['readonly'] ? ' readonly' : '';

		echo sprintf( '<label for="%s"><span class="d4p-accessibility-show-for-sr">%s: </span></label><input%s type="text" name="%s" id="%s" value="%s" class="widefat d4p-color-picker" />',
			$id_base, $element->title, $readonly, esc_attr( $name_base ), esc_attr( $id_base ), esc_attr( $value ) );
	}

	protected function draw_absint( $element, $value, $name_base, $id_base ) {
		$this->draw_integer( $element, $value, $name_base, $id_base );
	}

	protected function draw_select( $element, $value, $name_base, $id_base ) {
		$this->draw_select_multi( $element, $value, $name_base, $id_base, false );
	}

	protected function draw_group( $element, $value, $name_base, $id_base ) {
		$this->draw_group_multi( $element, $value, $name_base, $id_base, false );
	}

	protected function draw_radios_hierarchy( $element, $value, $name_base, $id_base ) {
		$this->draw_checkboxes_hierarchy( $element, $value, $name_base, $id_base, false );
	}

	protected function draw_radios( $element, $value, $name_base, $id_base ) {
		$this->draw_checkboxes( $element, $value, $name_base, $id_base, false );
	}

	protected function draw_expandable_pairs( $element, $value, $name_base, $id_base = '' ) {
		$this->_pair_element( $name_base . '[0]', $id_base . '_0', 0, array(
			'key'   => '',
			'value' => ''
		), $element, true );

		$i = 1;
		foreach ( $value as $key => $val ) {
			$this->_pair_element( $name_base . '[' . $i . ']', $id_base . '_' . $i, $i, array(
				'key'   => $key,
				'value' => $val
			), $element );
			$i ++;
		}

		echo '<a role="button" class="button-primary" href="#">' . $element->args['label_button_add'] . '</a>';
		echo '<input type="hidden" value="' . esc_attr( $i ) . '" class="d4p-next-id" />';
	}

	protected function draw_expandable_raw( $element, $value, $name_base, $id_base = '' ) {
		$this->draw_expandable_text( $element, $value, $name_base, $id_base );
	}

	protected function part_check_uncheck_all() {
		echo '<div class="d4p-check-uncheck">';

		echo '<a href="#checkall" class="d4p-check-all"><i class="d4p-icon d4p-ui-check-box"></i> ' . __( "Check All", "d4plib" ) . '</a>';
		echo '<a href="#uncheckall" class="d4p-uncheck-all"><i class="d4p-icon d4p-ui-box"></i> ' . __( "Uncheck All", "d4plib" ) . '</a>';

		echo '</div>';
	}
}
