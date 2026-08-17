<?php
/**
 * Name:    Dev4Press\v56\Core\Task\Runner
 * Version: v5.6
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

namespace Dev4Press\v56\Core\Task;

use DateTime;
use Dev4Press\v56\Core\Base\Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Runner {
	protected string $name = 'd4plib';
	protected string $scope = 'items';
	protected array $data = array();
	protected Background $job;

	protected function __construct() {
	}

	public static function i() : static {
		static $instance = array();

		if ( ! isset( $instance[ static::class ] ) ) {
			$instance[ static::class ] = new static();
		}

		return $instance[ static::class ];
	}

	public function job_run() : void {
		$this->job_clear();
		$this->job->start();
	}

	public function job_clear() : void {
		$this->job->delete();
	}

	public function job_abort() : void {
		$this->job->abort();
	}

	public function current_status() {
		return $this->data['status'] ?? 'empty';
	}

	public function current_data() {
		return $this->data['data'] ?? array();
	}

	public function current_messages() {
		return $this->data['messages'] ?? array();
	}

	public function current_info() {
		return $this->data['info'] ?? array();
	}

	public function current_tasks() {
		return $this->data['tasks'] ?? array();
	}

	public function current_completed() : array {
		$completed = array(
			'done'  => $this->data['info']['done'] ?? 0,
			'total' => $this->data['info']['total'] ?? 0,
		);

		$completed['percentage'] = $completed['total'] > 0 ? round( ( $completed['done'] / $completed['total'] ) * 100, 2 ) : 0;

		return $completed;
	}

	public function get_name() : string {
		return $this->name;
	}

	public function get_scope() : string {
		return $this->scope;
	}

	public function get_action_nonce( string $name ) : string {
		return $this->name . '-runner-nonce-' . $name;
	}

	public function get_action_code( string $name ) : string {
		return $this->name . '-runner-code-' . $name;
	}

	public function get_tabs() : array {
		$_tabs   = array();
		$_status = $this->current_status();

		if ( $_status === 'empty' ) {
			$_tabs['prepare'] = array(
				'label'   => __( 'Preparation', 'd4plib' ),
				'icon'    => 'ui-sync',
				'include' => '',
			);
		} else {
			if ( $_status === 'done' || $_status === 'abort' ) {
				$_tabs['results'] = array(
					'label'   => __( 'Results', 'd4plib' ),
					'icon'    => 'ui-check-square',
					'include' => '',
				);
			}

			$_tabs['messages'] = array(
				'label'   => __( 'Messages', 'd4plib' ),
				'icon'    => 'ui-list',
				'include' => '',
			);
		}

		return $_tabs;
	}

	public function render_messages() : string {
		$messages = $this->current_messages();

		if ( ! in_array( $this->current_status(), array( 'empty', 'done' ) ) ) {
			$messages = array_reverse( $messages );
		}

		$_icons = array(
			'info'     => 'ui-info',
			'system'   => 'ui-server',
			'activity' => 'ui-play',
			'warning'  => 'ui-warning',
			'error'    => 'ui-close-square',
		);

		$_labels = array(
			'info'     => __( 'Process', 'd4plib' ),
			'system'   => __( 'System', 'd4plib' ),
			'activity' => __( 'Activity', 'd4plib' ),
			'warning'  => __( 'Warning', 'd4plib' ),
			'error'    => __( 'Error', 'd4plib' ),
		);

		$status = $this->current_completed();
		$render = '<ul data-total="' . $status['total'] . '" data-done="' . $status['done'] . '" data-percentage="' . $status['percentage'] . '">';

		foreach ( $messages as $message ) {
			$now = DateTime::createFromFormat( 'U.u', $message['time'] );

			if ( $now === false ) {
				$now = DateTime::createFromFormat( 'U', absint( $message['time'] ?? 0 ) );
			}

			$date_time = $now === false ? '/' : $now->format( 'Y-m-d H:i:s' );

			$render .= '<li class="__message __message-' . esc_attr( $message['type'] ) . '">';
			$render .= '<span class="__date-time">' . $date_time . '</span>';
			$render .= '<span class="__icon" title="' . esc_attr( $_labels[ $message['type'] ] ) . '"><i class="d4p-icon d4p-' . esc_attr( $_icons[ $message['type'] ] ) . ' d4p-icon-fw"></i></span>';
			$render .= '<span class="__content">' . esc_html( $message['message'] ) . '</span>';
			$render .= '</li>';
		}

		$render .= '</ul>';

		return $render;
	}

	public function list_statuses() : array {
		return array(
			'empty'   => array(
				'label' => __( 'Empty', 'd4plib' ),
				'color' => 'purple',
			),
			'idle'    => array(
				'label' => __( 'Idle', 'd4plib' ),
				'color' => 'purple',
			),
			'done'    => array(
				'label' => __( 'Done', 'd4plib' ),
				'color' => 'green',
			),
			'error'   => array(
				'label' => __( 'Error', 'd4plib' ),
				'color' => 'red',
			),
			'abort'   => array(
				'label' => __( 'Aborted', 'd4plib' ),
				'color' => 'red',
			),
			'working' => array(
				'label' => __( 'Working', 'd4plib' ),
				'color' => 'blue',
			),
			'waiting' => array(
				'label' => __( 'Waiting', 'd4plib' ),
				'color' => 'blue',
			),
		);
	}
}
