<?php

namespace Dev4Press\Core\Options;

class Element {
    public $type;
    public $name;
    public $title;
    public $notice;
    public $input;
    public $value;
    public $source;
    public $data;
    public $args;

    function __construct($type, $name, $title = '', $notice = '', $input = 'text', $value = '', $source = '', $data = '', $args = array()) {
        $this->type = $type;
        $this->name = $name;
        $this->title = $title;
        $this->notice = $notice;
        $this->input = $input;
        $this->value = $value;
        $this->source = $source;
        $this->data = $data;
        $this->args = $args;
    }
}
