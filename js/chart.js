/* global google, UVL */

(function ( google )
{
	'use strict';

	/**
	 * Callback for drawing chart
	 *
	 * @return void
	 */
	function drawChart()
	{
		var data = new google.visualization.DataTable(),
			chart = new google.visualization.LineChart( document.getElementById( 'uvl-overview-chart' ) ),
			options = {
				legend: 'none'
			};

		data.addColumn( 'string', UVL.dateTitle );
		data.addColumn( 'number', UVL.visitsTitle );

		UVL.overview.forEach( function ( element, index )
		{
			UVL.overview[index][0] = formatDate( UVL.overview[index][0] );
			UVL.overview[index][1] = parseInt( element[1] );
		} );
		data.addRows( UVL.overview );

		chart.draw( data, options );
	}

	/**
	 * Format a date based on user locale
	 * @param string
	 * @returns string
	 */
	function formatDate( string )
	{
		var date = new Date( string );
		return date.toLocaleString( UVL.locale, { month: 'short', day: 'numeric' } );
	}

	google.load( 'visualization', '1', { 'packages': ['corechart'] } );
	google.setOnLoadCallback( drawChart );
})( google );
