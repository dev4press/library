<?php
/**
 * Name:    Dev4Press\v55\Core\Quick\Display
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

namespace Dev4Press\v55\Core\Quick;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class KSES {
	public static function post( string $render ) : string {
		return wp_kses_post( $render );
	}

	public static function standard( string $render ) : string {
		return wp_kses( $render, self::allowed_html_standard() );
	}

	public static function strong( string $render ) : string {
		return wp_kses( $render, self::allowed_html_strong() );
	}

	public static function buttons( string $render ) : string {
		return wp_kses( $render, self::allowed_html_button() );
	}

	public static function select( string $render ) : string {
		return wp_kses( $render, self::allowed_html_select() );
	}

	public static function input( string $render ) : string {
		return wp_kses( $render, self::allowed_html_input() );
	}

	public static function checkboxes( string $render ) : string {
		return wp_kses( $render, self::allowed_html_checkboxes() );
	}

	public static function allowed_html_strong() : array {
		return array(
			'strong' => array(
				'class' => true,
				'style' => true,
			),
			'span'   => array(
				'class' => true,
				'style' => true,
			),
			'i'      => array(
				'class'  => true,
				'aria-*' => true,
			),
		);
	}

	public static function allowed_html_button() : array {
		return array(
			'div'    => array(
				'id'    => true,
				'class' => true,
			),
			'a'      => array(
				'class'  => true,
				'href'   => true,
				'rel'    => true,
				'id'     => true,
				'name'   => true,
				'target' => true,
			),
			'button' => array(
				'name'  => true,
				'id'    => true,
				'type'  => true,
				'class' => true,
			),
			'span'   => array(
				'class' => true,
			),
			'i'      => array(
				'class'  => true,
				'aria-*' => true,
			),
		);
	}

	public static function allowed_html_select() : array {
		return array(
			'select'   => array(
				'class'    => true,
				'style'    => true,
				'title'    => true,
				'multiple' => true,
				'readonly' => true,
				'id'       => true,
				'name'     => true,
				'data-*'   => true,
			),
			'optgroup' => array(
				'label' => true,
			),
			'option'   => array(
				'value'    => true,
				'selected' => true,
			),
		);
	}

	public static function allowed_html_input() : array {
		return array(
			'input' => array(
				'class'        => true,
				'style'        => true,
				'title'        => true,
				'alt'          => true,
				'type'         => true,
				'checked'      => true,
				'readonly'     => true,
				'id'           => true,
				'name'         => true,
				'placeholder'  => true,
				'value'        => true,
				'min'          => true,
				'max'          => true,
				'step'         => true,
				'size'         => true,
				'minlength'    => true,
				'maxlength'    => true,
				'multiple'     => true,
				'pattern'      => true,
				'required'     => true,
				'autocomplete' => true,
				'autofocus'    => true,
				'data-*'       => true,
				'aria-*'       => true,
			),
		);
	}

	public static function allowed_html_checkboxes() : array {
		return array(
			'a'     => array(
				'class' => true,
				'href'  => true,
			),
			'i'     => array(
				'class'  => true,
				'aria-*' => true,
			),
			'ul'    => array(
				'class' => true,
			),
			'li'    => array(
				'class' => true,
			),
			'div'   => array(
				'class' => true,
			),
			'label' => array(
				'class' => true,
			),
			'input' => array(
				'class'    => true,
				'style'    => true,
				'checked'  => true,
				'readonly' => true,
				'id'       => true,
				'name'     => true,
				'data-*'   => true,
				'type'     => true,
				'value'    => true,
			),
		);
	}

	public static function allowed_html_standard() : array {
		$list = array(
			'code'   => array(),
			'br'     => array(),
			'hr'     => array(),
			'h1'     => array(),
			'h2'     => array(),
			'h3'     => array(),
			'h4'     => array(),
			'h5'     => array(),
			'h6'     => array(),
			'p'      => array(),
			'em'     => array(),
			'strong' => array(),
			'div'    => array(),
			'span'   => array(),
			'i'      => array(),
			'li'     => array(),
			'ul'     => array(),
			'ol'     => array(
				'start' => true,
			),
			'a'      => array(
				'href'     => true,
				'rel'      => true,
				'download' => true,
				'target'   => true,
			),
			'img'    => array(
				'src'    => true,
				'alt'    => true,
				'height' => true,
				'width'  => true,
			),
		);

		return self::expand_tags_with_attributes( $list );
	}

	public static function allowed_html_expanded() : array {
		$list = array(
			'abbr'       => array(),
			'br'         => array(),
			'hr'         => array(),
			'div'        => array(),
			'span'       => array(),
			'code'       => array(),
			'p'          => array(),
			'pre'        => array(),
			'em'         => array(),
			'i'          => array(),
			'b'          => array(),
			'strong'     => array(),
			'del'        => array(),
			'h1'         => array(),
			'h2'         => array(),
			'h3'         => array(),
			'h4'         => array(),
			'h5'         => array(),
			'h6'         => array(),
			'li'         => array(),
			'ul'         => array(),
			'ol'         => array(
				'start' => true,
			),
			'blockquote' => array(
				'cite' => true,
			),
			'a'          => array(
				'href'     => true,
				'rel'      => true,
				'download' => true,
				'target'   => true,
			),
			'img'        => array(
				'src'    => true,
				'alt'    => true,
				'height' => true,
				'width'  => true,
			),
			'table'      => array(
				'cellpadding' => true,
				'cellspacing' => true,
				'align'       => true,
				'border'      => true,
				'width'       => true,
			),
			'tbody'      => array(
				'align'  => true,
				'valign' => true,
			),
			'td'         => array(
				'align'   => true,
				'valign'  => true,
				'colspan' => true,
				'rowspan' => true,
				'width'   => true,
			),
			'tfoot'      => array(
				'align'  => true,
				'valign' => true,
			),
			'th'         => array(
				'align'   => true,
				'valign'  => true,
				'colspan' => true,
				'rowspan' => true,
				'width'   => true,
			),
			'thead'      => array(
				'align'  => true,
				'valign' => true,
			),
			'tr'         => array(
				'align'  => true,
				'valign' => true,
			),
		);

		return self::expand_tags_with_attributes( $list );
	}

	public static function expand_tags_with_attributes( $tags ) {
		foreach ( $tags as &$attrs ) {
			$attrs['id']     = true;
			$attrs['class']  = true;
			$attrs['style']  = true;
			$attrs['title']  = true;
			$attrs['aria-*'] = true;
			$attrs['data-*'] = true;
		}

		return $tags;
	}
}
