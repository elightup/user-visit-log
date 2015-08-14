<?php
add_action( 'plugins_loaded', 'uvl_create_table' );

/**
 * Create plugin's custom table
 * @return void
 */
function uvl_create_table()
{
	$option = get_option( UVL_OPTION, array( 'version' => 0 ) );

	// Check if we need to update table
	if ( version_compare( UVL_DB_VERSION, $option['version'], '<=' ) )
	{
		return;
	}

	// Create plugin's custom table
	global $wpdb;
	$table = $wpdb->prefix . UVL_TABLE;
	$sql   = "CREATE TABLE {$table} (
		id int(12) NOT NULL AUTO_INCREMENT,
		user_id int(8) NOT NULL,
		date datetime DEFAULT '0000-00-00' NOT NULL,
		count int(8) NOT NULL,
		PRIMARY KEY  (id)
	);";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	// Update database version
	$option['version'] = UVL_DB_VERSION;
	update_option( UVL_OPTION, $option );
}
