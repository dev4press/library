<?php

namespace Dev4Press\v35\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PanelDashboard extends Panel {
	public $sidebar_links = array(
		'plugin' => array(),
		'basic'  => array(),
		'about'  => array()
	);

	public function __construct( $admin ) {
		parent::__construct( $admin );

		if ( isset( $this->a()->menu_items['settings'] ) ) {
			$this->sidebar_links['basic']['settings'] = array(
				'icon'  => $this->a()->menu_items['settings']['icon'],
				'class' => 'button-secondary',
				'url'   => $this->a()->panel_url( 'settings' ),
				'label' => __( "Settings", "d4plib" )
			);
		}

		if ( isset( $this->a()->menu_items['tools'] ) ) {
			$this->sidebar_links['basic']['tools'] = array(
				'icon'  => $this->a()->menu_items['tools']['icon'],
				'class' => 'button-secondary',
				'url'   => $this->a()->panel_url( 'tools' ),
				'label' => __( "Tools", "d4plib" )
			);
		}

		if ( isset( $this->a()->menu_items['about'] ) ) {
			$this->sidebar_links['about'] = array(
				'about' => array(
					'icon'  => $this->a()->menu_items['about']['icon'],
					'class' => 'button-secondary',
					'url'   => $this->a()->panel_url( 'about' ),
					'label' => __( "About", "d4plib" )
				)
			);
		}
	}
}
