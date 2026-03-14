<?php

namespace Dev4Press\v56\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PanelAbout extends Panel {
	protected bool $sidebar = false;
	protected bool $history = false;
	protected string $default_subpanel = 'whatsnew';
	protected string $wrapper_class = 'd4p-page-about';

	protected function init_default_subpanels() : void {
		$this->subpanels = array(
			'whatsnew'  => array(
				'title' => __( 'What&#8217;s New', 'd4plib' ),
				'icon'  => '',
			),
			'donate'    => array(
				'title' => __( 'Donations', 'd4plib' ),
				'icon'  => '',
			),
			'info'      => array(
				'title' => __( 'Info', 'd4plib' ),
				'icon'  => '',
			),
			'changelog' => array(
				'title' => __( 'Changelog', 'd4plib' ),
				'icon'  => '',
			),
			'history'   => array(
				'title' => __( 'History', 'd4plib' ),
				'icon'  => '',
			),
			'system'    => array(
				'title' => __( 'System', 'd4plib' ),
				'icon'  => '',
			),
			'dev4press' => array(
				'title' => __( 'Dev4Press', 'd4plib' ),
				'icon'  => '',
			),
		);

		if ( ! ( $this->a()->settings()->i()->edition === 'free' && ! empty( $this->a()->settings()->i()->github_url ) ) ) {
			unset( $this->subpanels['donate'] );
		}

		if ( ! $this->history ) {
			unset( $this->subpanels['history'] );
		}
	}

	public function enqueue_scripts() : void {
		$this->a()->enqueue->css( 'about' );
	}
}
