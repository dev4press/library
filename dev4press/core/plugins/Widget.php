<?php

namespace Dev4Press\Core\Plugins;

class Widget extends \WP_Widget {
    public $selective_refresh = true;

    public $defaults = array(
        'title' => 'Base Widget Class',
        '_display' => 'all',
        '_cached' => 0,
        '_hook' => '',
        '_class' => ''
    );
}
