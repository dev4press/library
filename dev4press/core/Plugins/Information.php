<?php

namespace Dev4Press\Core\Plugins;

class Information {
    public $code = '';

    public $version = '';
    public $build = 0;
    public $updated = '';
    public $status = '';
    public $edition = '';
    public $url = '';
    public $released = '';

    public $author_name = 'Milan Petrovic';
    public $author_url = 'https://www.dev4press.com/';

    public $php = '5.6';
    public $mysql = '5.1';
    public $wordpress = '4.7';

    public $install = false;
    public $update = false;
    public $previous = 0;

    function __construct() {
    }

    public function to_array() {
        return (array)$this;
    }
}