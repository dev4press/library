<?php
/**
 * Name:    Dev4Press\v56\Core\Mailer\Detection
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

namespace Dev4Press\v56\Core\Mailer;

use Dev4Press\v56\Core\Helpers\Source;
use Dev4Press\v56\Core\Quick\Str;
use Dev4Press\v56\Library;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Detection {
	protected array $detection;
	protected array $supported = array();
	protected array $aliases = array(
		'bp_send_email',
	);
	protected bool $has_regex = false;

	protected function __construct() {
		$this->reset();
		$this->init();
		$this->listen();
	}

	/** @deprecated 5.5.0 Use self::i() instead. */
	public static function instance() : static {
		return static::i();
	}

	public static function i() : static {
		static $instance = false;

		if ( $instance === false ) {
			$instance = new static();
		}

		return $instance;
	}

	public function reset() : void {
		$this->detection = array(
			'name' => '',
			'data' => '',
			'call' => array(),
		);
	}

	public function broadcast( $atts ) {
		$name = $this->detection['name'];

		if ( ! empty( $name ) && isset( $this->supported[ $name ] ) ) {
			$this->detection['data']          = $this->supported[ $name ];
			$this->detection['data']['label'] = $this->get_label( $name );
		}

		$this->caller();

		/** HOOK: `dev4press_v56_mailer_notification_detected` */
		do_action( Library::i()->hook( 'mailer_notification_detected' ), $this->detection, $atts );

		return $atts;
	}

	public function get_label( $name = '' ) : string {
		if ( ! empty( $this->supported[ $name ]['label'] ) ) {
			return $this->supported[ $name ]['label'];
		}

		$labels = $this->get_labels();

		return $labels[ $name ] ?? _x( 'Unknown', 'Email Detection Type', 'd4plib' );
	}

	public function get_data( string $code ) {
		return $this->supported[ $code ] ?? array();
	}

	public function get_supported_types() : array {
		return $this->supported;
	}

	public function test_regex( $atts, $regex ) : bool {
		$tests = array();

		if ( $regex['subject'] !== false && ! empty( $atts['subject'] ) ) {
			$tests['subject'] = preg_match( $regex['subject'], $atts['subject'] ) !== false;
		}

		if ( $regex['message'] !== false && ! empty( $atts['message'] ) ) {
			$tests['message'] = preg_match( $regex['message'], $atts['message'] ) !== false;
		}

		if ( $regex['headers'] !== false && ! empty( $atts['headers'] ) ) {
			$tests['headers'] = false;

			foreach ( $atts['headers'] as $header ) {
				if ( preg_match( $regex['message'], $header ) ) {
					$tests['headers'] = true;
					break;
				}
			}
		}

		if ( empty( $tests ) ) {
			return false;
		}

		foreach ( $tests as $test ) {
			if ( $test !== true ) {
				return false;
			}
		}

		return true;
	}

	public function intercept_wp_mail( $atts ) {
		if ( is_string( $atts['headers'] ) && ! empty( $atts['headers'] ) ) {
			if ( str_contains( $atts['headers'], 'X-WPCF7-Content-Type' ) ) {
				$this->detection['name'] = 'cf7-email';
			}
		}

		if ( $this->has_regex && empty( $this->detection['name'] ) ) {
			$input = shortcode_atts( array(
				'subject' => '',
				'message' => '',
				'headers' => array(),
			), $atts );

			foreach ( $this->supported as $code => $data ) {
				if ( isset( $data['regex'] ) ) {
					if ( $this->test_regex( $input, $data['regex'] ) ) {
						$this->detection['name'] = $code;
						break;
					}
				}
			}
		}

		return $atts;
	}

	public function intercept_buddypress( &$email, $email_type ) : void {
		$this->detection['name'] = 'buddypress-' . $email_type;
	}

	public function intercept_wp_members( $return ) {
		$this->detection['name'] = 'wpmembers-' . ( ! empty( $return['tag'] ) ? $return['tag'] : 'custom' );

		return $return;
	}

	public function intercept_woocommerce( $callback, $object ) {
		$class_name = get_class( $object );

		if ( str_contains( $class_name, '_' ) ) {
			$words = explode( '_', $class_name );

			if ( $words[0] == 'WC' && $words[1] == 'Email' ) {
				$words = array_slice( $words, 2 );
			}
		} else {
			$words = Str::camelcase_to_words( $class_name );
		}

		$label = join( ' ', $words );
		$words = array_map( 'strtolower', $words );

		$this->detection['name'] = 'woocommerce-' . join( '-', $words );
		$this->detection['data'] = array(
			'source'     => 'woocommerce',
			'label'      => $label,
			'class_name' => $class_name,
		);

		return $callback;
	}

	public function intercept_filter( $return ) {
		$this->identify( current_filter(), 'filter' );

		return $return;
	}

	public function intercept_action() : void {
		$this->identify( current_action(), 'action' );
	}

	protected function caller() : void {
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
		$file_path = '';
		$file_line = '';

		foreach ( $backtrace as $item ) {
			if ( isset( $item['file'] ) && isset( $item['line'] ) && isset( $item['function'] ) ) {
				if ( in_array( $item['function'], $this->aliases ) || $item['function'] == 'wp_mail' ) {
					$file_path = $item['file'];
					$file_line = $item['line'];
					break;
				}
			}
		}

		if ( ! empty( $file_path ) ) {
			$this->detection['call']         = Source::instance()->origin( $file_path );
			$this->detection['call']['line'] = $file_line;
		}
	}

	protected function identify( string $name, string $type ) : void {
		foreach ( $this->supported as $code => $data ) {
			if ( isset( $data[ $type ] ) && $data[ $type ] == $name ) {
				$this->detection['name'] = $code;
				break;
			}
		}
	}

	protected function listen() : void {
		add_filter( 'wp_mail', array( $this, 'intercept_wp_mail' ), 1 );
		add_action( 'bp_send_email', array( $this, 'intercept_buddypress' ), 10, 2 );
		add_filter( 'wpmem_email_filter', array( $this, 'intercept_wp_members' ) );
		add_filter( 'woocommerce_mail_callback', array( $this, 'intercept_woocommerce' ), 10, 2 );

		foreach ( $this->supported as $data ) {
			if ( isset( $data['filter'] ) ) {
				add_filter( $data['filter'], array( $this, 'intercept_filter' ) );
			} else if ( isset( $data['action'] ) ) {
				add_action( $data['action'], array( $this, 'intercept_action' ) );
			}
		}

		add_filter( 'wp_mail', array( $this, 'broadcast' ), 5 );
		add_action( 'wp_mail_succeeded', array( $this, 'reset' ), 100000 );
		add_action( 'wp_mail_failed', array( $this, 'reset' ), 100000 );
	}

	protected function init() : void {
		$this->supported = array(
			'wp-comment-notify-moderator'                            => array(
				'filter' => 'comment_moderation_headers',
				'source' => 'WordPress',
			),
			'wp-comment-notify-postauthor'                           => array(
				'filter' => 'comment_notification_headers',
				'source' => 'WordPress',
			),
			'wp-email-change-confirmation'                           => array(
				'filter' => 'new_user_email_content',
				'source' => 'WordPress',
			),
			'wp-email-change-notification'                           => array(
				'filter' => 'email_change_email',
				'source' => 'WordPress',
			),
			'wp-password-change-notification'                        => array(
				'filter' => 'password_change_email',
				'source' => 'WordPress',
			),
			'wp-retrieve-password-message'                           => array(
				'filter' => 'retrieve_password_notification_email',
				'source' => 'WordPress',
			),
			'wp-privacy-personal-data-email'                         => array(
				'filter' => 'wp_privacy_personal_data_email_headers',
				'source' => 'WordPress',
			),
			'wp-privacy-request-confirmation'                        => array(
				'filter' => 'user_request_confirmed_email_subject',
				'source' => 'WordPress',
			),
			'wp-privacy-erasure-fulfillment'                         => array(
				'filter' => 'user_confirmed_action_email_content',
				'source' => 'WordPress',
			),
			'wp-send-user-request'                                   => array(
				'filter' => 'user_request_action_email_subject',
				'source' => 'WordPress',
			),
			'wp-site-admin-email-change'                             => array(
				'filter' => 'site_admin_email_change_email',
				'source' => 'WordPress',
			),
			'wp-site-admin-email-change-attempt'                     => array(
				'filter' => 'new_admin_email_content',
				'source' => 'WordPress',
			),
			'wp-auto-plugin-theme-update-email'                      => array(
				'filter' => 'auto_plugin_theme_update_email',
				'source' => 'WordPress',
			),
			'wp-auto-core-update-email'                              => array(
				'filter' => 'auto_core_update_email',
				'source' => 'WordPress',
			),
			'wp-automatic-updates-debug-email'                       => array(
				'filter' => 'automatic_updates_debug_email',
				'source' => 'WordPress',
			),
			'wp-new-user-notification-admin'                         => array(
				'filter' => 'wp_new_user_notification_email_admin',
				'source' => 'WordPress',
			),
			'wp-new-user-notification'                               => array(
				'filter' => 'wp_new_user_notification_email',
				'source' => 'WordPress',
			),
			'wp-password-change-notification-admin'                  => array(
				'filter' => 'wp_password_change_notification_email',
				'source' => 'WordPress',
			),
			'wp-recovery-mode-email'                                 => array(
				'filter' => 'recovery_mode_email',
				'source' => 'WordPress',
			),
			'wp-network-signup-new-site-created'                     => array(
				'filter' => 'new_site_email',
				'source' => 'WordPress',
			),
			'wp-network-signup-blog-confirmation'                    => array(
				'filter' => 'wpmu_signup_blog_notification_subject',
				'source' => 'WordPress',
			),
			'wp-network-signup-user-confirmation'                    => array(
				'filter' => 'wpmu_signup_user_notification_subject',
				'source' => 'WordPress',
			),
			'wp-network-delete-site-email-content'                   => array(
				'filter' => 'delete_site_email_content',
				'source' => 'WordPress',
			),
			'wp-network-welcome-blog'                                => array(
				'filter' => 'update_welcome_subject',
				'source' => 'WordPress',
			),
			'wp-network-welcome-user'                                => array(
				'filter' => 'update_welcome_user_subject',
				'source' => 'WordPress',
			),
			'wp-network-new-blog-siteadmin'                          => array(
				'filter' => 'newblog_notify_siteadmin',
				'source' => 'WordPress',
			),
			'wp-network-new-user-siteadmin'                          => array(
				'filter' => 'newuser_notify_siteadmin',
				'source' => 'WordPress',
			),
			'wp-network-network-admin-email-confirmation'            => array(
				'filter' => 'new_network_admin_email_content',
				'source' => 'WordPress',
			),
			'wp-network-network-admin-email-notification'            => array(
				'filter' => 'network_admin_email_change_email',
				'source' => 'WordPress',
			),
			'coreactivity-instant-notification'                      => array(
				'filter' => 'coreactivity_instant_notification_email',
				'source' => 'coreActivity',
			),
			'coreactivity-daily-digest'                              => array(
				'filter' => 'coreactivity_daily_digest_email',
				'source' => 'coreActivity',
			),
			'coreactivity-weekly-digest'                             => array(
				'filter' => 'coreactivity_weekly_digest_email',
				'source' => 'coreActivity',
			),
			'coresecurity-digest-daily'                              => array(
				'filter' => 'coresecurity-digest-email-subject-daily',
				'source' => 'coreSecurity',
			),
			'coresecurity-digest-weekly'                             => array(
				'filter' => 'coresecurity-digest-email-subject-weekly',
				'source' => 'coreSecurity',
			),
			'coresecurity-digest-monthly'                            => array(
				'filter' => 'coresecurity-digest-email-subject-monthly',
				'source' => 'coreSecurity',
			),
			'coresecurity-instant-notification'                      => array(
				'filter' => 'coresecurity-instant-email-subject',
				'source' => 'coreSecurity',
			),
			'coresecurity-user-notification'                         => array(
				'filter' => 'coresecurity-user-notification-email-subject',
				'source' => 'coreSecurity',
			),
			'gdpol-digest-notify-moderators'                         => array(
				'action' => 'gdpol_daily_digest_notify_moderators_pre_notify',
				'source' => 'topicPolls for bbPress',
			),
			'gdpol-digest-notify-author'                             => array(
				'action' => 'gdpol_daily_digest_notify_author_pre_notify',
				'source' => 'topicPolls for bbPress',
			),
			'gdpol-instant-notify'                                   => array(
				'action' => 'gdpol_instant_notify_pre_notify',
				'source' => 'topicPolls for bbPress',
			),
			'bbpress-new-reply-in-topic'                             => array(
				'action' => 'bbp_pre_notify_subscribers',
				'source' => 'bbPress',
			),
			'bbpress-new-topic-in-forum'                             => array(
				'action' => 'bbp_pre_notify_forum_subscribers',
				'source' => 'bbPress',
			),
			'gd-bbpress-toolbox-topic-auto-close'                    => array(
				'action' => 'bbp_pre_notify_topic_auto_close',
				'source' => 'GD bbPress Toolbox',
			),
			'gd-bbpress-toolbox-topic-manual-close'                  => array(
				'action' => 'bbp_pre_notify_topic_manual_close',
				'source' => 'GD bbPress Toolbox',
			),
			'gd-bbpress-toolbox-topic-edit'                          => array(
				'action' => 'bbp_pre_notify_topic_edit_subscribers',
				'source' => 'GD bbPress Toolbox',
			),
			'gd-bbpress-toolbox-reply-edit'                          => array(
				'action' => 'bbp_pre_notify_reply_edit_subscribers',
				'source' => 'GD bbPress Toolbox',
			),
			'gd-bbpress-toolbox-new-topic-moderators'                => array(
				'action' => 'bbp_pre_notify_new_topic_moderators',
				'source' => 'GD bbPress Toolbox',
			),
			'gd-bbpress-toolbox-new-reply-moderators'                => array(
				'action' => 'bbp_pre_notify_new_reply_moderators',
				'source' => 'GD bbPress Toolbox',
			),
			'wpmembers-custom'                                       => array(
				'source' => 'WP Members',
			),
			'wpmembers-newreg'                                       => array(
				'source' => 'WP Members',
			),
			'wpmembers-newmod'                                       => array(
				'source' => 'WP Members',
			),
			'wpmembers-appmod'                                       => array(
				'source' => 'WP Members',
			),
			'wpmembers-repass'                                       => array(
				'source' => 'WP Members',
			),
			'wpmembers-getuser'                                      => array(
				'source' => 'WP Members',
			),
			'wpmembers-admin-notify'                                 => array(
				'filter' => 'wpmem_notify_filter',
				'source' => 'WP Members',
			),
			'asgaros-subscriber-new-topic'                           => array(
				'filter' => 'asgarosforum_subscriber_mails_new_topic',
				'source' => 'Asgaros Forum',
			),
			'asgaros-subscriber-new-post'                            => array(
				'filter' => 'asgarosforum_subscriber_mails_new_post',
				'source' => 'Asgaros Forum',
			),
			'rank-math-auto-update-email'                            => array(
				'filter' => 'rank_math/auto_update_email',
				'source' => 'Rank Math',
			),
			'cf7-email'                                              => array(
				'source' => 'Contact Form 7',
			),
			'buddypress-core-user-registration-with-blog'            => array(
				'source' => 'BuddyPress',
			),
			'buddypress-core-user-registration'                      => array(
				'source' => 'BuddyPress',
			),
			'buddypress-core-user-activation'                        => array(
				'source' => 'BuddyPress',
			),
			'buddypress-bp-members-invitation'                       => array(
				'source' => 'BuddyPress',
			),
			'buddypress-members-membership-request'                  => array(
				'source' => 'BuddyPress',
			),
			'buddypress-members-membership-request-rejected'         => array(
				'source' => 'BuddyPress',
			),
			'buddypress-friends-request'                             => array(
				'source' => 'BuddyPress',
			),
			'buddypress-friends-request-accepted'                    => array(
				'source' => 'BuddyPress',
			),
			'buddypress-activity-comment'                            => array(
				'source' => 'BuddyPress',
			),
			'buddypress-activity-comment-author'                     => array(
				'source' => 'BuddyPress',
			),
			'buddypress-messages-unread'                             => array(
				'source' => 'BuddyPress',
			),
			'buddypress-settings-verify-email-change'                => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-details-updated'                      => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-membership-request'                   => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-membership-request-accepted'          => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-membership-request-rejected'          => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-membership-request-accepted-by-admin' => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-membership-request-rejected-by-admin' => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-member-promoted'                      => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-invitation'                           => array(
				'source' => 'BuddyPress',
			),
			'buddypress-groups-at-message'                           => array(
				'source' => 'BuddyPress',
			),
			'buddypress-activity-at-message'                         => array(
				'source' => 'BuddyPress',
			),
			'woocommerce-low-stock'                                  => array(
				'filter' => 'woocommerce_email_recipient_low_stock',
				'source' => 'WooCommerce',
			),
			'woocommerce-no-stock'                                   => array(
				'filter' => 'woocommerce_email_recipient_no_stock',
				'source' => 'WooCommerce',
			),
			'woocommerce-low-backorder'                              => array(
				'filter' => 'woocommerce_email_recipient_backorder',
				'source' => 'WooCommerce',
			),
		);

		/** HOOK: `dev4press_v56_mailer_custom_regex` */
		$_custom_regex = apply_filters( Library::i()->hook( 'mailer_custom_regex' ), array() );

		foreach ( $_custom_regex as $regex ) {
			$regex = shortcode_atts( array(
				'code'   => '',
				'label'  => '',
				'source' => '',
				'regex'  => array(
					'subject' => false,
					'message' => false,
					'headers' => false,
				),
			), $regex );

			if ( ! empty( $regex['code'] ) && ! empty( $regex['label'] ) && ! empty( $regex['source'] ) && ( isset( $regex['regex']['subject'] ) || isset( $regex['regex']['message'] ) || isset( $regex['regex']['headers'] ) ) ) {
				if ( ! isset( $this->supported[ $regex['code'] ] ) ) {
					$this->has_regex = true;

					$this->supported[ $regex['code'] ] = array(
						'label'  => $regex['label'],
						'source' => $regex['source'],
						'regex'  => array(
							'subject' => $regex['regex']['subject'] ?? '',
							'message' => $regex['regex']['message'] ?? '',
							'headers' => $regex['regex']['headers'] ?? '',
						),
					);
				}
			}
		}
	}

	public function get_labels() : array {
		return array(
			'wp-comment-notify-moderator'                            => _x( 'Comment Notify Moderator', 'Email Detection Type', 'd4plib' ),
			'wp-comment-notify-postauthor'                           => _x( 'Comment Notify Post Author', 'Email Detection Type', 'd4plib' ),
			'wp-email-change-confirmation'                           => _x( 'Email Change Confirmation', 'Email Detection Type', 'd4plib' ),
			'wp-email-change-notification'                           => _x( 'Email Change Notification', 'Email Detection Type', 'd4plib' ),
			'wp-password-change-notification'                        => _x( 'Password Change Notification', 'Email Detection Type', 'd4plib' ),
			'wp-retrieve-password-message'                           => _x( 'Retrieve Password Notification', 'Email Detection Type', 'd4plib' ),
			'wp-privacy-personal-data-email'                         => _x( 'Privacy Personal Data Email', 'Email Detection Type', 'd4plib' ),
			'wp-privacy-request-confirmation'                        => _x( 'Privacy Request Confirmation', 'Email Detection Type', 'd4plib' ),
			'wp-privacy-erasure-fulfillment'                         => _x( 'Privacy Erasure Fulfillment', 'Email Detection Type', 'd4plib' ),
			'wp-send-user-request'                                   => _x( 'Send User Request', 'Email Detection Type', 'd4plib' ),
			'wp-site-admin-email-change'                             => _x( 'Site Admin Email Change', 'Email Detection Type', 'd4plib' ),
			'wp-site-admin-email-change-attempt'                     => _x( 'Site Admin Email Change Attempt', 'Email Detection Type', 'd4plib' ),
			'wp-auto-plugin-theme-update-email'                      => _x( 'Plugin or Theme Update Email', 'Email Detection Type', 'd4plib' ),
			'wp-auto-core-update-email'                              => _x( 'Core Update Email', 'Email Detection Type', 'd4plib' ),
			'wp-automatic-updates-debug-email'                       => _x( 'Auto Update Debug Email', 'Email Detection Type', 'd4plib' ),
			'wp-new-user-notification-admin'                         => _x( 'New User Notification Admin', 'Email Detection Type', 'd4plib' ),
			'wp-new-user-notification'                               => _x( 'New User Notification', 'Email Detection Type', 'd4plib' ),
			'wp-password-change-notification-admin'                  => _x( 'Password Change Notification Admin', 'Email Detection Type', 'd4plib' ),
			'wp-recovery-mode-email'                                 => _x( 'Recovery Mode Email', 'Email Detection Type', 'd4plib' ),
			'wp-network-signup-new-site-created'                     => _x( 'New Site Created', 'Email Detection Type', 'd4plib' ),
			'wp-network-signup-blog-confirmation'                    => _x( 'Signup Blog Confirmation', 'Email Detection Type', 'd4plib' ),
			'wp-network-signup-user-confirmation'                    => _x( 'Signup User Confirmation', 'Email Detection Type', 'd4plib' ),
			'wp-network-delete-site-email-content'                   => _x( 'Site Deleted Email', 'Email Detection Type', 'd4plib' ),
			'wp-network-welcome-blog'                                => _x( 'Welcome Blog', 'Email Detection Type', 'd4plib' ),
			'wp-network-welcome-user'                                => _x( 'Welcome User', 'Email Detection Type', 'd4plib' ),
			'wp-network-new-blog-siteadmin'                          => _x( 'New Blog Site Admin', 'Email Detection Type', 'd4plib' ),
			'wp-network-new-user-siteadmin'                          => _x( 'New User Site Admin', 'Email Detection Type', 'd4plib' ),
			'wp-network-network-admin-email-confirmation'            => _x( 'Network Admin Email Confirmation', 'Email Detection Type', 'd4plib' ),
			'wp-network-network-admin-email-notification'            => _x( 'Network Admin Email Notification', 'Email Detection Type', 'd4plib' ),
			'coreactivity-instant-notification'                      => _x( 'Instant Notification', 'Email Detection Type', 'd4plib' ),
			'coreactivity-daily-digest'                              => _x( 'Daily Digest', 'Email Detection Type', 'd4plib' ),
			'coreactivity-weekly-digest'                             => _x( 'Weekly Digest', 'Email Detection Type', 'd4plib' ),
			'coresecurity-digest-daily'                              => _x( 'Daily Digest', 'Email Detection Type', 'd4plib' ),
			'coresecurity-digest-weekly'                             => _x( 'Weekly Digest', 'Email Detection Type', 'd4plib' ),
			'coresecurity-digest-monthly'                            => _x( 'Monthly Digest', 'Email Detection Type', 'd4plib' ),
			'coresecurity-instant-notification'                      => _x( 'Instant Notification', 'Email Detection Type', 'd4plib' ),
			'coresecurity-user-notification'                         => _x( 'User Notification', 'Email Detection Type', 'd4plib' ),
			'gdpol-digest-notify-moderators'                         => _x( 'Digest Notify Moderators', 'Email Detection Type', 'd4plib' ),
			'gdpol-digest-notify-author'                             => _x( 'Digest Notify Author', 'Email Detection Type', 'd4plib' ),
			'gdpol-instant-notify'                                   => _x( 'Instant Notify', 'Email Detection Type', 'd4plib' ),
			'bbpress-new-reply-in-topic'                             => _x( 'New Reply In Topic', 'Email Detection Type', 'd4plib' ),
			'bbpress-new-topic-in-forum'                             => _x( 'New Topic In Forum', 'Email Detection Type', 'd4plib' ),
			'gd-bbpress-toolbox-topic-auto-close'                    => _x( 'Topic Auto Close', 'Email Detection Type', 'd4plib' ),
			'gd-bbpress-toolbox-topic-manual-close'                  => _x( 'Topic Manual Close', 'Email Detection Type', 'd4plib' ),
			'gd-bbpress-toolbox-topic-edit'                          => _x( 'Topic Edit', 'Email Detection Type', 'd4plib' ),
			'gd-bbpress-toolbox-reply-edit'                          => _x( 'Reply Edit', 'Email Detection Type', 'd4plib' ),
			'gd-bbpress-toolbox-new-topic-moderators'                => _x( 'New Topic Moderators', 'Email Detection Type', 'd4plib' ),
			'gd-bbpress-toolbox-new-reply-moderators'                => _x( 'New Reply Moderators', 'Email Detection Type', 'd4plib' ),
			'wpmembers-custom'                                       => _x( 'Custom', 'Email Detection Type', 'd4plib' ),
			'wpmembers-newreg'                                       => _x( 'New User Registration', 'Email Detection Type', 'd4plib' ),
			'wpmembers-newmod'                                       => _x( 'New User Registration Moderated', 'Email Detection Type', 'd4plib' ),
			'wpmembers-appmod'                                       => _x( 'Registration Approved', 'Email Detection Type', 'd4plib' ),
			'wpmembers-repass'                                       => _x( 'Password Reset', 'Email Detection Type', 'd4plib' ),
			'wpmembers-getuser'                                      => _x( 'Retrieve Username', 'Email Detection Type', 'd4plib' ),
			'wpmembers-admin-notify'                                 => _x( 'Admin Notification for User Registration', 'Email Detection Type', 'd4plib' ),
			'asgaros-subscriber-new-topic'                           => _x( 'New Topic Email', 'Email Detection Type', 'd4plib' ),
			'asgaros-subscriber-new-post'                            => _x( 'New Post Email', 'Email Detection Type', 'd4plib' ),
			'rank-math-auto-update-email'                            => _x( 'Auto Update Email', 'Email Detection Type', 'd4plib' ),
			'cf7-email'                                              => _x( 'Contact Email', 'Email Detection Type', 'd4plib' ),
			'buddypress-core-user-registration-with-blog'            => _x( 'Core User Registration With Blog', 'Email Detection Type', 'd4plib' ),
			'buddypress-core-user-registration'                      => _x( 'Core User Registration', 'Email Detection Type', 'd4plib' ),
			'buddypress-core-user-activation'                        => _x( 'Core User Activation', 'Email Detection Type', 'd4plib' ),
			'buddypress-bp-members-invitation'                       => _x( 'Core User Activation', 'Email Detection Type', 'd4plib' ),
			'buddypress-members-membership-request'                  => _x( 'Members Membership Request', 'Email Detection Type', 'd4plib' ),
			'buddypress-members-membership-request-rejected'         => _x( 'Members Membership Request Rejected', 'Email Detection Type', 'd4plib' ),
			'buddypress-friends-request'                             => _x( 'Friends Request', 'Email Detection Type', 'd4plib' ),
			'buddypress-friends-request-accepted'                    => _x( 'Friends Request Accepted', 'Email Detection Type', 'd4plib' ),
			'buddypress-activity-comment'                            => _x( 'Activity Comment', 'Email Detection Type', 'd4plib' ),
			'buddypress-activity-comment-author'                     => _x( 'Activity Comment Author', 'Email Detection Type', 'd4plib' ),
			'buddypress-messages-unread'                             => _x( 'Messages Unread', 'Email Detection Type', 'd4plib' ),
			'buddypress-settings-verify-email-change'                => _x( 'Settings Verify Email Change', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-details-updated'                      => _x( 'Groups Details Updated', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-membership-request'                   => _x( 'Groups Membership Request', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-membership-request-accepted'          => _x( 'Groups Membership Request Accepted', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-membership-request-rejected'          => _x( 'Groups Membership Request Rejected', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-membership-request-accepted-by-admin' => _x( 'Groups Membership Request Accepted by Admin', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-membership-request-rejected-by-admin' => _x( 'Groups Membership Request Rejected by Admin', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-member-promoted'                      => _x( 'Groups Member Promoted', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-invitation'                           => _x( 'Groups Invitation', 'Email Detection Type', 'd4plib' ),
			'buddypress-groups-at-message'                           => _x( 'Groups At Message', 'Email Detection Type', 'd4plib' ),
			'buddypress-activity-at-message'                         => _x( 'Activity At Message', 'Email Detection Type', 'd4plib' ),
			'woocommerce-low-stock'                                  => _x( 'Low Stock', 'Email Detection Type', 'd4plib' ),
			'woocommerce-no-stock'                                   => _x( 'No Stock', 'Email Detection Type', 'd4plib' ),
			'woocommerce-low-backorder'                              => _x( 'Backorder', 'Email Detection Type', 'd4plib' ),
		);
	}
}
