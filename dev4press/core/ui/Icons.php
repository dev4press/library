<?php

namespace Dev4Press\Core\UI;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Icons {
	protected $base = 'd4p-icon';
	protected $prefix = 'd4p-';
	protected $prefix_control = 'd4p-icon-';

	protected $icons = array(
		'addon-icon-amazon-s3-storage-addon',
		'addon-icon-analytics-addon',
		'addon-icon-aws-ses-addon',
		'addon-icon-book-rich-snippet-addon',
		'addon-icon-christmas-pack-addon',
		'addon-icon-code-builder-addon',
		'addon-icon-comment-form-addon',
		'addon-icon-dropbox-storage-addon',
		'addon-icon-emojione-pack-addon',
		'addon-icon-gmail-addon',
		'addon-icon-halloween-pack-addon',
		'addon-icon-integration-addon-edd',
		'addon-icon-integration-addon-wc',
		'addon-icon-mailgun-addon',
		'addon-icon-mailjet-addon',
		'addon-icon-multi-ratings-addon',
		'addon-icon-mycred-integration-addon',
		'addon-icon-mycred-simple-integration-addon',
		'addon-icon-recipe-rich-snippet-addon',
		'addon-icon-sendinblue-addon',
		'addon-icon-user-reviews-addon',
		'brand-facebook-square',
		'brand-instagram',
		'brand-twitter-square',
		'brand-wordpress',
		'brand-youtube-square',
		'club-icon-gd-bbpress-club',
		'club-icon-bbpress',
		'club-icon-gd-dev4press-plugins',
		'club-icon-dev4press',
		'club-icon-gd-rating-club',
		'club-icon-rating',
		'file-archive',
		'file-certificate',
		'file-import',
		'file-text',
		'icon-bbpress-forum',
		'icon-bbpress-reply',
		'icon-bbpress-topic',
		'icon-responsive',
		'icon-snowflake',
		'logo-bbpress',
		'logo-buddypress',
		'logo-dev4press-light',
		'logo-dev4press',
		'logo-jquery',
		'logo-php-light',
		'logo-php',
		'logo-woo',
		'logo-xscape2',
		'plugin-icon-dev4press-updater',
		'plugin-icon-gd-bbpress-attachments',
		'plugin-icon-gd-bbpress-toolbox',
		'plugin-icon-gd-bbpress-tools',
		'plugin-icon-gd-clever-widgets',
		'plugin-icon-gd-content-tools',
		'plugin-icon-gd-crumbs-navigator',
		'plugin-icon-gd-forum-manager',
		'plugin-icon-gd-forum-manager-for-bbpress',
		'plugin-icon-gd-forum-notices',
		'plugin-icon-gd-forum-notices-for-bbpress',
		'plugin-icon-gd-knowledge-base',
		'plugin-icon-gd-mail-queue',
		'plugin-icon-gd-members-directory',
		'plugin-icon-gd-members-directory-for-bbpress',
		'plugin-icon-gd-pages-navigator',
		'plugin-icon-gd-power-search',
		'plugin-icon-gd-power-search-for-bbpress-lite',
		'plugin-icon-gd-power-search-for-bbpress',
		'plugin-icon-gd-press-tools',
		'plugin-icon-gd-quantum-theme',
		'plugin-icon-gd-quantum-theme-for-bbpress',
		'plugin-icon-gd-rating-system',
		'plugin-icon-gd-rating-system-lite',
		'plugin-icon-gd-security-headers',
		'plugin-icon-gd-security-toolbox',
		'plugin-icon-gd-seo-toolbox',
		'plugin-icon-gd-swift-navigator',
		'plugin-icon-gd-topic-polls',
		'plugin-icon-gd-topic-polls-lite',
		'plugin-icon-gd-topic-polls-for-bbpress',
		'plugin-icon-gd-topic-prefix',
		'plugin-icon-gd-topic-prefix-for-bbpress',
		'plugin-icon-gd-webfonts-toolbox',
		'plugin-icon-gd-webfonts-toolbox-lite',
		'ui-alarm',
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
		'ui-calendar-day',
		'ui-calendar',
		'ui-cancel',
		'ui-caret-down',
		'ui-caret-up',
		'ui-certificate',
		'ui-chart-bar',
		'ui-check-box',
		'ui-check-square',
		'ui-check',
		'ui-chevron-left',
		'ui-chevron-right',
		'ui-clear',
		'ui-clipboard-list',
		'ui-clock',
		'ui-cloud-download',
		'ui-cloud',
		'ui-code',
		'ui-cog',
		'ui-cogs',
		'ui-comment-dots',
		'ui-database',
		'ui-desktop',
		'ui-download',
		'ui-envelope-open',
		'ui-envelope',
		'ui-eraser',
		'ui-exclamation-square',
		'ui-external-link',
		'ui-friends',
		'ui-hammer',
		'ui-home',
		'ui-info',
		'ui-key',
		'ui-language',
		'ui-life-ring',
		'ui-link',
		'ui-lock',
		'ui-magic',
		'ui-magnet',
		'ui-minus-square',
		'ui-minus',
		'ui-mobile-phone',
		'ui-network',
		'ui-newspaper',
		'ui-object-ungroup',
		'ui-paint-brush',
		'ui-palette',
		'ui-paper-plane',
		'ui-photo',
		'ui-plug-regular',
		'ui-plug',
		'ui-plus-square',
		'ui-plus',
		'ui-poll-horizontal',
		'ui-poll',
		'ui-puzzle',
		'ui-question',
		'ui-quote-left',
		'ui-quote-right',
		'ui-ribbon',
		'ui-rocket',
		'ui-search',
		'ui-server',
		'ui-shield-check',
		'ui-shopping-bag',
		'ui-shopping-cart',
		'ui-signal',
		'ui-sliders-base-hor',
		'ui-sliders-base',
		'ui-sliders-hor',
		'ui-sliders',
		'ui-spinner',
		'ui-square',
		'ui-star',
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

	public static function instance() {
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
