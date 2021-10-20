<?php

namespace Dev4Press\v37\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PanelFeatures extends Panel {
	protected $form = true;

	public $settings_class = '';

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->init_default_subpanels();
	}

	protected function init_default_subpanels() {
		$this->subpanels = array(
			'index' => array(
				'title' => __( "Features Index", "d4plib" ),
				'icon'  => 'ui-cabinet',
				'info'  => __( "Main control panel for all the plugin individual features.", "d4plib" )
			)
		);
	}

	public function settings_fields() {
		$group  = $this->a()->plugin . '-features';
		$action = $this->a()->v();

		echo "<input type='hidden' name='option_page' value='" . esc_attr( $group ) . "' />";
		echo "<input type='hidden' name='action' value='update' />";
		echo "<input type='hidden' name='" . $action . "' value='postback' />";

		wp_nonce_field( $group . '-options' );
	}

	public function enqueue_scripts_early() {
		$this->a()->enqueue->js( 'mark' )->js( 'confirmsubmit' );
	}
}
