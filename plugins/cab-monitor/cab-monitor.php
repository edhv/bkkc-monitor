<?php
/*
Plugin Name: Culturele Atlas 
Plugin URI: 
Description: Monitor System for Culturele Atlas
Version: 0.2
Author: Edhv
Author URI: 
License: 
*/
include_once('php/cab-functions.php'); // handles all getting with the db
include_once('php/cab-form.php'); // handles all parsing and populating
include_once('php/cab-api.php'); // handles all getting with the db
include_once('php/cab-admin.php'); // Handles the admin part of the plugin
include_once('php/cab-export.php');

register_activation_hook( __FILE__, array( 'CultureleAtlasMonitor', 'install' ) );
register_uninstall_hook( __FILE__, array( 'CultureleAtlasMonitor', 'uninstall' ) );

if (!session_id()) session_start();


class CultureleAtlasMonitor {


    function __construct() {

        add_action('init', array($this, 'init'), 1);
        add_action( 'before_delete_post', array($this, 'delete_custom_tables') );
        add_action( 'admin_head', array($this, 'cab_organisations_menu_icon') );
        add_action( 'admin_menu', array($this, 'cab_remove_menu_pages' ));



    }




    function cab_remove_menu_pages() {
        remove_menu_page('link-manager.php');
        remove_menu_page('edit.php');
        remove_menu_page('edit-comments.php');

        //remove_menu_page('tools.php');  
    }

/* 
*   Perform the basic install logic
*
*/
    function install() {

        global $wpdb;
        // Use the upgrade logic from wp-admin
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


        // Create table
        $table_name = $wpdb->prefix . "cab_subsidy"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            totaal int(11) NOT NULL,
            gemeente int(11) NOT NULL,
            gemeente_meerjarig int(11) NULL,
            prov_nb int(11) NOT NULL,
            prov_nb_meerjarig int(11) NULL,
            rijk int(11) NOT NULL,
            rijk_meerjarig int(11) NULL,
            fonds_podiumkunsten int(11) NOT NULL,
            fonds_podiumkunsten_meerjarig int(11) NULL,
            mondriaan_stichting int(11) NOT NULL,
            mondriaan_stichting_meerjarig int(11) NULL,
            mondriaan_fonds int(11) NOT NULL,
            mondriaan_fonds_meerjarig int(11) NULL,
            fonds_bkvb int(11) NOT NULL,
            fonds_bkvb_meerjarig int(11) NULL,
            mediafonds int(11) NOT NULL,
            mediafonds_meerjarig int(11) NULL,
            nl_filmfonds int(11) NOT NULL,
            nl_filmfonds_meerjarig int(11) NULL,
            fonds_creatieve_industrie int(11) NOT NULL,
            fonds_creatieve_industrie_meerjarig int(11) NULL,
            letterenfonds int(11) NOT NULL,
            letterenfonds_meerjarig int(11) NULL,
            overig int(11) NOT NULL,
            overig_toelichting longtext NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );


        // Create table
        $table_name = $wpdb->prefix . "cab_eigen_inkomsten"; 

            // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            totaal int (11) NOT NULL,
            publieksinkomsten int(11) NOT NULL,
            sponsoring int(11) NOT NULL,
            private_fondsen int(11) NOT NULL,
            overig int(11) NOT NULL,    
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );

        // Create table
        $table_name = $wpdb->prefix . "cab_omzet"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            totaal int(11) NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );


        // Create table
        $table_name = $wpdb->prefix . "cab_organisatie"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            fte float NOT NULL,
            freelancers int(11) NOT NULL,
            vrijwilligers int(11) NOT NULL,
            stagiaires int(11) NOT NULL,    
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );


        // Create table
        $table_name = $wpdb->prefix . "cab_scholing"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            uitgaven int(11) NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );

        // Create table
        $table_name = $wpdb->prefix . "cab_marketing"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            uitgaven int(11) NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );


        // Create table
        $table_name = $wpdb->prefix . "cab_media"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            aandacht int(11) NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );


        // Categories
        // Create table
        $table_name = $wpdb->prefix . "cab_organisatie_type"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            type_id int(11) NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );

                // Create table
        $table_name = $wpdb->prefix . "cab_organisatie_discipline"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            discipline_id int(11) NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );

                // Create table
        $table_name = $wpdb->prefix . "cab_organisatie_keten"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            keten_id int(11) NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );


        // Aanvullend 
        $table_name = $wpdb->prefix . "cab_activiteiten"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            aanv_vragenlijst_id int(11) NOT NULL,
            totaal int(11) NOT NULL, 
            in_opdracht int(11) NOT NULL, 
            eigen_werk int(11) NOT NULL, 
            premieres int(11) NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );

        $table_name = $wpdb->prefix . "cab_nevenactiviteiten"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            aanv_vragenlijst_id int(11) NOT NULL,
            totaal int(11) NOT NULL, 
            educatief int(11) NOT NULL, 
            overig int(11) NOT NULL, 
            overig_toelichting longtext NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );


        $table_name = $wpdb->prefix . "cab_bezoekers"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            aanv_vragenlijst_id int(11) NOT NULL,
            totaal int(11) NOT NULL,
            standplaats int(11) NULL, 
            provincie int(11) NULL, 
            nederland int(11) NULL, 
            buitenland int(11) NULL,
            podium int(11) NULL, 
            festivals int(11) NULL, 
            scholen int(11) NULL, 
            overig int(11) NULL,
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );






        $table_name = $wpdb->prefix . "cab_vertoningen"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            aanv_vragenlijst_id int(11) NOT NULL,
            totaal int(11) NOT NULL, 
            standplaats int(11) NULL, 
            provincie int(11) NULL, 
            nederland int(11) NULL, 
            buitenland int(11) NULL, 
            bioscoop int(11) NULL,
            filmhuis int(11) NULL,
            festival int(11) NULL,
            omroep int(11) NULL,
            internet int(11) NULL,
            internet_toelichting longtext NOT NULL, 
            UNIQUE KEY id (id)
            );";

        dbDelta( $sql );


                $table_name = $wpdb->prefix . "cab_spreiding"; 

        // Set the table structure for subsidy
        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            period_id int(11) NOT NULL,
            organisation_id int(11) NOT NULL,
            aanv_vragenlijst_id int(11) NOT NULL,
            standplaats int(11) NULL, 
            provincie int(11) NULL, 
            nederland int(11) NULL, 
            buitenland int(11) NULL,
            podium int(11) NULL, 
            festivals int(11) NULL, 
            scholen int(11) NULL, 
            overig int(11) NULL,
            UNIQUE KEY id (id)

            );";

        dbDelta( $sql );


        // Add user role
        add_role('cab_organisation', 'Cab Organisation', array(
            'read' => true, // True allows that capability
            'edit_posts' => true,
            'delete_posts' => false, // Use false to explicitly deny
        ));

}


function uninstall() {
    remove_role( 'cab_organisation' );
}




function init() {
    global $wpdb;


    $wpdb->show_errors();


    // Create Organisations Post type
    $labels = array(
        'name' => 'Organisations',
        'singular_name' => 'Organisation',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Organisation',
        'edit_item' => 'Edit Organisation',
        'new_item' => 'New Organisation',
        'all_items' => 'All Organisations',
        'view_item' => 'View Organisation',
        'search_items' => 'Search Organisations',
        'not_found' =>  'No Organisations found',
        'not_found_in_trash' => 'No Organisations found in Trash', 
        'parent_item_colon' => '',
        'menu_name' => 'Organisations'
        );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true, 
        'show_in_menu' => true, 
        'query_var' => true,
        'rewrite' => array( 'slug' => 'Organisation' ),
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array( 'title' )
        ); 

    register_post_type( 'Cab_Organisation', $args );


}





function delete_custom_tables( $post_id ){

    global $cab_functions;


    if (get_post_type($post_id) == 'cab_organisation') {

        // Delete custom tables
        $cab_functions->remove_custom_tables($post_id);

        return true;

    } else {
        return true;
    }



}

function cab_organisations_menu_icon() {
    $img_folder_url = plugins_url('/img/', __FILE__);

   ?>
    <style type="text/css" media="screen">
/*        #menu-posts-cab_organisation .wp-menu-image {
            background: url('<? echo $img_folder_url; ?>wp_organisations_menu-icon.png') no-repeat 0px 0px !important;
        }
*/
        .icon32-posts-cab_organisation {
            background: url('<? echo $img_folder_url; ?>wp_organisations_page-icon.png') no-repeat 2px 2px !important;
        }
    </style>
<?php }







function form_pre_render($form) {
}




function CultureleAtlasMonitor() { //constructor

}









/* ------------ */
/* GETTERS */


// // Get subsidie
// function get_subsidie($organisation_id, $period) {
//     global $wpdb;

//     $table_name = $wpdb->prefix . "cab_subsidy"; 
//     $sql = "SELECT * FROM ".$wpdb->prefix."cab_subsidy WHERE organisation_id = $organisation_id AND period_id = $period";
//     return $wpdb->get_row($sql, 'ARRAY_A');

// }





}







$cab_monitor = new CultureleAtlasMonitor();




?>