<?php

namespace Dev4Press\Core\UI\Admin;

class PanelDashboard extends Panel {
    public $sidebar_links = array(
        'plugin' => array(),
        'basic' => array(),
        'about' => array()
    );

    public function __construct($admin) {
        parent::__construct($admin);

        $this->sidebar_links['basic'] = array(
            'settings' => array(
                'icon' => $this->a()->menu_items['settings']['icon'],
                'class' => 'button-secondary',
                'url' => $this->a()->panel_url('settings'),
                'label' => __("Settings")
            ),
            'tools' => array(
                'icon' => $this->a()->menu_items['tools']['icon'],
                'class' => 'button-secondary',
                'url' => $this->a()->panel_url('tools'),
                'label' => __("Tools")
            )
        );

        $this->sidebar_links['about'] = array(
            'about' => array(
                'icon' => $this->a()->menu_items['about']['icon'],
                'class' => 'button-secondary',
                'url' => $this->a()->panel_url('about'),
                'label' => __("About")
            )
        );
    }
}
