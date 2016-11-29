//var base_url = 'http://www.cultureleatlasbrabant.nl/monitor';
//var base_url = 'http://localhost/Cab_Monitor/site';
var base_url = wordpress_vars['url'];

jQuery(document).ready(function($){


//if ($( ".tabs" ).length != 0; ) {

	if ($( ".tabs" ).length != 0) {
	 $( ".tabs" ).tabs();
	}
//}

 $(".button-period-options").on("click", period_data_options);


 $(".js-export-period").on("click", export_period);


});




function period_data_options() {

	var object = jQuery(this);

	if (object.data('method') == 'unlock_form') { unlock_form(object.data('organisation_id'), object.data('period_id'), object); }
	if (object.data('method') == 'lock_form') { lock_form(object.data('organisation_id'), object.data('period_id'), object); }

	if (object.data('method') == 'remove_autosave') { remove_autosave(object.data('organisation_id'), object.data('period_id'), object); }

	//if (jQuery(this).data('data-method') == 'delete_autosave') { }

}

function unlock_form(organisation_id, period_id, element) {
	element.addClass("button-loading");
	var jqxhr = jQuery.post(base_url+"/api/?action=unlock_form&period_id="+period_id+"&organisation_id="+organisation_id, function(data, textstatus) {
		element.hide();
 	})
 	.done(function() {  })
 	.fail(function(e) { element.removeClass("button-loading"); })

}

function lock_form(organisation_id, period_id, element) {
	element.addClass("button-loading");
	var jqxhr = jQuery.post(base_url+"/api/?action=lock_form&period_id="+period_id+"&organisation_id="+organisation_id, function(data, textstatus) {
		element.hide();
 	})
 	.done(function() {  })
 	.fail(function(e) { element.removeClass("button-loading"); })

}

function remove_autosave(organisation_id, period_id, element) {
	element.addClass("button-loading");
	var jqxhr = jQuery.post(base_url+"/api/?action=remove_autosave&period_id="+period_id+"&organisation_id="+organisation_id, function(data, textstatus) {
		element.hide();
 	})
 	.done(function() {  })
 	.fail(function(e) { element.removeClass("button-loading"); })

}


/* Export */
function export_period() {

	var periodId = jQuery(".js-period-selector").val();
	window.location = base_url+'/api/?action=export_all_organisations_csv&period='+periodId;
	
}
