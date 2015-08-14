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
		<th class="column-id">#</th>
		<th class="column-username"><?php _e( 'Name', 'user-visit-log' ); ?></th>
		<th class="column-name"><?php _e( 'Username', 'user-visit-log' ); ?></th>
		<th class="column-visits"><?php _e( 'Visits', 'user-visit-log' ); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach ( $data as $k => $row )
	{
		$user = get_userdata( $row->user_id );
		printf(
			'<tr>
				<td class="column-id">%d</td>
				<td class="column-name">%s</td>
				<td class="column-username">%s</td>
				<td class="column-visits">%d</td>
			</tr>',
			$k + 1,
			$user->display_name,
			$user->user_login,
			$row->count
		);
	}
	?>
	</tbody>
</table>
