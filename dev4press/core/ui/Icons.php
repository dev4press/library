<?php

/*
Name:    Dev4Press\v38\Core\UI\Icons
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Icons {
	protected $base = 'd4p-icon';
	protected $prefix = 'd4p-';
	protected $prefix_control = 'd4p-icon-';

	protected $icons = array(
		'addon-gd-knowledge-base',
		'addon-gd-mail-queue',
		'addon-gd-press-tools',
		'addon-gd-rating-system',
		'brand-facebook',
		'brand-instagram',
		'brand-linkedin',
		'brand-stack-exchange',
		'brand-twitter',
		'brand-wordpress',
		'brand-youtube',
		'club-icon-bbpress',
		'club-icon-dev4press',
		'club-icon-gd-bbpress-club',
		'club-icon-gd-dev4press-plugins',
		'club-icon-gd-rating-club',
		'club-icon-rating',
		'file-archive',
		'file-certificate',
		'file-chart-line',
		'file-import',
		'file-text',
		'icon-bbpress-forum',
		'icon-bbpress-reply',
		'icon-bbpress-topic',
		'icon-responsive',
		'icon-snowflake',
		'logo-bbpress',
		'logo-buddypress',
		'logo-dev4press',
		'logo-dev4press-light',
		'logo-jquery',
		'logo-php',
		'logo-php-light',
		'logo-woo',
		'plugin-archivespress',
		'plugin-breadcrumbspress',
		'plugin-debugpress',
		'plugin-demopress',
		'plugin-dev4press-updater',
		'plugin-gd-bbpress-attachments',
		'plugin-gd-bbpress-toolbox',
		'plugin-gd-bbpress-tools',
		'plugin-gd-clever-widgets',
		'plugin-gd-content-tools',
		'plugin-gd-forum-manager',
		'plugin-gd-forum-manager-for-bbpress',
		'plugin-gd-forum-notices',
		'plugin-gd-forum-notices-for-bbpress',
		'plugin-gd-knowledge-base',
		'plugin-gd-mail-queue',
		'plugin-gd-members-directory',
		'plugin-gd-members-directory-for-bbpress',
		'plugin-gd-pages-navigator',
		'plugin-gd-power-search',
		'plugin-gd-power-search-for-bbpress',
		'plugin-gd-power-search-for-bbpress-lite',
		'plugin-gd-press-tools',
		'plugin-gd-quantum-theme',
		'plugin-gd-quantum-theme-for-bbpress',
		'plugin-gd-rating-system',
		'plugin-gd-rating-system-lite',
		'plugin-gd-security-headers',
		'plugin-gd-security-toolbox',
		'plugin-gd-seo-toolbox',
		'plugin-gd-swift-navigator',
		'plugin-gd-topic-polls',
		'plugin-gd-topic-polls-for-bbpress',
		'plugin-gd-topic-polls-lite',
		'plugin-gd-topic-prefix',
		'plugin-gd-topic-prefix-for-bbpress',
		'plugin-gd-webfonts-toolbox',
		'ui-alarm',
		'ui-analytics',
		'ui-archive',
		'ui-arrows-v',
		'ui-assistive-listening-systems',
		'ui-badge-check',
		'ui-badge-percent',
		'ui-bell',
		'ui-book-spells',
		'ui-bookmark',
		'ui-box',
		'ui-brackets',
		'ui-briefcase',
		'ui-browser',
		'ui-bug',
		'ui-cabinet',
		'ui-calendar',
		'ui-calendar-day',
		'ui-cancel',
		'ui-caret-down',
		'ui-caret-up',
		'ui-certificate',
		'ui-chart-area',
		'ui-chart-bar',
		'ui-chart-pie',
		'ui-check',
		'ui-check-box',
		'ui-check-square',
		'ui-chevron-left',
		'ui-chevron-right',
		'ui-clear',
		'ui-clipboard-list',
		'ui-clock',
		'ui-close-square',
		'ui-cloud',
		'ui-cloud-download',
		'ui-code',
		'ui-cog',
		'ui-cogs',
		'ui-comment',
		'ui-comment-dots',
		'ui-comments',
		'ui-database',
		'ui-desktop',
		'ui-download',
		'ui-edit',
		'ui-envelope',
		'ui-envelope-open',
		'ui-eraser',
		'ui-exclamation-square',
		'ui-external-link',
		'ui-eye',
		'ui-filter',
		'ui-flag',
		'ui-friends',
		'ui-globe',
		'ui-hammer',
		'ui-home',
		'ui-info',
		'ui-key',
		'ui-language',
		'ui-life-ring',
		'ui-link',
		'ui-list',
		'ui-lock',
		'ui-magic',
		'ui-magnet',
		'ui-minus',
		'ui-minus-square',
		'ui-mobile-phone',
		'ui-network',
		'ui-newspaper',
		'ui-object-ungroup',
		'ui-paint-brush',
		'ui-palette',
		'ui-paper-plane',
		'ui-paste',
		'ui-pause',
		'ui-pen-nib',
		'ui-pencil',
		'ui-photo',
		'ui-play',
		'ui-plug',
		'ui-plug-regular',
		'ui-plus',
		'ui-plus-square',
		'ui-poll',
		'ui-poll-horizontal',
		'ui-puzzle',
		'ui-qrcode',
		'ui-question',
		'ui-quote-left',
		'ui-quote-right',
		'ui-ribbon',
		'ui-rocket',
		'ui-search',
		'ui-server',
		'ui-share',
		'ui-shield-check',
		'ui-shopping-bag',
		'ui-shopping-cart',
		'ui-shortcode',
		'ui-signal',
		'ui-sliders',
		'ui-sliders-base',
		'ui-sliders-base-hor',
		'ui-sliders-hor',
		'ui-spinner',
		'ui-square',
		'ui-star',
		'ui-sun',
		'ui-sync',
		'ui-tag',
		'ui-tags',
		'ui-tasks',
		'ui-term',
		'ui-terms',
		'ui-times',
		'ui-toggle-off',
		'ui-toggle-on',
		'ui-toolbox',
		'ui-tools',
		'ui-traffic',
		'ui-trash',
		'ui-unlock',
		'ui-upload',
		'ui-user',
		'ui-users',
		'ui-video',
		'ui-vote-nay',
		'ui-vote-yea',
		'ui-warning',
		'ui-wrench'
	);

	protected $bool_args = array(
		'full' => 'fw',
		'spin' => 'spin'
	);
	protected $valid_args = array(
		'size'   => array( 'lg', '1x', '2x', '3x', '4x', '5x', '6x', '7x', '8x', '9x', '10x' ),
		'pull'   => array( 'left', 'right' ),
		'flip'   => array( 'vertical', 'horizontal', 'both' ),
		'rotate' => array( '45', '90', '270' )
	);

	public function __construct() {

	}

	public static function instance() : Icons {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new Icons();
		}

		return $instance;
	}

	public function icons_list() {
		return $this->icons;
	}

	public function icon_class( $name, $args = array() ) {
		$defaults = array(
			'size'   => false,
			'pull'   => false,
			'flip'   => false,
			'full'   => false,
			'spin'   => false,
			'rotate' => false
		);

		$args = wp_parse_args( $args, $defaults );

		$classes = array();

		if ( in_array( $name, $this->icons ) ) {
			$classes[] = $this->base;
			$classes[] = $this->prefix . $name;
		}

		foreach ( $args as $arg => $value ) {
			if ( $value !== false && is_string( $value ) ) {
				if ( isset( $this->bool_args[ $arg ] ) ) {
					$classes[] = $this->prefix_control . $this->bool_args[ $arg ];
				} else if ( isset( $this->valid_args[ $arg ] ) ) {
					if ( in_array( $value, $this->valid_args[ $arg ] ) ) {
						$classes[] = $this->prefix_control . $value;
					}
				}
			}
		}

		return $classes;
	}

	public function icon( $name, $tag = 'i', $args = array(), $attr = array() ) {
		$render     = '';
		$properties = array();
		$classes    = $this->icon_class( $name, $args );

		if ( ! empty( $classes ) ) {
			$defaults = array(
				'title'       => '',
				'style'       => '',
				'class'       => '',
				'aria-hidden' => 'true'
			);

			$attr = shortcode_atts( $defaults, $attr );

			if ( ! empty( $attr['class'] ) ) {
				$classes[] = $attr['class'];
			}

			$attr['class'] = join( ' ', $classes );

			foreach ( $attr as $key => $value ) {
				if ( ! empty( $value ) && is_string( $value ) ) {
					$properties[] = $key . '="' . esc_attr( $value ) . '"';
				}
			}

			$render = '<' . $tag . ' ' . join( ' ', $properties ) . '></' . $tag . '>';
		}

		return $render;
	}

	public function icons( $list = false, $tag = 'i', $args = array(), $attr = array() ) {
		$out  = array();
		$list = $list === false ? $this->icons : $list;

		foreach ( $list as $icon ) {
			$render = $this->icon( $icon, $tag, $args, $attr );

			if ( ! empty( $render ) ) {
				$out[ $icon ] = $render;
			}
		}

		return $out;
	}
}
