<?php

namespace Dev4Press\Core\UI\Admin;

class PanelAbout extends Panel {
    protected $sidebar = false;

    public function __construct($admin) {
        parent::__construct($admin);

        $this->subpanels = array(
            'whatsnew' => array(
                'title' => __("What&#8217;s New", "d4plib"), 'icon' => ''),
            'info' => array(
                'title' => __("Info", "d4plib"), 'icon' => ''),
            'changelog' => array(
                'title' => __("Changelog", "d4plib"), 'icon' => ''),
            'translations' => array(
                'title' => __("Translations", "d4plib"), 'icon' => ''),
            'dev4press' => array(
                'title' => __("Dev4Press", "d4plib"), 'icon' => 'logo-dev4press')
        );
    }

    public function enqueue_scripts() {
        $this->a()->enqueue->css('about');
    }
}
