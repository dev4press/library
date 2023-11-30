<?php
/**
 * Name:    Dev4Press\v45\Core\Task\CRON
 * Version: v4.5
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v45\Core\Task;

use Dev4Press\v45\Core\Base\Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class CRON extends Background {
	protected $method = 'cron';
	protected $job = '';

	public function __construct() {
		parent::__construct();

		add_action( $this->job, array( $this, 'handler' ) );
	}

	protected function spawn() {
		wp_schedule_single_event( time() + $this->delay, $this->job );
	}
}
