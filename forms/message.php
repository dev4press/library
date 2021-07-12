<?php

use function Dev4Press\v36\Functions\panel;
use function Dev4Press\v36\Functions\sanitize_slug;

$_message = '';
$_color   = 'success';

if ( isset( $_GET['message'] ) && $_GET['message'] != '' ) {
	$msg_code = sanitize_slug( $_GET['message'] );

	switch ( $msg_code ) {
		case 'saved':
			$_message = __( "Settings are saved.", "d4plib" );
			break;
		case 'imported':
			$_message = __( "Import operation completed.", "d4plib" );
			break;
		case 'removed':
			$_message = __( "Removal operation completed.", "d4plib" );
			break;
		case 'nothing':
			$_color   = 'error';
			$_message = __( "Nothing done.", "d4plib" );
			break;
		case 'nothing-removed':
			$_color   = 'error';
			$_message = __( "Nothing removed.", "d4plib" );
			break;
		case 'invalid':
			$_message = __( "Requested operation is invalid.", "d4plib" );
			$_color   = 'error';
			break;
		case 'import-failed':
			$_message = __( "Import operation failed.", "d4plib" );
			$_color   = 'error';
			break;
	}

	$msg = panel()->a()->message_process( $msg_code, array( 'message' => $_message, 'color' => $_color ) );
	$msg = apply_filters( panel()->a()->h( 'admin_panel_message' ), $msg );

	$_message = $msg['message'];
	$_color   = $msg['color'];
}

if ( $_message != '' ) {
	echo '<div class="d4p-message"><div class="notice notice-' . $_color . ' is-dismissible">' . $_message . '</div></div>';
}
