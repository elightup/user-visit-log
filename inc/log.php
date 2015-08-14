<?php
add_action( 'template_redirect', 'uvl_log' );

/**
 * Log user visit
 * @return void
 */
function uvl_log()
{
	// Don't log non-logged in users or users who already visited the website
	if ( ! is_user_logged_in() || isset( $_COOKIE['uvl_first_time'] ) )
	{
		return;
	}

	// Log visit
	global $wpdb;
	$table   = $wpdb->prefix . UVL_TABLE;
	$today   = current_time( 'Y-m-d' );
	$user_id = get_current_user_id();
	$visits  = $wpdb->get_var( $wpdb->prepare(
		"SELECT count FROM {$table} WHERE user_id='%d' AND date='%s'",
		$user_id,
		$today
	) );
	if ( ! $visits )
	{
		$visits = 1;
		$wpdb->insert( $table, array(
			'user_id' => $user_id,
			'date'    => $today,
			'count'   => $visits,
		) );
	}
	else
	{
		$visits ++;
		$wpdb->update( $table, array(
			'count' => $visits,
		), array(
			'user_id' => $user_id,
			'date'    => $today
		) );
	}

	setcookie( 'uvl_first_time', $visits, 0 );
}