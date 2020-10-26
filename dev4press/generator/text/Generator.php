<?php

/*
Name:    Dev4Press\Generator\Text\Generator
Version: v3.3
Author:  Milan Petrovic
Email:   support@dev4press.com
Website: https://www.dev4press.com/

== Notice ==
Based on the LoremImpsum script by Josh Sherman
https://github.com/joshtronic/php-loremipsum

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

namespace Dev4Press\Generator\Text;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Generator {
	protected $sentence_mean = 24.46;
	protected $sentence_dev = 5.08;

	protected $paragraph_mean = 5.8;
	protected $paragraph_dev = 1.93;

	public function __construct() {
	}

	public function word( $tags = false ) {
		return $this->words( 1, $tags );
	}

	public function sentence( $tags = false ) {
		return $this->sentences( 1, $tags );
	}

	public function sentences( $count = 1, $tags = false, $array = false ) {
		$sentences = array();

		for ( $i = 0; $i < $count; $i ++ ) {
			$sentences[] = $this->words( $this->gauss( $this->sentence_mean, $this->sentence_dev ), false, true );
		}

		$this->punctuate( $sentences );

		return $this->output( $sentences, $tags, $array );
	}

	public function set_sentence_gauss( $mean = 24.46, $dev = 5.08 ) {
		$this->sentence_mean = floatval( $mean );
		$this->sentence_dev  = floatval( $dev );

		return $this;
	}

	public function set_paragraph_gauss( $mean = 5.8, $dev = 1.93 ) {
		$this->paragraph_mean = floatval( $mean );
		$this->paragraph_dev  = floatval( $dev );

		return $this;
	}

	public function paragraph( $tags = false ) {
		return $this->paragraphs( 1, $tags );
	}

	public function paragraphs( $count = 1, $tags = false, $array = false ) {
		$paragraphs = array();

		for ( $i = 0; $i < $count; $i ++ ) {
			$paragraphs[] = $this->sentences( $this->gauss( $this->paragraph_mean, $this->paragraph_dev ) );
		}

		return $this->output( $paragraphs, $tags, $array, "\n\n" );
	}

	public function html( $paragraphs = 1, $settings = array(), $array = false ) {
		$items = array();

		for ( $i = 0; $i < $paragraphs; $i ++ ) {
			$p = $this->paragraph( false );

			if ( in_array( 'decorate', $settings ) ) {
				$p = $this->random_tagify( $p, 'strong' );
				$p = $this->random_tagify( $p, 'i' );
			}

			if ( in_array( 'link', $settings ) ) {
				$p = $this->random_tagify( $p, 'a', .05 );
			}

			$items[] = '<p>' . $p . '</p>';
		}

		if ( in_array( 'bq', $settings ) ) {
			$max   = rand( 1, $paragraphs );
			$items = array_merge( $items, $this->sentences( $max, array( 'blockquote' ), true ) );
		}

		if ( in_array( 'code', $settings ) ) {
			$max   = rand( 1, $paragraphs );
			$items = array_merge( $items, $this->sentences( $max, array( 'pre' ), true ) );
		}

		if ( in_array( 'dl', $settings ) ) {
			$max = rand( 1, $paragraphs ) * 3;

			$list = array();

			$list[] = '<dl>';
			for ( $i = 0; $i < $max; $i ++ ) {
				$list[] = '<dt>' . ucfirst( $this->words( 2, false, false ) ) . '</dt>';
				$list[] = $this->sentences( 1, array( 'dd' ), false );
			}
			$list[] = '</dl>';

			$items[] = join( '', $list );
		}

		if ( in_array( 'ul', $settings ) ) {
			$max = rand( 1, $paragraphs ) * 3;

			$list = array();

			$list[] = '<ul>';
			for ( $i = 0; $i < $max; $i ++ ) {
				$list[] = $this->sentences( 1, array( 'li' ), false );
			}
			$list[] = '</ul>';

			$items[] = join( '', $list );
		}

		if ( in_array( 'ol', $settings ) ) {
			$max = rand( 1, $paragraphs ) * 3;

			$list = array();

			$list[] = '<ol>';
			for ( $i = 0; $i < $max; $i ++ ) {
				$list[] = $this->sentences( 1, array( 'li' ), false );
			}
			$list[] = '</ol>';

			$items[] = join( '', $list );
		}

		if ( in_array( 'headers', $settings ) ) {
			$max   = rand( 1, $paragraphs );
			$items = array_merge( $items, $this->set_sentence_gauss( 5, 1 )->sentences( $max, array(
				'h2',
				'h3',
				'h4',
				'h5',
				'h6'
			), true ) );
		}

		$this->set_paragraph_gauss();

		shuffle( $items );

		if ( ! $array ) {
			$items = join( '', $items );
		}

		return $items;
	}

	protected function gauss( $mean, $std_dev ) {
		$x = mt_rand() / mt_getrandmax();
		$y = mt_rand() / mt_getrandmax();

		$z = sqrt( - 2 * log( $x ) ) * cos( 2 * pi() * $y );

		return $z * $std_dev + $mean;
	}

	protected function punctuate( &$sentences ) {
		foreach ( $sentences as $key => $sentence ) {
			$words = count( $sentence );

			if ( $words > 4 ) {
				$mean    = log( $words, 6 );
				$std_dev = $mean / 6;
				$commas  = round( $this->gauss( $mean, $std_dev ) );

				for ( $i = 1; $i <= $commas; $i ++ ) {
					$word = round( $i * $words / ( $commas + 1 ) );

					if ( $word < ( $words - 1 ) && $word > 0 ) {
						$sentence[ $word ] .= ',';
					}
				}
			}

			$sentences[ $key ] = ucfirst( implode( ' ', $sentence ) . '.' );
		}
	}

	protected function output( $strings, $tags = false, $array = false, $delimiter = ' ' ) {
		if ( $tags ) {
			$tags      = (array) $tags;
			$delimiter = '';

			foreach ( $strings as $key => $string ) {
				foreach ( $tags as $tag ) {
					if ( $tag[0] == '<' ) {
						$string = str_replace( '$1', $string, $tag );
					} else {
						$string = sprintf( '<%1$s>%2$s</%1$s>', $tag, $string );
					}

					$strings[ $key ] = $string;
				}
			}
		}

		if ( ! $array ) {
			$strings = implode( $delimiter, $strings );
		}

		return $strings;
	}

	protected function random_tagify( $content, $tag = 'strong', $frequency = .2 ) {
		$result = array();

		$items = explode( ' ', $content );
		$total = count( $items );
		$max   = rand( 1, floor( $total * $frequency ) );

		$pick = (array)array_rand( $items, $max );

		foreach ( $items as $id => $word ) {
			if ( in_array( $id, $pick ) ) {
				if ( $tag == 'a' ) {
					$result[] = '<a href="#">' . $word . '</a>';
				} else {
					$result[] = '<' . $tag . '>' . $word . '</' . $tag . '>';
				}
			} else {
				$result[] = $word;
			}
		}

		return join( ' ', $result );
	}

	abstract function words( $count = 1, $tags = false, $array = false );
}
