/**
* Theme Customizer enhancements for a better user experience.
*
* Contains handlers to make Theme Customizer preview reload changes asynchronously.
* Things like site title, description, and background color changes.
*/


//var base_url = 'http://www.cultureleatlasbrabant.nl/monitor';
var base_url = wordpress_vars['wpurl'];
var cab_api = base_url+"/api/?action=get_periods";

var auto_save_data_object = new Array();

var prev_spy_object;



//
function restore_auto_save_data(organisation_id, period_id) {

	var jqxhr = jQuery.post(base_url+"/api/?action=get_auto_save&period_id="+period_id+"&organisation_id="+organisation_id, function(data, textstatus) {

		jQuery.each(JSON.parse(data), function(i, field){

			var name = field.name;
			var id = field.id;

			// Escape because if there are fields with a "." in the name jquery fails
			if (name) { var escaped_name = name.replace(".","\\."); }
			if (id) { var escaped_id = id.replace(".","\\."); }


			if (field.type == "checkbox") {
				jQuery(":input[name="+escaped_name+"]").attr('checked', field.checked);
			} else {
				jQuery(":input[name="+escaped_name+"]").val(field.value);
			}
		});		

	})


}


function show_auto_save_confirmation() {
	jQuery("#auto_save_confirmation").css("left",450);
	jQuery("#auto_save_confirmation").css("top",100);
	jQuery('#auto_save_confirmation').fadeIn(100);
}


function hide_auto_save_confirmation() {
	jQuery('#auto_save_confirmation').fadeOut(100);
}

function auto_save_data(organisation_id, period_id) {
	auto_save_data_object = new Array();


	jQuery(":input").each(function( index ) {

		var name = jQuery(this).attr("name");
		var id = jQuery(this).attr("id");

		// Escape because if there are fields with a "." in the name jquery fails
		if (name) { var escaped_name = name.replace(".","\\."); }
		if (id) { var escaped_id = id.replace(".","\\."); }
		// If checkbox
		if (jQuery(this).attr("type") == 'checkbox') {
			auto_save_data_object.push({"name":escaped_name,"id":escaped_id,"value":jQuery(this).val(),"type":jQuery(this).attr("type"),"checked":jQuery(this).is(':checked')});
		} else if (jQuery(this).attr("type") == 'radio') {
			if (jQuery(this).is(':checked')) {
			auto_save_data_object.push({"name":escaped_name,"id":escaped_id,"value":jQuery(this).val(),"type":jQuery(this).attr("type")});
			}
		} else {
			auto_save_data_object.push({"name":escaped_name,"id":escaped_id,"value":jQuery(this).val(),"type":jQuery(this).attr("type")});
		}

	});

	//auto_save_data_object = new Array({"naam":"klaas"},{"kees":"jan"});
	jQuery("#menu_save").addClass("menu-button-loading");
	jQuery("#menu_save").removeClass("menu-button-save");
	
	var jqxhr = jQuery.post(base_url+"/api/?action=set_auto_save", {"auto_save":{"organisation_id":organisation_id, "period_id":period_id, "data":JSON.stringify(auto_save_data_object)}}, function(data, textstatus) {
	})
	.fail(function(e) { console.log(e); })
	.always(function() { 
		show_auto_save_confirmation();
		jQuery("#menu_save").removeClass("menu-button-loading");
		jQuery("#menu_save").addClass("menu-button-save");  
	});

}




//document.querySelector(".gform_body")


// on document ready

