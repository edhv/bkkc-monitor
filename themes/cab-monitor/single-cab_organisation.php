<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

//edis_user_logged_in()

if ( cab_user_is_allowed() ) {

get_header();


 	$organisation_id = $post->ID;
	 cab_show_menu();

?>


			<div class="row-fluid body-content">
					<span class="span12">
<?

 	// Check if there is a period selected
	if (!$cab_functions->get_current_period()) {


		// Get the text for this page
		//599
		$text = get_post(599);
		//print_r($text->post_content);
		$content = apply_filters ("the_content", $text->post_content);



		// Replace { } with the form list

		$html_form_list = "<ul>";

		// Get the available forms
		$available_forms = $cab_functions->get_available_forms($organisation_id);
		$form_activity = $cab_functions->form_activity_get_all($organisation_id);


		// Walk through available forms
		if ($available_forms) {
			foreach ($available_forms as $key => $period) {

        // @MARK 160922: DO SHOW 2011
				// // don't show old questionaries
				// if (in_array($key, array(3,2))) {
				// 	continue;
				// }
				$html_form_list .= "<li><a href='".add_query_arg( 'period_id', $period->term_id )."'/>Vragenlijst ".$period->name."</a></li>";
			}
		}

		$html_form_list .= "</ul>";

		$content = str_replace("{cab_beschikbare_vragenlijsten}", $html_form_list, $content);


				if (count($available_forms) > 0) {
					echo $content;

				} else {
					echo '<p>Beste lezer,</p><p>Alle beschikbare formulieren zijn reeds ingevuld.</p>';
				}


	} else {



		$period_id = $cab_functions->get_current_period();
?>

<!-- <div class="row-fluid">
			<a id="save-fields" href="#" onclick="auto_save_data_2(<? echo $post->ID; ?>,<? echo $period_id;?>);">save fields in js</a>
			<a id="empty-fields" href="#" >empty fields</a>
			<a id="restore-fields" href="#" onclick="restore_auto_save_data(<? echo $post->ID; ?>,<? echo $period_id;?>);">restore fields</a>
		</div>
 -->
<?
		// First check if the period is available for this organisation, if not deny
		if ($cab_functions->is_period_available($period_id, $organisation_id)) {

			echo "<div id='absolute_help' style=''></div>";
			echo '<div id="auto_save_confirmation" ><p>Het formulier is tijdelijk opgeslagen. U kunt uw browser sluiten en op een later tijdstip verder gaan.</p><p><a href="#" onClick="hide_auto_save_confirmation();">Sluiten</a></p></div>';

			// Get forms based on the user + period
			gravity_form(1, '', false, false, '', false);

		} else {
			echo "Het formulier is niet beschikbaar";
		}

	}



 ?>
</div>
</div>

<? } else {
wp_redirect( home_url() );
exit;
}
?>
<?php get_footer(); ?>
