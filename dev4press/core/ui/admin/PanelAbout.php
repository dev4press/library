<?php

namespace Dev4Press\v39\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PanelAbout extends Panel {
	protected $sidebar = false;
	protected $default_subpanel = 'whatsnew';
	protected $wrapper_class = 'd4p-page-about';

	public function __construct( $admin ) {
		parent::__construct( $admin );

		$this->init_default_subpanels();
	}

	protected function init_default_subpanels() {
		$this->subpanels = array(
			'whatsnew'  => array(
				'title' => __( "What&#8217;s New", "d4plib" ),
				'icon'  => ''
			),
			'info'      => array(
				'title' => __( "Info", "d4plib" ),
				'icon'  => ''
			),
			'changelog' => array(
				'title' => __( "Changelog", "d4plib" ),
				'icon'  => ''
			),
			'system'    => array(
				'title' => __( "System", "d4plib" ),
				'icon'  => ''
			),
			'dev4press' => array(
				'title' => __( "Dev4Press", "d4plib" ),
				'icon'  => ''
			)
		);

		$translations = $this->a()->settings()->i()->translations;

		if ( empty( $translations ) ) {
			unset( $this->subpanels['translations'] );
		}
	}

	public function enqueue_scripts() {
		$this->a()->enqueue->css( 'about' );
	}
}