jQuery(document).ready(function($){


	// prevent the system from sending the form after a return key down
	jQuery(document).on("keypress", ":input:not(textarea):not([type=submit])", function(event) {
		if(event.keyCode == 13) {
		  event.preventDefault();
		  return false;
		}
	    // ...
	});




	var formBody = document.querySelector('.gform_body');
	formBody.style.visibility = 'hidden';




	/* Validation */
	// validator
	// 




	// add method which checks if a checkboxgroup has at least one item selectect
	jQuery.validator.addMethod("checkboxgroupchecked", function(value, element) {

		var checkbox_group = $("#" + element.id).parents(".checkboxgroup");

		// Get all checkboxes in the group
		var checked_checkboxes = checkbox_group.find("input:checked");

		if (checked_checkboxes.length > 0) {
			return true;
		} else {
			return false;
		}

	}, "U dient minimaal 1 selectie vak te selecteren");


	jQuery.validator.addMethod("rounded_number", function(value, element) {

		if (element) {
			jQuery(element).removeClass('show_error');

		}

		if (value !== '') {

			// if ( !/^\d+$/.test(value) ) {
			if ( !/^-?(?:\d+|\d{1,3}(?:\.\d{3})+)?$/.test(value) ) {

				jQuery(element).addClass('show_error');

				return false;
			} else {
				return true;
			}

		} else {
			return true;
		}

	}, "Enkel hele cijfers zijn toegestaan.");


	jQuery.validator.addMethod("number", function(value, element) {

		if (element) {
			jQuery(element).removeClass('show_error');
		}

		if (value !== '') {

			if ( !/^-?(?:\d+|\d{1,3}(?:\.\d{3})+)?(?:,\d+)?$/.test(value) ) {

				jQuery(element).addClass('show_error');

				return false;
			} else {
				return true;
			}

		} else {
			return true;
		}

	}, "Dit is geen geldig cijfer, gebruik een punt voor duizendtallen en een komma voor decimalen.");


	// check if the url has a http:// 
	jQuery.validator.addMethod("urlnohttp", function(value, element) {
		// now check if valid url
		return /^(www\.)[A-Za-z0-9_-]+\.+[A-Za-z0-9.\/%&=\?_:;-]+$/.test(value);
	}, "Please enter a valid URL.");

	// check if the url has a http:// 
	// jQuery.validator.addMethod("lala", function(value, element) {
	// 	console.log("koekoek lala");
	// 	return false;
	// }, "Please enter a valid URL.");

	// check if it is a valid url
	jQuery.validator.addMethod("url", function(value, element) {

		if (value !== '') {
			return /^(https?:\/\/)((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|((\d{1,3}\.){3}\d{1,3}))(\:\d+)?(\/[-a-z\d%_.~+]*)*(\?[;&a-z\d%_.~+=-]*)?(\#[-a-z\d_]*)?$/.test(value);
		} else {
			return true;
		}

	}, "Please enter a valid URL.");

	jQuery.validator.addMethod("ignore", function(value, element) {

		return true;
	}, "Please enter a valid URL.");

	// perform the validation
	$('#gform_1').validate({ 
		messages: {
			input_72: "U dient een keuze te maken"
		},
		ignore: ".ignore, :hidden",
		invalidHandler: function(event, validator) {
			show_error_box();
		},
		errorElement: "div",
		errorPlacement: function(error,element) {
			// Check if it is a checkboxgroup
			var checkbox_group = $("#" + element[0].id).parents(".checkboxgroup");
			var radio_group = $("#" + element[0].id).parents(".gfield_radio");

			if (checkbox_group.length > 0 || element.hasClass("checkconfirmation") ) {
				//checkbox_group.find('.gfield_label').addClass("group-error");
				error.insertBefore(element)
			} else if (radio_group.length > 0) {
				error.insertBefore(radio_group);
			} else if (element.hasClass("email") || element.hasClass("url") ) {
				error.insertAfter(element);
		
			} else if (element.hasClass("show_error") ) {
								error.insertAfter(element);

			} else {
				return true;
			
			}

		},
		onfocusout: function(element,event) { 
			$(element).valid(); 
		} 
	}); 



	// this piece enables the form to validate even required fields if the checkbox is checked of an ignorable field
	jQuery.validator.addClassRules('ignore', {
	        required: false /*,
	        other rules */
	    });

	$.validator.messages.required = 'Dit veld is verplicht';
	$.validator.messages.email = 'Dit is geen geldig email adres';
	$.validator.messages.url = 'Dit is geen geldige url';

	function validate_group(id) {
		var group_valid = true;

		$(id).next('.row-fluid').find(":input.required").each(function(){
			if ($(this).valid() == false) {
				group_valid = false;
			}
		});

		return group_valid;
	}



	function show_error_box() {
		$('.error-box').show();
	}

	function hide_error_box() {
		$('.error-box').hide();
	}

	$('#gform_1').on('submit', function() {

		// if the form is valid let gravity form set the "gf_submitting" value
		// so someone cannot resend while already sending
		if (!$("#gform_1").valid()) {
			window["gf_submitting_1"] = false;	
		} else {
			// add loading icon
			$("#gform_submit_button_1").after('<div class="form_submitting"></div>');
		}

		return true;
		//   var isvalidate=$("#gform_1").valid();
		//      if(isvalidate)
		//      {
		//          e.preventDefault();
		//          alert(getvalues("#gform_1"));
		//      }
		// return false;
	});








	/* Periods */
	//
	var periods;

	$.getJSON( cab_api ).done(function( data ) {
		periods = data.periods;
		check_period_tags(periods);
	});


	function replace_period_tags( content, period ) {
		var replaced = content;

		if (content.indexOf("{period}") !== -1) {
			replaced = replaced.replace('{period}', periods[period]['label']);
		}

		return replaced;
	}

	// find the period tags in the tags
	function check_period_tags(periods) {

		// get current period
		var current_period = getUrlVars()["period_id"];

		var n=current_period.indexOf("#");
		if (n != -1) {
			current_period = current_period.substr(0,n)
		} 
		// replace periods in titles
		jQuery( ".gsection_title" ).each(function( index ) {
			//var current_title = jQuery(this).text();

			jQuery(this).text( replace_period_tags( jQuery(this).text(), current_period ) );
			// //&& (this).parent('.gsection').css('display') != 'none'
			// if (current_title.indexOf("{period}") !== -1) {
			// 	current_title = current_title.replace('{period}', periods[current_period]['label']);
			// 	jQuery(this).text(current_title);
			// }
		});

		jQuery( ".gfield_html").each(function(index) {

			jQuery(this).html( replace_period_tags( jQuery(this).html(), current_period ) );

		});


		var has_list = false;

		//Pak alle H2's en maak lijst aan linker zijde met click-box en link.
		$('.fieldgroup').not('.section-invisible').each(function() {

			if ($(this).css("display") != 'none') {
				has_list = true;
				var id_fieldgroup = $(this).attr("id");
				var group_tilte = $(this).find('h2').text();

				var html = '<li id="trigger-'+id_fieldgroup+'"><a href="#'+id_fieldgroup+'"><div class="click-box"></div>'+group_tilte+'</a></li>';

				$("#menu").append(html);
			}
		});

		//Scrollspy Bootstrap (Add active in #menu)
		if (has_list) { 
			$('#menu').prepend('<span class="title">Vragenlijst onderdelen:</span>'); 
		}
		$('#menu li').wrapAll("<ul class='nav'></ul>").on('activate', function(e) {

			var current_spy_object = $(this).find('a');
			// Validate the last viewed group
			if (prev_spy_object) {
				var group_validation = validate_group(prev_spy_object.attr("href"));
				if (group_validation == false) {
					prev_spy_object.find('.click-box').addClass('error');
					prev_spy_object.find('.click-box').removeClass('checked');
				} else if (group_validation == true) {
					prev_spy_object.find('.click-box').addClass('checked');
					prev_spy_object.find('.click-box').removeClass('error');
				}
			}
			prev_spy_object = current_spy_object;

		});


		$('body').scrollspy({ target: '#menu' });

	}


	setTimeout( function() {



		$('#empty-fields').click(function(event){
			$("input").val("");
		});


		$('#restore-fields').click(function(event){

			jQuery.each(auto_save_data_object, function(i, field){
				if (field.type == "checkbox") {
					$(":input[name="+field.name+"]").attr('checked', field.checked);
				} else {
					$(":input[name="+field.name+"]").val(field.value);
				}
			});

		});








		// find and replace {nr}
		var nr_counter = 1;
		$( ".gsection" ).each(function( index ) {

			// get all section heads
			$.each($(this).children(".gsection_title"), function(index, value) {
				var current_label = $(this).text();
				//&& (this).parent('.gsection').css('display') != 'none'
				if (current_label.indexOf("{nr}") !== -1) {
					if ($(this).parent('.gsection').css('display') != 'none') {
						current_label = current_label.replace('{nr}', nr_counter+".");
						$(this).text(current_label);
						nr_counter++;
					}

				}

			});
		});

		// add required class to the inputs to enable jquery validation
		$( ".gfield_contains_required:not(.checkbox_group)" ).each(function( index ) {
			// get all section heads
			$(this).find(":input").addClass("required");
		});


		// Find all checkbox groups and add class for custom validation
		$( ".checkbox_group").each(function(index) {
			$(this).find("input:checkbox:first").addClass("checkboxgroupchecked");
		});





		// description part
		$('.gfield_description').before('<span class="description_rollover">?</span>');
		$('.gfield_description').css('display','none');
		$('.description_rollover').mouseover(function() {
			var description_text = $(this).next('.gfield_description').html();

			var mainContent = $('.maincontent').offset();
			var position = {top:$(this).offset().top-mainContent.top ,left:$(this).offset().left-mainContent.left};

			//var position = $(this).position();
			show_description(description_text,position);
		});




		$('.description_rollover').mouseout(function() {
			hide_description();
		});

		function hide_description() {
			$("#absolute_help").fadeOut(100);
		}


		function show_description(html, position) {
			$("#absolute_help").html(html);
			$("#absolute_help").fadeIn(200);
			$("#absolute_help").css("left",position.left+30);
			$("#absolute_help").css("top",position.top+5);
		} 

		// when a textfield is selected show the description
		$('.gfield input').focus(function() {

			var description_item = $(this).parents('.gfield').find('.gfield_description');
			var rollover_item = $(this).parents('.gfield').find('.description_rollover');
			// Check if there is a description item
			if (description_item[0]) {
				var description_text = description_item.html();
				var mainContent = $('.maincontent').offset();
				var position = {top:rollover_item.offset().top-mainContent.top ,left:rollover_item.offset().left-mainContent.left};

				show_description(description_text, position);
			}

		});

		// when textfield deselected remove description
		$('.gfield input').focusout(function() {
			hide_description();
		});


		// when value selected in dropdown disable field
		$('.social-media-select select').change(function() {

			var value = $(this).val();

			var field = $(this).parent().parent().parent().parent().prev().find('input');

			if (value === '-2' || value === '-3') {
				field.val('');
				field.change(); // to allow gravity forms to calculate totals
				field.addClass('ignore');
				field.prop('disabled', true);
				field.prop('placeholder', "Niet van toepassing");

			} else {
				field.removeClass('ignore');
				field.prop('disabled', false);
				field.removeClass('error');
				field.removeClass('valid');
				field.prop('placeholder', "");


			}
		});






		// Maak .cab-sidebar even lang als scherm hoogte.
		$(window).resize(function() {
		// var bestandsHoogte = $(document).height();
		// $(".cab-sidebar").css("height", bestandsHoogte);
		});


		// Prepare fieldgroups in Spans.
		$('.fieldgroup').not('.two-column-input, .four-column-input').each(function() {
			//console.log($(this).children().html());	
			if ($(this).css("display") != 'none') {

				//get items in fieldgroup untill next fieldgroup
				var fieldgroup_fields = $(this).nextUntil('.fieldgroup');

				$(fieldgroup_fields).find('.gsection').hide();

				// Prepare fields
				$(fieldgroup_fields).filter(".gfield").not(".gsection, .checkboxgroup, .one-column").each(function(index){

					if ($(this).hasClass("currency")) {

						// Wrap the label
						$(this).find(".gfield_label").wrapAll('<div class="span4"></div>').wrapAll('<div class="span12"></div>').wrapAll('<div class="span10 self_label"></div>').parent().parent().append("<span class='span1 offset1'><span class='currency'>€</a></span>");

						//$//(this).find(".gfield_label").append("<span class='currency'>€</span>");
					} else {
						$(this).find(".gfield_label").wrapAll('<div class="span4 self_label"></div>');

					}

					// Wrap the container
					$(this).find(".ginput_container").wrapAll('<div class="span8 self_container"></div>');

					// Wrap description ?
					$(this).find(".description_rollover, .gfield_description").wrapAll('<div class="description_indicator"></div>');

					}
				);
			} 
		});


		$('.one-column').each(function() {


			$(this).find(".gfield_label").remove();

			$(this).find(".ginput_container").wrapAll('<div class="span12 self_container"></div>');

			// 		// Wrap description ?
			$(this).find(".description_rollover, .gfield_description").wrapAll('<div class="description_indicator"></div>');

			// if ($(this).css("display") != 'none') {
			// 	//console.log($(this));
			// 	//grab description
			// 	//$(this).find(".gsection_description").wrapAll('<div class-"span5"></div>').appendTo($(this).next('.row-fluid'));

			// 	//get items in fieldgroup untill next fieldgroup
			// 	var fieldgroup_fields = $(this).nextUntil('.fieldgroup');

			// 	$(fieldgroup_fields).find('.gsection').hide();

			// 	// Prepare fields
			// 	$(fieldgroup_fields).filter(".gfield").not(".gsection, .checkboxgroup").each(function(index){

			// 		// Wrap the label
			// 		$(this).find(".gfield_label").remove();

			// 		// Wrap the container
			// 		$(this).find(".ginput_container").wrapAll('<div class="span7 self_container"></div>');

			// 		// Wrap description ?
			// 		$(this).find(".description_rollover, .gfield_description").wrapAll('<div class="span1 self_container"></div>');

			// 	});
			// 	} 
		});


		// Two column fieldgroups
		$('.two-column-input').each(function() {
			//get items in fieldgroup untill next fieldgroup
			var fieldgroup_fields = $(this).nextUntil('.fieldgroup');


			// Prepare fields
			$(fieldgroup_fields).filter(".gfield").not(".form-two-colum, .one-column").each(function(index){

				var has_description = false;

				if (!$(this).hasClass("form-two-column")) {

					// Wrap the label
					if ($(this).hasClass("currency")) {
						$(this).find(".gfield_label").wrapAll('<div class="span4"></div>').wrapAll('<div class="span12"></div>').wrapAll('<div class="span10 self_label"></div>').parent().parent().append("<span class='span1 offset1'><span class='currency'>€</a></span>");
					} else {
						$(this).find(".gfield_label").wrapAll('<div class="span4 self_label"></div>');
					}

					$(this).find(".ginput_container").wrapAll('<div class="span4"></div>');

				} else {

					$(this).find(".gfield_label").remove();

					if ($(this).hasClass("currency")) {
						//$(this).prepend("<span class='span1 offset1'><span class='currency'>€</a></span>");
					} else {
						//$(this).prepend("<span class='span1 offset1'></span>");
					}

					$(this).find(".ginput_container").wrapAll('<div class="span12"></div>');
					$(this).find(".description_rollover, .gfield_description").wrapAll('<div class="description_indicator"></div>');
					$(this).wrapAll('<div class="span4"></div>');

				}

			});

			//$(this).nextUntil('.fieldgroup').wrapAll('<div class="row-fluid"></div>');


		});



		// Two column fieldgroups
		// $('.four-column-input').each(function() {

		

		// 	//get items in fieldgroup untill next fieldgroup
		// 	var fieldgroup_fields = $(this).nextUntil('.fieldgroup');

		// 	// Prepare fields
		// 	$(fieldgroup_fields).filter(".gfield").each(function(index){


		// 		if ($(this).hasClass("form-four-column_1")) {

		// 			$(this).find(".gfield_label").wrapAll('<div class="span4 self_label"></div>');
		// 			$(this).find(".ginput_container").wrapAll('<div class="span3"></div>');
		// 		}
			

		// });
		// 		$(this).nextUntil('.fieldgroup').wrapAll('<div class="row-fluid"></div>');

		

		// 	//console.log("four column input")
		// // Prepare fields
		// 	// $(fieldgroup_fields).filter(".gfield").not(".form-two-colum, .one-column, .two-column-input").each(function(index){

		// 	// 	var has_description = false;


		// 	// 	// if ($(this).hasClass("form-four-column_1")) {
		// 	// 	// 	$(this).find(".gfield_label").wrapAll('<div class="span4 self_label"></div>');

		// 	// 	// 	$(this).find(".ginput_container").wrapAll('<div class="span6"></div>');

		// 	// 	// } else {

		// 	// 	// 	$(this).find(".gfield_label").remove();
		// 	// 	// 	$(this).find(".ginput_container").wrapAll('<div class="span8"></div>');
		// 	// 	// 	$(this).wrapAll('<div class="span6"></div>');

		// 	// 	// }



		// 	// 	//console.log(index);

		// 	// });

		// 	//$(this).nextUntil('.fieldgroup').wrapAll('<div class="row-fluid"></div>');

		// });




		// Wrap all fieldgroups in spans en row-fluids
		$('.fieldgroup').each(function() {

			// id opvragen
			var id_fieldgroup = $(this).attr("id");

			if ($(this).css("display") != 'none') {

				$(this).nextUntil('.fieldgroup').wrapAll('<div class="span8"></div>');
				// Move description
				$(this).find('.gsection_description').insertAfter($(this).next()).wrapAll("<div class='span4'></div>");

				$(this).nextUntil('.fieldgroup').wrapAll('<div class="row-fluid"></div>');
			}
		});



		$('.ignorable').each(function() {

			var field = $(this).find('input').parent('.ginput_container');


			var wrapper = field.wrapAll("<div class='span11'></div>");

			 $(this).find('input').parent('.ginput_container').parent('.span11').after("<div class='span1 ignorable-checkbox-column'><input name='' class='js-ignorable-checkbox' type='checkbox' value='' id='' tabindex=''></div>");

			// id opvragen
			// var id_fieldgroup = $(this).attr("id");

			// if ($(this).css("display") != 'none') {

			// 	$(this).nextUntil('.fieldgroup').wrapAll('<div class="span8"></div>');
			// 	// Move description
			// 	$(this).find('.gsection_description').insertAfter($(this).next()).wrapAll("<div class='span4'></div>");

			// 	$(this).nextUntil('.fieldgroup').wrapAll('<div class="row-fluid"></div>');
			// }
		});


		//Gsection in row-fluid, span12
		$(".gsection").each(function(){
			$(this).find('h2').wrapAll("<div class='row-fluid'><div class='span12'></div></div>");
		});

		// Give email tag to all input of the section
		$(".gfield.email").each(function(){
			$(this).find('input').addClass("email");
		});

		// Give rounded_number tag to all input of the section
		$(".gfield.rounded_number").each(function(){
			$(this).find('input[type=text]').addClass("rounded_number");
		});

		// Give float_number tag to all input of the section
		$(".gfield.number").each(function(){
			$(this).find('input[type=text]').addClass("number");
		});


		// $(".gfield.lala").each(function(){
		// 	$(this).find('input').addClass("lala");
		// });

		$(".gfield.urlnohttp").each(function(){
			$(this).find('input').addClass("urlnohttp");
		});


		$(".gfield.url").each(function(){
			$(this).find('input').addClass("url");
		});


		// Find the confirmation checkbox
		$( ".check_confirmation").each(function(index) {
			$(this).find("input:checkbox").addClass("checkconfirmation");
		});


		$(".gfield.ignore").each(function(){
			var field = $(this).find('input');
			field.addClass("ignore");
			field.prop('disabled', true);

			$(this).find('input').addClass("ignore");

		});


		//Scroll animatie
		function goToByScroll(href){
			// Remove "link" from the ID 
			// Scroll
			$('html,body').animate({
				scrollTop: $(href).offset().top},
			'slow');
		}


		$(".js-ignorable-checkbox").change(function(){

			var field = $(this).parent().prev().find('input');
			var errorDiv = $(this).parent().prev().find('div.error');

			if (this.checked) {

				// remove the error message otherwise it shows up when the checkbox is unchecked
				errorDiv.remove();

				field.val('');

				// remove the class which enables showing error texts
				field.removeClass('show_error');

				field.change(); // to allow gravity forms to calculate totals
				field.addClass('ignore');
				field.prop('disabled', true);
				field.prop('placeholder', "Niet van toepassing");

				// validate the field, this also removes any error descriptions which are still showing
				field.valid();
			} else {
				field.removeClass('ignore');
				field.prop('disabled', false);
				field.removeClass('error');
				field.removeClass('valid');
				field.prop('placeholder', "");
								field.change(); // to allow gravity forms to calculate totals

								field.valid();


			}

		});


		$(".js-ignorable-checkbox").mouseover(function() {
			var description_text = 'Gebruik het afvinkveld als de gegevens niet bekend zijn of de vraag niet van toepassing is op uw organisatie.';

			var mainContent = $('.maincontent').offset();
			var position = {top:$(this).offset().top-mainContent.top ,left:$(this).offset().left-mainContent.left};

			//var position = $(this).position();
			show_description(description_text,position);
		});

		$(".js-ignorable-checkbox").mouseout(function() {
			hide_description();
		});

		// $(".js-ignorable-checkbox").click(function(e) {

		// 	console.log($(this).parent().prev().find('input'));


		
		// });

		$("#menu a").click(function(e) { 
			// Prevent default
			//e.preventDefault(); 

			// var group_id = $(this).attr("href");
			// //group_id.substring(1);
			// var group_validation = validate_group(group_id);
			// console.log(group_validation);
			// if (group_validation == false) {
			// 	$(this).find('.click-box').addClass('error');
			// 	$(this).find('.click-box').removeClass('checked');

			// } else if (group_validation == true) {
			// 	$(this).find('.click-box').addClass('checked');
			// 	$(this).find('.click-box').removeClass('error');


			// }

			// Call the scroll function
			//goToByScroll($(this).attr("href"));           
		});

		$(":input.required").focusout(function() {

		// var fieldgroup = $(this).parents('.row-fluid').prev('.fieldgroup');
		// var list_item = '';

		// console.log(fieldgroup.attr("id"));
		});






		// show the form
		formBody.style.visibility = 'visible';


	}, 0);

}); //Einde JS





//var auto_save_data = {

// $('#save-fields').click(function(event){

// 	 $(":input").each(function( index ) {

// 	 	var name = $(this).attr("name");
// 	 	var id = $(this).attr("id");

// 	 	// Escape because if there are fields with a "." in the name jquery fails
// 	 	if (name) { var escaped_name = name.replace(".","\\."); }
// 	 	if (id) { var escaped_id = id.replace(".","\\."); }

// 	 	// If checkbox
// 	 	if ($(this).attr("type") == 'checkbox') {
//    			auto_save_data.push({"name":escaped_name,"id":escaped_id,"value":$(this).val(),"type":$(this).attr("type"),"checked":$(this).is(':checked')});
// 	 	} else {
//    			auto_save_data.push({"name":escaped_name,"id":escaped_id,"value":$(this).val(),"type":$(this).attr("type")});
// 	 	}
// 	 });




// 	var jqxhr = $.post("http://localhost:8888/cab/monitor/api/?action=set_auto_save", {"organisation_id":3, "period_id":5, "data":JSON.stringify(auto_save_data)}, function(data, textstatus) {
// 		console.log(data);
// 	})
// 	.done(function() { alert("second success"); })
// 	.fail(function(e) { console.log(e); })
// 	.always(function() { alert("finished"); });

// });
// 
// 
//$("#menu .nav li").each(function( index ) {
//	var current_item = $(this).find('a');
//		var group_validation = validate_group(current_item.attr("href"));
// if (group_validation == false) {
// 	current_item.find('.click-box').addClass('error');
// 	current_item.find('.click-box').removeClass('checked');
// } else if (group_validation == true) {
// 	current_item.find('.click-box').addClass('checked');
// 	current_item.find('.click-box').removeClass('error');
// }
//	});
//do stuff

// 			 var current_spy_object = $(this).find('a');
// 			 console.log('hit');
// 			 // Validate the last viewed group
// 			 if (prev_spy_object) {
// 			 	var group_validation = validate_group(prev_spy_object.attr("href"));
// 		if (group_validation == false) {
// 			prev_spy_object.find('.click-box').addClass('error');
// 			prev_spy_object.find('.click-box').removeClass('checked');
// 		} else if (group_validation == true) {
// 			prev_spy_object.find('.click-box').addClass('checked');
// 			prev_spy_object.find('.click-box').removeClass('error');
// 		}
// 			 }
// 			 prev_spy_object = current_spy_object;
// });

// $( $(':input') ).each(function( index ) {
// 	var fieldgroup = $(this).parents('.row-fluid').prev('.fieldgroup').attr("id");

// 	// if (fieldgroup) {
// 	// 	$('#trigger-'+fieldgroup).find('.click-box').removeClass('checked');
// 	// 	$('#trigger-'+fieldgroup).find('.click-box').addClass('error');	
// 	// }

// 	// var group_validation = validate_group('#'+fieldgroup);
// 	// 		if (group_validation == false) {
// 	// 			$('#trigger-'+fieldgroup).find('.click-box').addClass('error');
// 	// 			$('#trigger-'+fieldgroup).find('.click-box').removeClass('checked');
// 	// 		} else if (group_validation == true) {
// 	// 			$('#trigger-'+fieldgroup).find('.click-box').addClass('checked');
// 	// 			$('#trigger-'+fieldgroup).find('.click-box').removeClass('error');
// 	// 		}


// 	// console.log(fieldgroup);
// });
// // // Change ddescription tags
// jQuery( ".gsection_description" ).each(function( index ) {
//     var current_description = jQuery(this).text();

//     var myRe = new RegExp("\\{["+current_period+"]:(.*)}$", "g");
//     var myArray = myRe.exec(current_description);
//     jQuery(this).text(myArray[1]);

// });




// ".gsection_description"
// ".gsection_title"
//console.log(current_period);