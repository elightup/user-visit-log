<?php
global $wpdb;
$table = $wpdb->prefix . UVL_TABLE;
$today = current_time( 'Y-m-d' );
$data  = $wpdb->get_results( $wpdb->prepare(
	"SELECT user_id, count FROM {$table} WHERE date = '%s' ORDER BY count DESC",
	$today
) );
if ( empty( $data ) )
{
	_e( 'No data', 'user-visit-log' );
	return;
}
?>
<table class="widefat">
	<thead>
	<tr>
		<th>#</th>
		<th><?php _e( 'Name', 'user-visit-log' ); ?></th>
		<th><?php _e( 'Visits', 'user-visit-log' ); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ( $data as $k => $row )
	{
		$user = get_userdata( $row->user_id );
		printf(
			'<tr>
				<th class="column-id">%d</th>
				<th class="column-user">%s</th>
				<th class="column-visits">%d</th>
			</tr>',
			$k + 1,
			$user->display_name . ' (' . $user->user_login . ')',
			$row->count
		);
	}
	?>
	</tbody>
</table>
