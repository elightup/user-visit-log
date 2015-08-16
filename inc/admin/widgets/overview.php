<p>
	<label for="uvl-widget-overview-user"><?php _e( 'User', 'user-visit-log' ); ?></label>
	<?php
	wp_dropdown_users( array(
		'id'              => 'uvl-widget-overview-user',
		'show_option_all' => __( 'All users', 'user-visit-log' ),
	) );
	$from = strtotime( '-30 days' ) + get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
	$from = date( 'Y-m-d', $from );
	?>
	<label for="uvl-widget-overview-from"><?php _e( 'From', 'user-visit-log' ); ?></label>
	<input id="uvl-widget-overview-from" type="text" class="uvl-date" size="10" value="<?php echo esc_attr( $from ); ?>">
	<label for="uvl-widget-overview-to"><?php _e( 'To', 'user-visit-log' ); ?></label>
	<input id="uvl-widget-overview-to" type="text" class="uvl-date" size="10" value="<?php echo esc_attr( current_time( 'Y-m-d' ) ); ?>">
</p>
<div id="uvl-overview-chart"></div>
