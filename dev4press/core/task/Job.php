<?php
/**
 * Name:    Dev4Press\v55\Core\Task\Job
 * Version: v5.5
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2026 Milan Petrovic (email: support@dev4press.com)
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

namespace Dev4Press\v55\Core\Task;

abstract class Job {
	protected $data;
	protected int $max;
	protected int $timer = 0;
	protected int $offset = 5;

	protected function __construct() {
		$this->timer = $this->now();
		$this->max   = absint( ini_get( 'max_execution_time' ) );

		if ( $this->max == 0 ) {
			$this->max = 30;
		}

		$this->prepare();
	}

	/** @deprecated 5.5.0 Use self::i() instead. */
	public static function instance() : static {
		return static::i();
	}

	public static function i() : static {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function run() {
		while ( $this->is_on_time() && $this->has_more() ) {
			$this->item();
		}
	}

	public function now() {
		return microtime( true );
	}

	public function elapsed() {
		return $this->now() - $this->timer;
	}

	public function is_on_time() : bool {
		return $this->elapsed() < $this->max - $this->offset;
	}

	abstract protected function prepare();

	abstract protected function item();

	abstract protected function finish();

	abstract protected function has_more() : bool;
}
