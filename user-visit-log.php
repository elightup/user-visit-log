<?php
/*
Plugin Name: User Visit Log
Plugin URI: http://www.deluxeblogtips.com
Description: Log users visit by day
Version: 1.0.0
Author: Rilwis
Author URI: http://www.deluxeblogtips.com
License: GPL2+
*/

define( 'UVL_DIR', plugin_dir_path( __FILE__ ) );
define( 'UVL_URL', plugin_dir_url( __FILE__ ) );
define( 'UVL_OPTION', 'user_visits_log' );
define( 'UVL_TABLE', 'user_visits_log' );
define( 'UVL_DB_VERSION', '1.0.0' );

require UVL_DIR . 'inc/admin/database.php';
if ( is_admin() )
{
	require UVL_DIR . 'inc/admin/dashboard.php';
}
else
{
	require UVL_DIR . 'inc/log.php';
}
