<?php

/*
Name:    Dev4Press\Service\Media\Picsum\Image
Version: v3.3
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

namespace Dev4Press\Service\Media\Picsum;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Image {
	public $id;
	public $url;
	public $download;

	public $width;
	public $height;

	public $author;

	public function __construct( $response ) {
		$this->id       = $response->id;
		$this->url      = $response->url;
		$this->download = $response->download_url;

		$this->width  = $response->width;
		$this->height = $response->height;

		$this->author = $response->author;
	}
}