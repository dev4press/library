<?php

/*
Name:    Dev4Press\v35\Core\Helpers\Languages
Version: v3.5
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Copyright ==
Copyright 2008 - 2021 Milan Petrovic (email: support@dev4press.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>
*/

namespace Dev4Press\v35\Core\Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Languages {
	static private $_current_instance = null;

	public $list = array(
		'hr'    => array( 'native' => 'Hrvatski', 'english' => 'Croatian' ),
		'ja'    => array( 'native' => '日本語', 'english' => 'Japanese' ),
		'da_DK' => array( 'native' => 'Dansk', 'english' => 'Danish' ),
		'de_AT' => array( 'native' => 'Deutsch Österreich', 'english' => 'German Austria' ),
		'de_DE' => array( 'native' => 'Deutsch', 'english' => 'German' ),
		'de_CH' => array( 'native' => 'Deutsch Schweiz', 'english' => 'German Switzerland' ),
		'es_AR' => array( 'native' => 'Español de Argentina', 'english' => 'Spanish Argentina' ),
		'es_ES' => array( 'native' => 'Español', 'english' => 'Spanish' ),
		'es_MX' => array( 'native' => 'Español de México', 'english' => 'Spanish Mexico' ),
		'fr_FR' => array( 'native' => 'Français', 'english' => 'French' ),
		'fr_BE' => array( 'native' => 'Français de Belgique', 'english' => 'French Belgian' ),
		'fr_CA' => array( 'native' => 'Français Canadien', 'english' => 'French Canadian' ),
		'it_IT' => array( 'native' => 'Italiano', 'english' => 'Italian' ),
		'nl_NL' => array( 'native' => 'Nederlands', 'english' => 'Dutch' ),
		'pl_PL' => array( 'native' => 'Polski', 'english' => 'Polish' ),
		'pt_BR' => array( 'native' => 'Português do Brasil', 'english' => 'Brazilian Portuguese' ),
		'pt_PT' => array( 'native' => 'Português', 'english' => 'Portuguese' ),
		'ru_RU' => array( 'native' => 'Русский', 'english' => 'Russian' ),
		'sr_RS' => array( 'native' => 'Српски', 'english' => 'Serbian' )
	);

	public function __construct() {
	}

	public static function instance() {
		if ( is_null( self::$_current_instance ) ) {
			self::$_current_instance = new Languages();
		}

		return self::$_current_instance;
	}

	public function plugin_translations( $translations ) {
		$list = array();

		foreach ( $translations as $code => $obj ) {
			$list[ $code ] = $this->list[ $code ] + $obj;

			if ( ! isset( $list[ $code ]['contributors'] ) ) {
				$list[ $code ] += array( 'contributors' => array() );
			}
		}

		return $list;
	}
}
