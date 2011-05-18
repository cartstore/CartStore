window.onload = function() {
	bindCalendarToPeriodControls();
}

$(document).ready(function() {
	
 $('body').click(function(event) {
  	var click_target = $(event.target);
							
    if ((click_target.parents('#cse_filter_list').length == 0) && $(click_target).get(0).id != 'cse_list_button' && $(click_target).get(0).id != 'cse_filter_list' ) {
     	$('#cse_filter_list').slideUp('normal');
    }
  } );

	$("#cse_list_button").click(function() {
		cse_filter_list();
	});
	
	assign_sortable();

});

function assign_sortable() {
	var myLinkTextExtraction = function(node) {
		return $(node).text();
	} 
	
	$.tablesorter.addWidget( {
		id: "habra",
		
		format: function(table) {
			$("tr:nth-child(odd)").removeClass().addClass("lightrow");
			$("tr:nth-child(even)").removeClass().addClass("darkrow");
		}
	} );
	
	$("#m1_dscs, #sales_performance, #m1_clicks_statistics").tablesorter( { 
    widgets: ['habra'],
		textExtraction: myLinkTextExtraction
	} );
}

function show_reports_view(reportsHTML)
{
	document.getElementById('content').innerHTML = reportsHTML;
	assign_sortable();
	bindCalendarToPeriodControls();
	
}

function loadReportsView(viewName, title)
{
	document.title = title + ' - Export Feeds';
	document.getElementById('titletext').innerHTML = title;
	document.getElementById('content').innerHTML = '<div class="loadingBlock"><img src="m1/export_all/sales_channel_analysis/images/loading.gif" align="absmiddle"/>&nbsp;&nbsp;Loading...</div>';
	eval("x_" + viewName + "(show_reports_view)");
	return false;
}

function loadClickStatisticsView(title, start_date, end_date, cse_id, product_id)
{
	document.title = title + ' - Export Feeds';
	document.getElementById('titletext').innerHTML = title;
	document.getElementById('content').innerHTML = '<div class="loadingBlock"><img src="m1/export_all/sales_channel_analysis/images/loading.gif" align="absmiddle"/>&nbsp;&nbsp;Loading...</div>';

	if (product_id) {
		x_getClicksStatisticView(start_date, end_date, cse_id, product_id, show_reports_view);
  } else {
		x_getClicksStatisticView(start_date, end_date, cse_id, show_reports_view);
	}
	
	return false;
}


function refreshViewByDate(viewName, startDate, endDate)
{
	var startDate = document.getElementById(startDate).value;
	var endDate = document.getElementById(endDate).value;
	document.getElementById('content').innerHTML = '<div class="loadingBlock"><img src="m1/export_all/sales_channel_analysis/images/loading.gif" align="absmiddle"/>&nbsp;&nbsp;Loading...</div>';
	eval("x_" + viewName + "('" + escape(startDate) + "', '" + escape(endDate) + "',show_reports_view)");
	return false;
}

function bindCalendarToPeriodControls()
{
	if (document.getElementById("dateFrom_picker") && document.getElementById("dateTo_picker"))
	{
	  Calendar.setup(
	    {
    	  inputField     :    "dateFrom",
	      ifFormat       :    "%m/%d/%Y",
	      showsTime      :    true,
	      button         :    "dateFrom_picker",
	      step           :    1
	    }
	  );
	  Calendar.setup(
	    {
	      inputField     :    "dateTo",
	      ifFormat       :    "%m/%d/%Y",
	      showsTime      :    true,
	      button         :    "dateTo_picker",
	      step           :    1
	    }
	  );
	}
}

function set_date_interval_to(start_date, end_date) {
	document.getElementById('dateFrom').value = start_date;
	document.getElementById('dateTo').value = end_date;
}

function confirm_cse_delete(cse_name) {
	return confirm("Confirm - Are you sure you want to delete all statistics for "+cse_name+"?");
}

function cse_filter_list() {
	$("#cse_filter_list").slideToggle();
}

function filter_select_items(new_status) {
	$("input[@name^='cse_to_hide']").attr("checked", new_status);
}
