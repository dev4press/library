<?php

namespace Dev4Press\v55\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PanelSettings extends Panel {
	protected bool $form = true;
	protected bool $form_multiform = true;
	protected bool $all_settings = true;

	public string $settings_class = '';

	protected function init_default_subpanels() : void {
		$this->subpanels = array(
			'index' => array(
				'title' => __( 'Settings Index', 'd4plib' ),
				'icon'  => 'ui-cog',
				'info'  => __( 'All plugin settings are split into several panels, and you access each starting from the right.', 'd4plib' ),
			),
		);

		if ( $this->all_settings ) {
			$this->subpanels['full'] = array(
				'title' => __( 'All Settings', 'd4plib' ),
				'icon'  => 'ui-cogs',
				'info'  => __( 'All plugin settings are displayed on this page, and you can use live search to find the settings you need.', 'd4plib' ),
			);
		}
	}

	public function enqueue_scripts_early() : void {
		$this->a()->enqueue->js( 'mark' )->js( 'confirmsubmit' );
	}
}
