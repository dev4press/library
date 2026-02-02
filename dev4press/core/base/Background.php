<?php
/**
 * Name:    Dev4Press\v55\Core\Base\Background
 * Version: v5.5
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4PressLibrary
 *
 * == Copyright ==
 * Copyright 2008 - 2025 Milan Petrovic (email: support@dev4press.com)
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

namespace Dev4Press\v55\Core\Base;

use Dev4Press\v55\Core\Helpers\IP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Background {
	protected string $method = '';
	protected string $transient = '';
	protected string $abort_transient = '';

	protected array $data = array();

	protected float $timer = 0;
	protected int $offset = 0;
	protected int $max = 0;
	protected int $delay = 10;
	protected string $abort = '';

	public function __construct() {
		$this->timer = $this->now();
		$this->max   = absint( ini_get( 'max_execution_time' ) );

		if ( $this->max < 1 ) {
			$this->max = 30;
		}

		if ( $this->offset == 0 ) {
			$this->offset = absint( $this->max * .6 );
		}
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

	public function is_aborted() : bool {
		return $this->abort === 'abort';
	}

	public function load() : void {
		if ( empty( $this->data ) ) {
			wp_raise_memory_limit();

			$this->prepare();
			$this->get();
		}
	}

	public function handler() : void {
		$this->load();

		if ( $this->abort == 'abort' ) {
			$this->do_abort();
		} else {
			if ( $this->get_status() == 'working' ) {
				$this->worker();
			} else if ( $this->get_status() == 'waiting' ) {
				$this->init();
			} else if ( $this->get_status() == 'idle' ) {
				$this->data['info']['started'] = $this->now();
				$this->data['info']['user_id'] = get_current_user_id();
				$this->data['info']['ip']      = IP::visitor();

				$this->add_message( __( 'Getting ready to start.', 'd4plib' ) );
				$this->status( 'waiting' );

				$this->save();
				$this->spawn();
			}
		}
	}

	public function get() : void {
		$_data  = get_site_transient( $this->transient );
		$_abort = get_site_transient( $this->abort_transient );

		if ( ! is_array( $_data ) ) {
			$this->data = $this->init_data();
		} else {
			$this->data = $_data;
		}

		if ( ! is_string( $this->abort ) ) {
			$this->abort = '';
		} else {
			$this->abort = $_abort;
		}
	}

	public function abort() : void {
		set_site_transient( $this->abort_transient, 'abort' );
	}

	public function delete() : void {
		delete_site_transient( $this->transient );

		$this->delete_abort();
	}

	public function delete_abort() : void {
		delete_site_transient( $this->abort_transient );
	}

	public function stalled() : void {
		$this->load();

		if ( $this->data['status'] == 'working' && $this->has_more() ) {
			$last = absint( $this->now() ) - absint( $this->data['info']['latest'] );

			if ( $last > $this->max * 3 ) {
				$this->spawn();
			}
		}
	}

	public function get_status() {
		return $this->data['status'];
	}

	protected function init() {
	}

	protected function prepare() {
	}

	protected function worker_done( $result ) : void {
		if ( $result && $this->has_more() ) {
			/* translators: Background process threads finished. %s: Thread elapsed time. */
			$this->add_message( sprintf( __( 'Processing thread finished after %s seconds.', 'd4plib' ), number_format( $this->elapsed(), 2 ) ) );
			$this->add_message( __( 'Spawning new background processing thread.', 'd4plib' ) );

			if ( $this->is_on_time() ) {
				$this->check_abort();

				if ( $this->is_aborted() ) {
					$this->do_abort();
				}
			}

			if ( $this->get_status() != 'abort' ) {
				$this->data['info']['threads'] ++;
				$this->save();

				$this->spawn();
			}
		} else {
			$threads = $this->data['info']['threads'];

			$this->data['info']['ended'] = $this->now();
			$this->data['info']['timer'] = $this->data['info']['ended'] - $this->data['info']['started'];

			$this->finish();

			/* translators: Background process threads message. %s: Number of threads. */
			$this->add_message( sprintf( _n( 'Process finished using %s thread.', 'Process finished using %s threads.', $threads, 'd4plib' ), $threads ) );
			$this->status( 'done' );

			$this->save();
		}

		$this->delete_abort();
	}

	protected function worker() : void {
		$this->add_message( __( 'Starting the thread worker processing.', 'd4plib' ) );

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

		$this->worker_done( $result );
	}

	protected function status( string $status ) : void {
		$this->data['status'] = $status;
	}

	protected function task_start( string $title ) : void {
		$this->data['info']['tasks'] ++;

		if ( ! isset( $this->data['tasks'][ $title ] ) ) {
			$this->data['tasks'][ $title ] = array();
		}

		$this->data['tasks'][ $title ][] = array(
			'title' => $title,
			'start' => $this->now(),
			'end'   => 0,
		);

		$this->save();
	}

	protected function task_end( string $title, bool $done = false ) : void {
		if ( $done ) {
			$this->data['info']['done'] ++;
		}

		$last_id = array_key_last( $this->data['tasks'][ $title ] );

		$this->data['tasks'][ $title ][ $last_id ]['end'] = $this->now();

		$this->save();
	}

	protected function init_data() : array {
		return array(
			'status'   => 'idle',
			'data'     => $this->defaults(),
			'messages' => array(),
			'info'     => array(
				'started' => 0,
				'ended'   => 0,
				'latest'  => 0,
				'timer'   => 0,
				'threads' => 0,
				'total'   => 0,
				'tasks'   => 0,
				'done'    => 0,
				'user_id' => 0,
				'ip'      => '',
			),
			'tasks'    => array(),
		);
	}

	protected function save() : void {
		$this->data['info']['latest'] = $this->now();

		set_site_transient( $this->transient, $this->data );
	}

	protected function now() : float {
		return microtime( true );
	}

	protected function elapsed() : float {
		return $this->now() - $this->timer;
	}

	protected function is_on_time() : bool {
		return $this->elapsed() < $this->max - $this->offset;
	}

	protected function has_more() : bool {
		return $this->data['info']['done'] < $this->data['info']['total'];
	}

	protected function add_message( string $message, string $type = 'system' ) : void {
		$this->data['messages'][] = array(
			'time'    => $this->now(),
			'message' => $message,
			'type'    => $type,
		);
	}

	protected function check_abort() : void {
		wp_cache_delete( $this->abort_transient, 'site-transient' );

		$this->abort = get_site_transient( $this->abort_transient );

		if ( ! is_string( $this->abort ) ) {
			$this->abort = '';
		}
	}

	protected function do_abort() : void {
		$this->add_message( __( 'Process has been aborted.', 'd4plib' ) );
		$this->status( 'abort' );

		$this->save();
	}

	abstract public function start() : void;

	abstract public function finish() : void;

	abstract protected function spawn() : void;

	abstract protected function task() : bool;

	abstract protected function defaults() : array;
}
