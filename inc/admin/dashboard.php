<?php

class UVL_Dashboard
{
	/**
	 * Class constructor, add hooks
	 */
	public function __construct()
	{
		add_action( 'wp_dashboard_setup', array( $this, 'add_widgets' ) );
		add_action( 'admin_print_styles-index.php', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_uvl_overview_get_data', array( $this, 'ajax_get_data' ) );
	}

	/**
	 * Add widgets to dashboard
	 * @return void
	 */
	public function add_widgets()
	{
		wp_add_dashboard_widget(
			'uvl-overview',
			__( 'Visit Overview', 'user-visit-log' ),
			array( $this, 'render_widgets' )
		);
		wp_add_dashboard_widget(
			'uvl-today',
			__( 'Today Visits', 'user-visit-log' ),
			array( $this, 'render_widgets' )
		);
	}

	/**
	 * Display dashboard widgets
	 *
	 * @param object $p    Not used
	 * @param array  $args Widget arguments
	 *
	 * @return void
	 */
	public function render_widgets( $p, $args )
	{
		include UVL_DIR . 'inc/admin/widgets/' . str_replace( 'uvl-', '', $args['id'] ) . '.php';
	}

	/**
	 * Enqueue scripts and styles for dashboard widgets
	 * @return void
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_style( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', '', '1.11.4' );
		wp_enqueue_script( 'google', 'https://www.google.com/jsapi', array(), '', true );
		wp_enqueue_script( 'uvl-chart', UVL_URL . 'js/chart.js', array( 'google', 'jquery-ui-datepicker' ), '', true );

		wp_localize_script( 'uvl-chart', 'UVL', array(
			'data'        => $this->get_data(),
			'locale'      => str_replace( '_', '-', get_locale() ),
			'dateTitle'   => __( 'Date', 'user-visit-log' ),
			'visitsTitle' => __( 'Visits', 'user-visit-log' ),
			'nonce'       => wp_create_nonce( 'get-data' ),
		) );
	}

	/**
	 * Ajax callback to get data for overview widget
	 */
	public function ajax_get_data()
	{
		if ( ! check_ajax_referer( 'get-data', false, false ) )
		{
			wp_send_json_error();
		}

		wp_send_json_success( $this->get_data(
			intval( $_GET['user'] ),
			strip_tags( $_GET['from'] ),
			strip_tags( $_GET['to'] )
		) );
	}

	/**
	 * Get data for overview widget
	 *
	 * @param int    $user_id User ID to get data for. If missed, then get data for all users
	 * @param string $from    From date
	 * @param string $to      To date
	 *
	 * @return array
	 */
	public function get_data( $user_id = 0, $from = '', $to = '' )
	{
		if ( ! $from )
		{
			$from = strtotime( '-30 days' ) + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
			$from = date( 'Y-m-d', $from );
		}
		if ( ! $to )
		{
			$to = current_time( 'Y-m-d' );
		}

		$where = "date >= '%s' AND date <= '%s'";
		if ( $user_id )
		{
			$where .= " AND user_id = '%d'";
		}

		// Get data for chart
		global $wpdb;
		$table = $wpdb->prefix . UVL_TABLE;
		$data  = $wpdb->get_results( $wpdb->prepare(
			"SELECT date, SUM(count) as visits FROM $table WHERE $where GROUP BY date ORDER BY date ASC",
			$from,
			$to,
			$user_id
		) );

		$data_table = array(
			'cols' => array(
				array(
					'id'    => 'date',
					'label' => __( 'Date', 'user-visit-log' ),
					'type'  => 'string',
				),
				array(
					'id'    => 'visits',
					'label' => __( 'Visits', 'user-visit-log' ),
					'type'  => 'number',
				),
			),
			'rows' => array(),
		);
		foreach ( $data as $row )
		{
			$data_table['rows'][] = array(
				'c' => array(
					array( 'v' => date( get_option( 'date_format' ), strtotime( $row->date ) ) ),
					array( 'v' => intval( $row->visits ) ),
				),
			);
		}
		return $data_table;
	}
}


