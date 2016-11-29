<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */


// Go to users organisation page directly
if ( is_user_logged_in() ) {

		$organisation_id = $cab_functions->get_organisation_id_by_user_id($current_user->data->ID);

		if (!current_user_can( 'manage_options' ) ) {
			if ($organisation_id) {
				
				//print_r($current_user);
				wp_redirect(get_permalink( $organisation_id ));
			}
		}
} 


get_header(); 

		cab_show_menu(); 


?>





		<?php
		if ( is_user_logged_in() ) {
			$organisations = $cab_functions->get_all_vragenlijst_organisations();


			echo "<ul>";
			foreach ($organisations as $key => $organisation) {
				echo "<li>".$organisation->post_title." - <a href='".get_bloginfo('url').'/Organisation/?p='.$organisation->ID."''>vragenlijst</a></li>";

			}
			echo "</ul>";
		} else {
			?>

					<div class="row-fluid body-content">
					<span class="span5">
			<? wp_login_form(); ?>
		</span>

				</div>
			<?
		}

		?>



<?php get_footer(); ?>