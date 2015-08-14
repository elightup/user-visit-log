<?php
add_action( 'wp_dashboard_setup', 'uvl_dashboard_widgets' );

/**
 * Add widgets to dashboard
 * @return void
 */
function uvl_dashboard_widgets()
{
	wp_add_dashboard_widget(
		'uvl-overview',
		__( 'Visit Overview', 'user-visit-log' ),
		'uvl_dashboard_widgets_render'
	);
	wp_add_dashboard_widget(
		'uvl-today',
		__( 'Today Visits', 'user-visit-log' ),
		'uvl_dashboard_widgets_render'
	);
}

/**
 * Display dashboard widgets
 *
 * @param object $p    Not used
 * @param array  $args Widget arguments
 * @return void
 */
function uvl_dashboard_widgets_render( $p, $args )
{
	include UVL_DIR . 'inc/admin/widgets/' . str_replace( 'uvl-', '', $args['id'] ) . '.php';
}

add_action( 'admin_print_styles-index.php', 'uvl_dashboard_enqueue' );

/**
 * Enqueue scripts and styles for dashboard widgets
 * @return void
 */
function uvl_dashboard_enqueue()
{
	wp_enqueue_script( 'google', 'https://www.google.com/jsapi', array(), '', true );
	wp_enqueue_script( 'uvl-chart', UVL_URL . 'js/chart.js', array( 'google', 'jquery' ), '', true );


	// Number of days in the chart
	$days = 30;

	// Get data for chart
	global $wpdb;
	$table = $wpdb->prefix . UVL_TABLE;
	$date  = strtotime( "-{$days} days" ) + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
	$date  = date( 'Y-m-d', $date );

	$data = $wpdb->get_results( $wpdb->prepare(
		"SELECT date, SUM(count) FROM {$table} WHERE date >= '%s' GROUP BY date ORDER BY date ASC",
		$date
	), ARRAY_N );

	wp_localize_script( 'uvl-chart', 'UVL', array(
		'overview'    => $data,
		'locale'      => str_replace( '_', '-', get_locale() ),
		'dateTitle'   => __( 'Date', 'user-visit-log' ),
		'visitsTitle' => __( 'Visits', 'user-visit-log' ),
	) );
}
