<?php
/*
Plugin Name: Culturele atlas mailer
Plugin URI: 
Description: Mail System for Culturele Atlas
Version: 0.1
Author: Edhv
Author URI: 
License: 
*/
include_once('php/mailer-functions.php'); // handles all getting with the db
include_once('php/mailer-admin.php'); // Handles the admin part of the plugin

register_activation_hook( __FILE__, array( 'CultureleAtlasMailer', 'install' ) );
register_uninstall_hook( __FILE__, array( 'CultureleAtlasMailer', 'uninstall' ) );



class CultureleAtlasMailer {


    function __construct() {

        add_action('init', array($this, 'init'), 1);
        add_action( 'admin_head', array($this, 'cab_organisations_menu_icon') );

    }




/* 
*   Perform the basic install logic
*
*/
    function install() {

        global $wpdb;
        // Use the upgrade logic from wp-admin
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );



}









function init() {
    global $wpdb;


    $wpdb->show_errors();


    // Create Organisations Post type
    $labels = array(
        'name' => 'Mailer',
        'singular_name' => 'mailer',
        'add_new' => 'Add New',
        'add_new_item' => 'Create new mail',
        'edit_item' => 'Edit mail',
        'new_item' => 'New mail',
        'all_items' => 'All mail',
        'view_item' => 'View mail',
        'search_items' => 'Search mail',
        'not_found' =>  'No mail found',
        'not_found_in_trash' => 'No mail found in Trash', 
        'parent_item_colon' => '',
        'menu_name' => 'Mailer'
        );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true, 
        'show_in_menu' => true, 
        'query_var' => true,
        'rewrite' => array( 'slug' => 'mail' ),
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array( 'title' )
        ); 

    register_post_type( 'mailer', $args );

        do_action('edhv/mailer');

}






function cab_organisations_menu_icon() {
    $img_folder_url = plugins_url('/img/', __FILE__);

   ?>
    <style type="text/css" media="screen">
   /*     #menu-posts-cab_organisation .wp-menu-image {
            background: url('<? echo $img_folder_url; ?>wp_organisations_menu-icon.png') no-repeat 0px 0px !important;
        }*/

        .icon32-posts-cab_organisation {
            background: url('<? echo $img_folder_url; ?>wp_organisations_page-icon.png') no-repeat 2px 2px !important;
        }
    </style>
<?php }










}







$cab_mailer = new CultureleAtlasMailer();




?>