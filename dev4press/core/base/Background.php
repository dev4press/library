<?php
/**
 * Name:    Dev4Press\v45\Core\Base\Background
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

namespace Dev4Press\v45\Core\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Background {
	protected $method = '';
	protected $transient = '';

	protected $data = array();

	protected $timer = 0;
	protected $offset = 5;
	protected $max = 0;
	protected $delay = 5;

	public function __construct() {
		$this->max   = ini_get( 'max_execution_time' );
		$this->timer = $this->now();

		wp_raise_memory_limit();
	}

	/** @return static */
	public static function instance() {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function handler() {
		$this->prepare();
		$this->get();
		$this->worker();
	}

	protected function worker() {
		if ( $this->data['status'] == 'working' ) {
			$this->save();
			$result = true;

			if ( $this->has_more() ) {
				while ( $this->has_more() && $this->is_on_time() ) {
					$result = $this->task();

					if ( ! $result ) {
						break;
					}
				}
			}

			if ( $result && $this->has_more() ) {
				$this->data['info']['threads'] ++;
				$this->save();

				// $this->spawn();
			} else {
				$this->status( 'done' );

				$this->finish();
				$this->save();
			}
		}
	}

	protected function init_data() : array {
		return array(
			'status'   => 'idle',
			'data'     => $this->defaults(),
			'messages' => array(),
			'info'     => array(
				'started' => 0,
				'ended'   => 0,
				'timer'   => 0,
				'threads' => 0,
				'total'   => 0,
				'tasks'   => 0,
				'done'    => 0,
			),
			'tasks'    => array(),
		);
	}

	protected function status( string $status ) {
		$this->data['status'] = $status;
	}

	protected function task_start( string $title ) {
		$this->data['info']['tasks'] ++;

		$this->data['tasks'][ $title ] = array(
			'title' => $title,
			'start' => $this->now(),
			'end'   => 0,
		);

		$this->save();
	}

	protected function task_end( string $title ) {
		$this->data['info']['done'] ++;
		$this->data['tasks'][ $title ]['end'] = $this->now();

		$this->save();
	}

	protected function get() {
		$this->data = get_site_transient( $this->transient );

		if ( $this->data === false ) {
			$this->data = $this->init_data();
		}
	}

	protected function save() {
		set_site_transient( $this->transient, $this->data );
	}

	protected function delete() {
		delete_site_transient( $this->transient );
	}

	protected function now() {
		return microtime( true );
	}

	protected function elapsed() {
		return $this->now() - $this->timer;
	}

	protected function is_on_time() : bool {
		return $this->elapsed() < $this->max - $this->offset;
	}

	protected function has_more() : bool {
		return $this->data['info']['done'] < $this->data['info']['total'];
	}

	abstract public function start();

	abstract public function finish();

	abstract protected function spawn();

	abstract protected function prepare();

	abstract protected function task() : bool;

	abstract protected function defaults() : array;
}
