<?php

/*
Name:    Dev4Press\Service\Media\PexelsImage
Version: v3.1.1
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2020 Milan Petrovic (email: support@dev4press.com)

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

if (!defined('ABSPATH')) {
    exit;
}

class PexelsImage {
    public $id;
    public $url;
    public $name;

    public $width;
    public $height;

    public $images;

    public $photographer;

    public function __construct($response) {
        $this->id = $response->id;
        $this->url = $response->url;

        $this->width = $response->width;
        $this->height = $response->height;

        $this->photographer = (object)array(
            'id' => $response->photographer_id,
            'name' => $response->photographer,
            'url' => $response->photographer_url
        );

        $this->images = $response->src;

        preg_match('/pexels\.com\/photo\/(.+?)-\d+?\//', $this->url, $output);

        if (!empty($output) && isset($output[1])) {
            $this->name = str_replace('-', ' ', $output[1]);
            $this->name = ucfirst($this->name);
        }
    }
}
