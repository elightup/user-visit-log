/* global jQuery,google, UVL, ajaxurl */

(function ( $, google )
{
	'use strict';

	var chart;

	/**
	 * Update data when change user, from and to dates via ajax
	 * @return void
	 */
	function updateData()
	{
		$.get( ajaxurl, {
			action  : 'uvl_overview_get_data',
			_wpnonce: UVL.nonce,
			user    : $( '#uvl-widget-overview-user' ).val(),
			from    : $( '#uvl-widget-overview-from' ).val(),
			to      : $( '#uvl-widget-overview-to' ).val()
		}, function ( r )
		{
			if ( r.success )
			{
				chart.setDataTable( r.data );
				chart.draw();
			}
		} );
	}

	/**
	 * Draw chart
	 * @return void
	 */
	function drawChart()
	{
		chart = new google.visualization.ChartWrapper( {
			chartType  : 'LineChart',
			dataTable  : UVL.data,
			options    : { legend: 'none' },
			containerId: 'uvl-overview-chart'
		} );
		chart.draw();
	}

	// Load Google Chart Library
	google.load( 'visualization', '1' );
	google.setOnLoadCallback( drawChart );

	// Run when DOM ready
	$( function ()
	{
		$( '.uvl-date' ).datepicker( {
			dateFormat: 'yy-mm-dd'
		} );
		$( '#uvl-overview' ).on( 'change', ':input', updateData );
	} );
})( jQuery, google );
