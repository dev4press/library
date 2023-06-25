<?php

namespace Dev4Press\v43\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PanelDashboard extends Panel {
	protected $cards = true;
	public $sidebar_links = array(
		'plugin' => array(),
		'basic'  => array(),
		'about'  => array()
	);

	public function __construct( $admin ) {
		parent::__construct( $admin );

		if ( isset( $this->a()->menu_items[ 'features' ] ) ) {
			$this->sidebar_links[ 'basic' ][ 'features' ] = array(
				'icon'  => $this->a()->menu_items[ 'features' ][ 'icon' ],
				'class' => 'button-primary',
				'url'   => $this->a()->panel_url( 'features', '', '', $this->a()->is_menu_item_network_only( 'features' ) ),
				'label' => __( "Features", "d4plib" ),
				'scope' => $this->a()->menu_items[ 'features' ][ 'scope' ] ?? array()
			);
		}

		if ( isset( $this->a()->menu_items[ 'settings' ] ) ) {
			$this->sidebar_links[ 'basic' ][ 'settings' ] = array(
				'icon'  => $this->a()->menu_items[ 'settings' ][ 'icon' ],
				'class' => 'button-secondary',
				'url'   => $this->a()->panel_url( 'settings', '', '', $this->a()->is_menu_item_network_only( 'settings' ) ),
				'label' => __( "Settings", "d4plib" ),
				'scope' => $this->a()->menu_items[ 'settings' ][ 'scope' ] ?? array()
			);
		}

		if ( isset( $this->a()->menu_items[ 'tools' ] ) ) {
			$this->sidebar_links[ 'basic' ][ 'tools' ] = array(
				'icon'  => $this->a()->menu_items[ 'tools' ][ 'icon' ],
				'class' => 'button-secondary',
				'url'   => $this->a()->panel_url( 'tools', '', '', $this->a()->is_menu_item_network_only( 'tools' ) ),
				'label' => __( "Tools", "d4plib" ),
				'scope' => $this->a()->menu_items[ 'tools' ][ 'scope' ] ?? array()
			);
		}

		if ( isset( $this->a()->menu_items[ 'about' ] ) ) {
			$this->sidebar_links[ 'about' ] = array(
				'about' => array(
					'icon'  => $this->a()->menu_items[ 'about' ][ 'icon' ],
					'class' => 'button-secondary',
					'url'   => $this->a()->panel_url( 'about', '', '', $this->a()->is_menu_item_network_only( 'about' ) ),
					'label' => __( "About", "d4plib" ),
					'scope' => $this->a()->menu_items[ 'about' ][ 'scope' ] ?? array()
				)
			);
		}
	}
}
