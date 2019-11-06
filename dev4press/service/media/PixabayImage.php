<?php

/*
Name:    Dev4Press\Service\Media\PixabayImage
Version: v2.9.0
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2019 Milan Petrovic (email: support@dev4press.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace Dev4Press\Service\Media;

class PixabayImage {
    public $id;
    public $type;
    public $url;
    public $likes;
    public $views;
    public $downloads;
    public $favorites;
    public $comments;
    public $tags;
    public $name;

    public $width;
    public $height;

    public $preview;
    public $web;
    public $large;
    public $fullhd;
    public $original;
    public $vector;

    public $user;

    public function __construct($response) {
        $this->id = $response->id;
        $this->type = $response->type;
        $this->url = $response->pageURL;
        $this->likes = $response->likes;
        $this->views = $response->views;
        $this->downloads = $response->downloads;
        $this->favorites = $response->favorites;
        $this->comments = $response->comments;

        $this->width = $response->imageWidth;
        $this->height = $response->imageHeight;

        $this->user = (object)array(
            'id' => $response->user_id,
            'name' => $response->user,
            'image' => (object)array('url' => $response->userImageURL)
        );

        $this->tags = explode(',', $response->tags);
        $this->tags = array_map('trim', $this->tags);

        $this->name = ucwords(join(' ', $this->tags));

        $this->preview = $this->_process_image('preview', $response);
        $this->web = $this->_process_image('webformat', $response);
        $this->large = $this->_process_image('largeImage', $response);
        $this->fullhd = $this->_process_image('fullHD', $response);
        $this->original = $this->_process_image('image', $response);
        $this->vector = $this->_process_image('vector', $response);
    }

    public function largest() {
        $list = array('original', 'fullhd', 'large', 'web');

        foreach ($list as $type) {
            if (!is_null($this->$type)) {
                return $this->$type;
            }
        }

        return null;
    }

    private function _process_image($name, $response) {
        if (!isset($response->{$name.'URL'})) {
            return null;
        }

        $obj = array(
            'url' => $response->{$name.'URL'}
        );

        if (isset($response->{$name.'Width'})) {
            $obj['width'] = $response->{$name.'Width'};
        }

        if (isset($response->{$name.'Height'})) {
            $obj['height'] = $response->{$name.'Height'};
        }

        return (object)$obj;
    }
}
