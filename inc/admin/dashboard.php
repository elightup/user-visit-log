<?php
add_action( 'wp_dashboard_setup', 'uvl_dashboard_widgets' );

/**
 * Add widgets to dashboard
 * @return void
 */
function uvl_dashboard_widgets()
{
	wp_add_dashboard_widget(
		'uvl-today',
		__( 'Today Visits', 'user-visit-log' ),
		'uvl_dashboard_widgets_render'
	);
//	wp_add_dashboard_widget(
//		'uvl-overview',
//		__( 'Visit Overview', 'user-visit-log' ),
//		'uvl_dashboard_widgets_render'
//	);
}

/**
 * Display dashboard widgets
 *
 * @param object $p    Not used
 * @param array  $args Widget arguments
 */
function uvl_dashboard_widgets_render( $p, $args )
{
	include UVL_DIR . 'inc/admin/widgets/' . str_replace( 'uvl-', '', $args['id'] ) . '.php';
}