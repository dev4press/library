<?php

/*
Name:    Dev4Press\Generator\Text\LoremIpsum
Version: v3.0.1
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

if (!defined('ABSPATH')) {
    exit;
}

class LoremIpsum extends Generator {
    protected $first = true;
    protected $first_count = 8;

    public $words = array(
        'lorem',
        'ipsum',
        'dolor',
        'sit',
        'amet',
        'consectetur',
        'adipiscing',
        'elit',
        'a',
        'ac',
        'accumsan',
        'ad',
        'aenean',
        'aliquam',
        'aliquet',
        'ante',
        'aptent',
        'arcu',
        'at',
        'auctor',
        'augue',
        'bibendum',
        'blandit',
        'class',
        'commodo',
        'condimentum',
        'congue',
        'consequat',
        'conubia',
        'convallis',
        'cras',
        'cubilia',
        'curabitur',
        'curae',
        'cursus',
        'dapibus',
        'diam',
        'dictum',
        'dictumst',
        'dignissim',
        'dis',
        'donec',
        'dui',
        'duis',
        'efficitur',
        'egestas',
        'eget',
        'eleifend',
        'elementum',
        'enim',
        'erat',
        'eros',
        'est',
        'et',
        'etiam',
        'eu',
        'euismod',
        'ex',
        'facilisi',
        'facilisis',
        'fames',
        'faucibus',
        'felis',
        'fermentum',
        'feugiat',
        'finibus',
        'fringilla',
        'fusce',
        'gravida',
        'habitant',
        'habitasse',
        'hac',
        'hendrerit',
        'himenaeos',
        'iaculis',
        'id',
        'imperdiet',
        'in',
        'inceptos',
        'integer',
        'interdum',
        'justo',
        'lacinia',
        'lacus',
        'laoreet',
        'lectus',
        'leo',
        'libero',
        'ligula',
        'litora',
        'lobortis',
        'luctus',
        'maecenas',
        'magna',
        'magnis',
        'malesuada',
        'massa',
        'mattis',
        'mauris',
        'maximus',
        'metus',
        'mi',
        'molestie',
        'mollis',
        'montes',
        'morbi',
        'mus',
        'nam',
        'nascetur',
        'natoque',
        'nec',
        'neque',
        'netus',
        'nibh',
        'nisi',
        'nisl',
        'non',
        'nostra',
        'nulla',
        'nullam',
        'nunc',
        'odio',
        'orci',
        'ornare',
        'parturient',
        'pellentesque',
        'penatibus',
        'per',
        'pharetra',
        'phasellus',
        'placerat',
        'platea',
        'porta',
        'porttitor',
        'posuere',
        'potenti',
        'praesent',
        'pretium',
        'primis',
        'proin',
        'pulvinar',
        'purus',
        'quam',
        'quis',
        'quisque',
        'rhoncus',
        'ridiculus',
        'risus',
        'rutrum',
        'sagittis',
        'sapien',
        'scelerisque',
        'sed',
        'sem',
        'semper',
        'senectus',
        'sociosqu',
        'sodales',
        'sollicitudin',
        'suscipit',
        'suspendisse',
        'taciti',
        'tellus',
        'tempor',
        'tempus',
        'tincidunt',
        'torquent',
        'tortor',
        'tristique',
        'turpis',
        'ullamcorper',
        'ultrices',
        'ultricies',
        'urna',
        'ut',
        'varius',
        'vehicula',
        'vel',
        'velit',
        'venenatis',
        'vestibulum',
        'vitae',
        'vivamus',
        'viverra',
        'volutpat',
        'vulputate'
    );
}
