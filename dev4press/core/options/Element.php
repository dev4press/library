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

    function __construct($type, $name, $title = '', $notice = '', $input = 'text', $value = '') {
        $this->type = $type;
        $this->name = $name;
        $this->title = $title;
        $this->notice = $notice;
        $this->input = $input;
        $this->value = $value;
    }

    public static function i($type, $name, $title = '', $notice = '', $input = 'text', $value = '') {
        return new Element($type, $name, $title, $notice, $input, $value);
    }

    public static function l($type, $name, $title = '', $notice = '', $input = 'text', $value = '', $source = '', $data = '', $args = array()) {
        return Element::i($type, $name, $title, $notice, $input, $value)->data($source, $data)->args($args);
    }

    public static function info($title = '', $notice = '') {
        return Element::i('', '', $title, $notice, Type::INFO);
    }

    public static function hr($title = '', $notice = '') {
        return Element::i('', '', $title, $notice, Type::HR);
    }

    public function data($source = '', $data = '') {
        $this->source = $source;
        $this->data = $data;

        return $this;
    }

    public function args($args = array()) {
        $this->args = $args;

        return $this;
    }
}
