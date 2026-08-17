<?php

namespace Dev4Press\v56\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class PanelWizard extends Panel {
	protected bool $sidebar = false;

	protected function init_default_subpanels() : void {
		$this->subpanels = array();

		foreach ( $this->a()->wizard()->panels as $panel => $obj ) {
			$this->subpanels[ $panel ] = array( 'title' => $obj['label'] );
		}
	}

	public function show() : void {
		$this->load( 'content-wizard.php' );
	}

	public function enqueue_scripts() : void {
		$this->a()->e()->css( 'wizard' )->js( 'wizard' );
	}
}
