<?php

/*
Plugin Name: Liquid Group Serve
Plugin URI: https://github.com/LiquidChurch/lqdoutreach
Description: Creates a Custom Post Type for Group Service Projects.
Version: 0.0.1
Author: Liquid Church, Dave Mackey, Gill Crockford
Author URI: http://www.liquidchurch.com/
License: GPL2
*/

class LQD_Group_Serve_CPT {

    /*
     * Constructor: Called when plugin is initialized.
     * TODO: Flush permalinks on activation.
     */

    /* comment out taxonomaies no longer required */
	function __construct() {
		add_action( 'init', array( $this, 'lqd_group_serve_cpt' ) );
		add_action( 'init', 'lqd_register_taxonomy_project_location' );
		add_action( 'init', 'lqd_register_taxonomy_project_dow' );
		add_action( 'init', 'lqd_register_taxonomy_project_family_friendly' );
		add_action( 'init', 'lqd_register_taxonomy_project_SN_friendly');
		add_action( 'init', 'lqd_register_taxonomy_project_occurs' );
        add_action( 'init', 'lqd_register_taxonomy_project_type' );
		add_action( 'init', 'lqd_register_taxonomy_project_dates' );
        add_action( 'init', 'lqd_register_taxonomy_project_host_url' );
		add_action( 'init', 'lqd_register_taxonomy_project_host_organization' );
		add_action( 'init', 'lqd_register_taxonomy_project_team_size');

	}

	/**
     * Activation Hook: Registers Group Serve Role to Group Serve CPT
     */
	function plugin_activation() {
	     // Set capabilities for role
        $customCaps = array(
            // Permissions for Projects CPT
            'edit_others_projects'          => true,
            'delete_others_projects'        => true,
            'delete_private_projects'       => true,
            'edit_private_projects'         => true,
            'read_private_projects'         => true,
            'edit_published_projects'       => true,
            'publish_projects'              => true,
            'delete_published_projects'     => true,
            'edit_projects'                 => true,
            'delete_projects'               => true,
            'edit_project'                  => true,
            'read_project'                  => true,
            'delete_project'                => true,
            // Permissions for Project Type Taxonomy
            'manage_project_types'          => true,
            'edit_project_types'            => true,
            'delete_project_types'          => true,
            'assign_project_types'          => true,
	        // Permissions for Project Locations Taxonomy
            'manage_project_locations'          => true,
            'edit_project_locations'            => true,
            'delete_project_locations'          => true,
            'assign_project_locations'          => true,
	        // Permissions for Project DOW Taxonomy
            'manage_project_DOW'          => true,
            'edit_project_DOW'            => true,
            'delete_project_DOW'          => true,
            'assign_project_DOW'          => true,
	        // Permissions for Project Dates Taxonomy
            'manage_project_dates'          => true,
            'edit_project_dates'            => true,
            'delete_project_dates'          => true,
            'assign_project_dates'          => true,
	        // Permissions for Project family-friendly Taxonomy
            'manage_project_family_friendly'          => true,
            'edit_project_family_friendly'            => true,
            'delete_project_family_friendly'          => true,
            'assign_project_family_friendly'          => true,
	        // Permissions for Project Host URL Taxonomy
            'manage_project_host_url'          => true,
            'edit_project_host_url'            => true,
            'delete_project_host_url'          => true,
            'assign_project_host_url'          => true,
	        // Permissions for Project Host Organization Taxonomy
            'manage_project_host_organization'          => true,
            'edit_project_host_organization'            => true,
            'delete_project_host_organization'          => true,
            'assign_project_host_organization'          => true,
            // Permissions for Project occurs Taxonomy
            'manage_project_occurences'     => true,
            'edit_project_occurences'       => true,
            'delete_project_occurences'     => true,
            'assign_project_occurences'     => true,
            // Permissions for teamsize Taxonomy
            'manage_project_team_sizes'     => true,
            'edit_project_team_sizes'       => true,
            'delete_project_team_sizes'     => true,
            'assign_project_team_sizes'     => true,
	        // Permissions for SN Friendly Taxonomy
            'manage_project_SN_friendly'         => true,
            'edit_project_SN_friendly'           => true,
            'delete_project_SN_friendly'         => true,
            'assign_project_SN_friendly'         => true,
    );

    // Create our Group Serve role and assign the custom capabilities to it
    add_role( 'group_serve_editor', __( 'Group Serve Editor', 'lqdoutreach' ), $customCaps );

    // Add custom capabilities to Admin and Editor Roles
     $roles = array( 'administrator', 'editor' );
     foreach ( $roles as $roleName ) {
         // Get role
         $role = get_role( $roleName );

         // Check role exists
         if ( is_null( $role) ) {
             continue;
         }

         // Iterate through our custom capabilities, adding them
         // to this role if they are enabled
         foreach ( $customCaps as $capability => $enabled ) {
             if ( $enabled ) {
                 // Add capability
                 $role->add_cap( $capability );
             }
         }
     }
	}

	function plugin_deactivation() {
	    remove_role (group_serve_editor);
    }

    /**
     * Registers a Custom Post Type: Group Service Projects.
     */
	function lqd_group_serve_cpt() {
		// Define the labels for CPT.
		$labels = array(
			'name'               => _x ('Service Projects', 'post type general name', 'lqdoutreach' ),
			'singular_name'      => _x ('Service Project', 'post type singular name', 'lqdoutreach' ),
			'manu_name'          => _x ('Service Projects', 'admin menu', 'lqdoutreach'),
			'name_admin_bar'     => _x ('Service Projects', 'lqdoutreach' ),
			'add_new'            => _x ('Add New', 'projects', 'lqdoutreach' ),
			'add_new_item'       => _x ('Add New Project', 'lqdoutreach' ),
			'new_item'           => _x ('New Project', 'lqdoutreach' ),
			'edit_item'          => _x ('Edit Project', 'lqdoutreach' ),
			'view_item'          => _x ('View Project', 'lqdoutreach' ),
			'all_items'          => _x ('All Projects', 'lqdoutreach' ),
			'search_items'       => _x ('Search Projects', 'lqdoutreach' ),
			'parent_item_colon'  => _x ('Parent Projects:', 'lqdoutreach' ),
			'not_found'          => _x ('No project found.', 'lqdoutreach' ),
			'not_found_in_trash' => _x ('No project found in Trash.', 'lqdoutreach' ),
		);

		// Define CPT.
        $capabilities = array(
            'edit_others_posts'     => 'edit_others_projects',
            'delete_others_posts'   => 'delete_others_projects',
            'delete_private_posts'  => 'delete_private_projects',
            'edit_private_posts'    => 'edit_private_projects',
            'read_private_posts'    => 'read_private_projects',
            'edit_published_posts'  => 'edit_published_projects',
            'publish_posts'         => 'publish_projects',
            'delete_published_posts'=> 'delete_published_projects',
            'edit_posts'            => 'edit_projects'   ,
            'delete_posts'          => 'delete_projects',
            'edit_post'             => 'edit_project',
            'read_post'             => 'read_project',
            'delete_post'           => 'delete_project',
        );
		$args = array(
			'description'   => 'Group Service Opportunities at Liquid Church.',
			'has_archive'   => true,
			'with_front'    => false,
			'hierarchical'  => true,
			'labels'        => $labels,
			'menu_icon'     => 'dashicons-calendar',
			'menu_position' => 31,
			'public'        => true,
			'rewrite'       => 'lqdoutreach',
			'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats' ),
            'capabilities'  => $capabilities,
            'map_meta_cap' => true,
		);

		// Register CPT.
		register_post_type( 'lqdoutreach', $args );
	}
}

function lqd_register_taxonomy_project_type() {
	// Define labels for taxonomy Project Type eg Group Served - Special Needs, Hungry & Homeless, Hands Om
	$labels = array(
		'name'              => _x( 'Types', 'taxonomy general name' ),
		'singular_name'     => _x( 'Type', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Types' ),
		'all_items'         => __( 'All Types' ),
		'parent_item'       => __( 'Parent Type' ),
		'parent_item_colon' => __( 'Parent Type:'),
		'edit_item'         => __( 'Edit Type' ),
		'update_item'       => __( 'Update Type' ),
		'add_new_item'      => __( 'Add New Type' ),
		'new_item_name'     => __( 'New Type' ),
		'menu_name'         => __( 'Types' ),
	);
	// Define capabilities of taxonomy
    $capabilities = array(
        'manage_terms'          => 'manage_project_types',
        'edit_terms'            => 'edit_project_types',
        'delete_terms'          => 'delete_project_types',
        'assign_terms'          => 'assign_project_types'
    );
    // Define taxonomy
	$args = array(
		'hierarchical'            => true,
		'with_front'              => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'project-type' ),
        'capabilities'            => $capabilities,
        'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'project-type', array( 'lqdoutreach' ), $args );
}

function lqd_register_taxonomy_project_dow() {
	// Define labels for taxonomy Project Day of Week - Sun, Mon, Tue, Wed, Thur, Fri ,Sat, Sat-Sun
	$labels = array(
		'name'              => _x( 'Days', 'taxonomy general name' ),
		'singular_name'     => _x( 'Day', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Days' ),
		'all_items'         => __( 'All Days' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit Day' ),
		'update_item'       => __( 'Update Day' ),
		'add_new_item'      => __( 'Add New Day' ),
		'new_item_name'     => __( 'New Day name' ),
		'menu_name'         => __( 'Day of Week' ),
	);
	// Define capabilities of taxonomy
	$capabilities = array(
		'manage_terms'          => 'manage_project_DOW',
		'edit_terms'            => 'edit_project_DOW',
		'delete_terms'          => 'delete_project_DOW',
		'assign_terms'          => 'assign_project_DOW'
	);
	// Define taxonomy
	$args = array(
		'hierarchical'            => true,
		'with_front'              => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'DOW' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'

	);

	// Register taxonomy.
	register_taxonomy( 'DOW', array( 'lqdoutreach' ), $args );
}

function lqd_register_taxonomy_project_location() {
	// Define labels for taxonomy Project Campus/Location - Essex, Middlesex, Morris, Somerset, Union, Garwood
	$labels = array(
		'name'              => _x( 'Project Locations', 'taxonomy general name' ),
		'singular_name'     => _x( 'Project Location', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Locations' ),
		'all_items'         => __( 'All Locations' ),
		'parent_item'       => __( 'Parent Location' ),
		'parent_item_colon' => __( 'Parent Location:'),
		'edit_item'         => __( 'Edit Location' ),
		'update_item'       => __( 'Update Location' ),
		'add_new_item'      => __( 'Add New Location' ),
		'new_item_name'     => __( 'New Location Name' ),
		'menu_name'         => __( 'Project Locations' ),
	);
	// Define capabilities of taxonomy
	$capabilities = array(
		'manage_terms'          => 'manage_project_locations',
		'edit_terms'            => 'edit_project_locations',
		'delete_terms'          => 'delete_project_locations',
		'assign_terms'          => 'assign_project_locations'
	);
	// Define taxonomy
	$args = array(
		'hierarchical'            => true,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'project_location' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'project_location', array( 'lqdoutreach' ), $args );
}


function lqd_register_taxonomy_project_dates() {
	// Define labels for taxonomy Project Dates (if not recurring throughout year)
	$labels = array(
		'name'              => _x( 'Dates', 'taxonomy general name' ),
		'singular_name'     => _x( 'Date', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Dates' ),
		'all_items'         => __( 'All Dates' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit Date' ),
		'update_item'       => __( 'Update Date' ),
		'add_new_item'      => __( 'Add New Date' ),
		'new_item_name'     => __( 'New Date Name' ),
		'menu_name'         => __( 'Dates' ),
	);
	// Define taxonomy
    $capabilities = array (
        'manage_terms'          => 'manage_project_dates',
        'edit_terms'            => 'edit_project_dates',
        'delete_terms'          => 'delete_project_dates',
        'assign_terms'          => 'assign_project_dates'
    );
	$args = array(
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'date' ),
        'capabilities'            => $capabilities,
        'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'date', array( 'lqdoutreach' ), $args );
}

function lqd_register_taxonomy_project_family_friendly() {
	// Define labels for taxonomy family-friendly (lower age limt if any)
	$labels = array(
		'name'              => _x( 'Family friendly', 'taxonomy general name' ),
		'singular_name'     => _x( 'Family friendly', 'taxonomy singular name' ),
		'search_items'      => __( 'Search family friendly' ),
		'all_items'         => __( 'All family friendly' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit family friendly' ),
		'update_item'       => __( 'Update family friendly' ),
		'add_new_item'      => __( 'Add New family friendly' ),
		'new_item_name'     => __( 'New family friendly Name' ),
		'menu_name'         => __( 'Family friendly' ),
	);
	// Define taxonomy
	$capabilities = array (
		'manage_terms'          => 'manage_project_family_friendly',
		'edit_terms'            => 'edit_project_family_friendly',
		'delete_terms'          => 'delete_project_family_friendly',
		'assign_terms'          => 'assign_project_family_friendly'
	);
	$args = array(
		'hierarchical'            => true,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'family_friendly' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'family_friendly', array( 'lqdoutreach' ), $args );
}

function lqd_register_taxonomy_project_host_organization() {
	// Define labels for taxonomy Host Organization name
	$labels = array(
		'name'              => _x( 'Host Org.', 'taxonomy general name' ),
		'singular_name'     => _x( 'Host Org', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Host Org' ),
		'all_items'         => __( 'All Host Org' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit Host Org' ),
		'update_item'       => __( 'Update Host Org' ),
		'add_new_item'      => __( 'Add New Host Org' ),
		'new_item_name'     => __( 'New Host Org Name' ),
		'menu_name'         => __( 'Host Org' ),
	);
	// Define taxonomy
	$capabilities = array (
		'manage_terms'          => 'manage_project_host_organization',
		'edit_terms'            => 'edit_project_host_organization',
		'delete_terms'          => 'delete_project_host_organization',
		'assign_terms'          => 'assign_project_host_organization'
	);
	$args = array(
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'Host-Org' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'Host-Org', array( 'lqdoutreach' ), $args );
}

/* This is now a custom field */
function lqd_register_taxonomy_project_host_url() {
	// Define labels for taxonomy Host URL
	$labels = array(
		'name'              => _x( 'Host URL', 'taxonomy general name' ),
		'singular_name'     => _x( 'Host URL', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Host URLs' ),
		'all_items'         => __( 'All Host URLs' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit Host URL' ),
		'update_item'       => __( 'Update Host URL' ),
		'add_new_item'      => __( 'Add New Host URL' ),
		'new_item_name'     => __( 'New Host URL' ),
		'menu_name'         => __( 'Host URL' ),
	);
	// Define taxonomy
	$capabilities = array (
		'manage_terms'          => 'manage_project_host_url',
		'edit_terms'            => 'edit_project_host_url',
		'delete_terms'          => 'delete_project_host_url',
		'assign_terms'          => 'assign_project_host_url'
	);
	$args = array(
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'Host-URL' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'Host-URL', array( 'lqdoutreach' ), $args );
}


function lqd_register_taxonomy_project_occurs() {
	// Define labels for taxonomy Project Occurs: Year round, Love Week, Christmas Outreach, One Off
	$labels = array(
		'name'              => _x( 'Occurs', 'taxonomy general name' ),
		'singular_name'     => _x( 'Occurs', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Occurences' ),
		'all_items'         => __( 'All Occurences' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit occurence' ),
		'update_item'       => __( 'Update occurence' ),
		'add_new_item'      => __( 'Add occurences' ),
		'new_item_name'     => __( 'New occurence Name' ),
		'menu_name'         => __( 'Occurence' ),
	);
	// Define taxonomy
	$capabilities = array (
		'manage_terms'          => 'manage_project_occurences',
		'edit_terms'            => 'edit_project_occurences',
		'delete_terms'          => 'delete_project_occurences',
		'assign_terms'          => 'assign_project_occurences'
	);

	$args = array(
		'hierarchical'            => true,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'occurs' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'occurs', array( 'lqdoutreach' ), $args );
}

function lqd_register_taxonomy_project_team_size() {
	// Define labels for taxonomy Project Occurs: Year round, Love Week, Christmas Outreach, One Off
	$labels = array(
		'name'              => _x( 'Team size', 'taxonomy general name' ),
		'singular_name'     => _x( 'Team size', 'taxonomy singular name' ),
		'search_items'      => __( 'Search team sizes' ),
		'all_items'         => __( 'All team sizes' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit team size' ),
		'update_item'       => __( 'Update team size' ),
		'add_new_item'      => __( 'Add team size' ),
		'new_item_name'     => __( 'New team size Name' ),
		'menu_name'         => __( 'Team size' ),
	);
	// Define taxonomy
	$capabilities = array (
		'manage_terms'          => 'manage_project_team_sizes',
		'edit_terms'            => 'edit_project_team_sizes',
		'delete_terms'          => 'delete_project_team_sizes',
		'assign_terms'          => 'assign_project_team_sizes'
	);

	$args = array(
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'team-size' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'team-size', array( 'lqdoutreach' ), $args );
}

function lqd_register_taxonomy_project_SN_friendly() {
	// Define labels for taxonomy Project Special Needs Friendly: Yes or No
	$labels = array(
		'name'              => _x( 'Special Needs friendly', 'taxonomy general name' ),
		'singular_name'     => _x( 'SN friendly', 'taxonomy singular name' ),
		'search_items'      => __( 'Search SN friendly' ),
		'all_items'         => __( 'All SN friendly' ),
		'parent_item'       => null,
		'parent_item_colon' => null,
		'edit_item'         => __( 'Edit SN friendly' ),
		'update_item'       => __( 'Update SN friendly' ),
		'add_new_item'      => __( 'Add SN friendly' ),
		'new_item_name'     => __( 'New SN friendly Name' ),
		'menu_name'         => __( 'Special Needs friendly' ),
	);
	// Define taxonomy
	$capabilities = array (
		'manage_terms'          => 'manage_project_SN_friendly',
		'edit_terms'            => 'edit_project_SN_friendly',
		'delete_terms'          => 'delete_project_SN_friendly',
		'assign_terms'          => 'assign_project_SN_friendly',
	);

	$args = array(
		'hierarchical'            => true,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'SN_friendly' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true',
	);

	// Register taxonomy.
	register_taxonomy( 'SN_friendly', array( 'lqdoutreach' ), $args );
}



$LQD_Group_Serve_CPT = new LQD_Group_Serve_CPT;

// Define Shortcodes
// Pulls all posts of CPT group-guides

add_shortcode('group-serve','group_serve_query');

function group_serve_query() {
    $args = array(
        'post_type'       => 'lqdoutreach',
        'post_status'     => 'publish',
	    'posts_per_page'  => '20',
        'order'           => 'ASC',
    );

    $string = '';

    $query = new WP_Query( $args );
    if( $query->have_posts() ) {
        $string .= '<ul>';
        while( $query->have_posts() ) {
            $query->the_post();
            $string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a>, '. get_the_content() . '</li>';
	        //Returns All Term Items for "my_taxonomy"
	        $terms = get_the_term_list($query->ID, 'occurs', '<li>', '</li><li>', '</li>');
	        //print_r($terms);
	        $string .=$terms;
	       $terms = get_the_term_list($query->ID, 'team-size', '<li>', '</li><li>', '</li>');
	      //  print_r($terms);
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'project-location', '<li>', '</li><li>', '</li>');
	        $string .=$terms;
        }
        $string .= '</ul>';
    }
    wp_reset_postdata();
    return $string;
}

add_shortcode('groupserveline','group_serve_query_line');

function group_serve_query_line() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
	);

	$string = '';
	$terms = '';

	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a>';
			//Returns All Term Items for "my_taxonomy"
			/*$terms = get_the_term_list($query->ID, 'Host-URL','  : Host site link : ', '', ' : ');
			$string .=$terms;*/
			$terms = get_the_term_list($query->ID, 'project-type', ' Compassion Focus: ', ' : ', ' ');
			$string .=$terms;
			$terms = get_the_content() . '';
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Day(s): ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'project-location', 'Location: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family-friendly', 'Family Friendly: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'team-size', 'Team size: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'Host-Org', '<br/>    Host Organization: ', '', '');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'occurs', ' Occurs: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'date', 'Dates: ', ' : ', '');
			$string .=$terms . '</li><br/> <br/>';
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('group-serve-term','group_serve_query_term');

function group_serve_query_term() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
	);
	$string = '';

	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a> </li>';
			//Returns All Term Items for "my_taxonomy"
			$terms = get_the_term_list($query->ID, '<li> occurs', '<p> Occurs:', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'team-size', 'Team size: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'project-location', 'Location: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'project-type', 'Compassion Focus: ', ' : ', '');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'Host-Org', ' ', '', ' ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family-friendly', 'Family Friendly: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', 'Day(s): ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'date', 'Dates: ', ' : ', '</li>');
			$string .=$terms;
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}
//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************
//********************************************************************************************************




add_shortcode('groupserveallyrESS','group_serve_query_allyrESS');

function group_serve_query_allyrESS() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'year round',
				),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'essex',
			)
							)
	);
	

	$string = '';
	$terms = '';

	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			//Returns All Term Items for "my_taxonomy"
			/*$terms = get_the_term_list($query->ID, 'Host-URL','  : Host site link : ', '', ' : ');
			$string .=$terms;*/
	/* comment out this ection to remove sign up button */
	//$terms = '<p style= "min-height:50px; "><a class="blue_btn" style="width:200px;float: left;" href=';
	//$string .= $terms;
	//$terms = get_field('sign_up_to_serve');
	//$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a>  ';
	//$terms = '';

	/* comment out this sec tion to remove url button */
	//$terms = '   <a class="blue_btn" style="width:200px;float: right; " href="';
	//$string .= $terms;
	//$terms = get_field('host_url');
	//$string .=  $terms . '" target="_blank"> Host URL</a>';

	/*while (have_posts() ) : the_post();{*/
	$id =$query;
	$string .= '</p> ';
	$terms = get_the_content() . '';
	$string .=$terms .'<p>';

	$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
	$string .=$terms;
	$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
	$string .=$terms;
	$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
	$string .=$terms;
	$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p> ');
	$string .=$terms;

	//$terms = get_field('family-friendly_rating');
	//$string .= 'Family Friendly (min. age): ' . $terms . ' : ';

	/*$terms = get_the_term_list($id, 'team-size', 'Team size: ', ' : ', '  ');*/


	//$terms = get_field('number_of_participants');
			////$string .=$terms;
			//$string .= 'Team size: ' . $terms .'<br/>';
	//$terms = get_the_term_list($query->ID, 'Host-Org', '    Host Organization: ', '', ' : ');
	//$string .=$terms;
	//$terms = get_the_term_list($id, 'occurs', ' Occurs: ', ' : ', ' : ');
	//$string .=$terms;
	//$terms = get_the_term_list($query->ID, 'date', 'Dates: ', ' : ', '');
	//$string .=$terms;
	//$terms = get_the_term_list($query->ID, 'project-type', '   Compassion Focus: ', ' : ', ' ');
	//$string .=$terms;
	/*}*/


	/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserveallyrsignESS','group_serve_query_allyrsignESS');

function group_serve_query_allyrsignESS() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'year round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'essex',
			)
		)
	);

	$string = '';
	$terms = '';

	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';

			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= $terms;
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</li></ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Middlesex
add_shortcode('groupserveallyrMID','group_serve_query_allyrMID');

function group_serve_query_allyrMID() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'year round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'middlesex',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserveallyrsignMID','group_serve_query_allyrsignMID');

function group_serve_query_allyrsignMID() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'year round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'middlesex',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= $terms;
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Morris
add_shortcode('groupserveallyrMOR','group_serve_query_allyrMOR');

function group_serve_query_allyrMOR() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'Year Round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Morris',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserveallyrsignMOR','group_serve_query_allyrsignMOR');

function group_serve_query_allyrsignMOR() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'Year Round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Morris',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px;"><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= $terms;
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Somerset
add_shortcode('groupserveallyrSOM','group_serve_query_allyrSOM');

function group_serve_query_allyrSOM() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'Year Round',
				),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Somerset',
			)
							)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserveallyrsignSOM','group_serve_query_allyrsignSOM');

function group_serve_query_allyrsignSOM() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'Year Round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Somerset',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= $terms;
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Garwood
add_shortcode('groupserveallyrGAR','group_serve_query_allyrGAR');

function group_serve_query_allyr() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'Year Round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Garwood',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserveallyrsignGAR','group_serve_query_allyrsignGAR');

function group_serve_query_allyrsignGAR() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'Year Round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Garwood',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= $terms;
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Mountainside
add_shortcode('groupserveallyrMTS','group_serve_query_allyrMTS');

function group_serve_query_allyrMTS() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'Year Round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Mountainside',
			)
		)
	);

	$string = '';
	$terms = '';

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserveallyrsignMTS','group_serve_query_allyrsignMTS');

function group_serve_query_allyrsignMTS() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'Year Round',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Mountainside',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= $terms;
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


//******************************************************************************

//********************* LOVE WEEKEND SHORTCODES ********************************

//******************************************************************************

add_shortcode('groupserve_lweESS','group_serve_query_lweESS');

function group_serve_query_lweESS() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'essex',
			)
		)
	);


	$string = '';
	$terms = '';

	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			//Returns All Term Items for "my_taxonomy"
			/*$terms = get_the_term_list($query->ID, 'Host-URL','  : Host site link : ', '', ' : ');
			$string .=$terms;*/
			/* comment out this ection to remove sign up button */
			//$terms = '<p style= "min-height:50px; max-width: 55%;"><a class="blue_btn" style="width:200px;float: left;" href=';
			//$string .= $terms;
			//$terms = get_field('sign_up_to_serve');
			//$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a>  ';
			//$terms = '';

			/* comment out this sec tion to remove url button */
			//$terms = '   <a class="blue_btn" style="width:200px;float: right; " href="';
			//$string .= $terms;
			//$terms = get_field('host_url');
			//$string .=  $terms . '" target="_blank"> Host URL</a>';

			/*while (have_posts() ) : the_post();{*/
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';

			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p> ');
			$string .=$terms;

			//$terms = get_field('family-friendly_rating');
			//$string .= 'Family Friendly (min. age): ' . $terms . ' : ';

			/*$terms = get_the_term_list($id, 'team-size', 'Team size: ', ' : ', '  ');*/


			//$terms = get_field('number_of_participants');
			////$string .=$terms;
			//$string .= 'Team size: ' . $terms .'<br/>';
			//$terms = get_the_term_list($query->ID, 'Host-Org', '    Host Organization: ', '', ' : ');
			//$string .=$terms;
			//$terms = get_the_term_list($id, 'occurs', ' Occurs: ', ' : ', ' : ');
			//$string .=$terms;
			//$terms = get_the_term_list($query->ID, 'date', 'Dates: ', ' : ', '');
			//$string .=$terms;
			//$terms = get_the_term_list($query->ID, 'project-type', '   Compassion Focus: ', ' : ', ' ');
			//$string .=$terms;
			/*}*/


			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserve_lwesignESS','group_serve_query_lwesignESS');

function group_serve_query_lwesignESS() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'essex',
			)
		)
	);

	$string = '';
	$terms = '';

	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</li></ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Middlesex
add_shortcode('groupserve_lweMID','group_serve_query_lweMID');

function group_serve_query_lweMID() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'middlesex',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserve_lwesignMID','group_serve_query_lwesignMID');

function group_serve_query_lwesignMID() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'middlesex',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Morris
add_shortcode('groupserve_lweMOR','group_serve_query_lweMOR');

function group_serve_query_lweMOR() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Morris',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserve_lwesignMOR','group_serve_query_lwesignMOR');

function group_serve_query_lwesignMOR() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Morris',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Somerset
add_shortcode('groupserve_lweSOM','group_serve_query_lweSOM');

function group_serve_query_lweSOM() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Somerset',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserve_lwesignSOM','group_serve_query_lwesignSOM');

function group_serve_query_lwesignSOM() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Somerset',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Garwood
add_shortcode('groupserve_lweGAR','group_serve_query_lweGAR');

function group_serve_query_lweGAR() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Garwood',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('groupserve_lwesignGAR','group_serve_query_lwesignGAR');

function group_serve_query_lwesignGAR() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Garwood',
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

//Mountainside
add_shortcode('groupserve_lweMTS','group_serve_query_lweMTS');

function group_serve_query_lweMTS() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Mountainside',
			)
		)
	);

	$string = '';
	$terms = '';

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> <p>&nbsp;</p>');
			$string .=$terms;
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('groupserve_lwesignMTS','group_serve_query_lwesignMTS');

function group_serve_query_lwesignMTS() {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => 'love-weekend',
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => 'Mountainside',
			)
		)
	);

	$string = '';
	$terms = '';

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('groupserve_lwesignMTS_PARAM','group_serve_query_lwesignMTS_PARAM()');

function group_serve_query_lwesignMTS_PARAM($OCCURS='',$CAMPUS='') {
	$args = array(
		'post_type'       => 'lqdoutreach',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy' => 'occurs',
				'field' => 'slug',
				'terms' => $OCCURS,
			),
			array(
				'taxonomy' => 'project_location',
				'field' => 'slug',
				'terms' => $CAMPUS,
			)
		)
	);

	$string = '';
	$terms = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<header class="entry-header"><h3 class="page-title"><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></h3></header>';
			$id =$query;
			$string .= '</p> ';
			$terms = get_the_content() . '';
			$string .=$terms .'<p>';
			$terms = get_the_term_list($query->ID, 'project_location', 'Campus: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Days: ', ', ', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'family_friendly', 'Family Friendly: ', '', '   &nbsp;&nbsp;&nbsp;');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'SN_friendly', 'Special Needs Friendly: ', '', ' </p> ');
			$string .=$terms;
			$terms = '<p style= "min-height:40px; "><a class="blue_btn" style="width:200px;float: left;" href=';
			$string .= $terms;
			$terms = get_field('sign_up_to_serve');
			$string .= '"'. $terms . '"'.'target="_blank"> Sign up to serve</a><br/></p><p>&nbsp;</p> ';
			/*endwhile; */
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

/**
 * If Archive is LQD Group Serve CPT Load CPT Template.
 */
add_filter( 'page_template', 'archive_lqd_group_serve_template' );
function archive_lqd_group_serve_template( $page_template )
{
	if ( is_post_type_archive( 'lqdoutreach')) {
		$page_template = dirname( __FILE__ ) . '/archive-lqdoutreach.php';
	}
	return $page_template;
}

/**
 * If Single is LQD Group Serve CPT Load CPT Template.
 */
add_filter( 'page_template', 'single_lqd_group_serve_template' );
function single_lqd_group_serve_template( $page_template )
{
	if ( is_singular( 'lqdoutreach' )) {
		$page_template = dirname( __FILE__ ) . '/single-lqdoutreach.php';
	}
	return $page_template;
}

/**
 * If Search is LQD Group Serve CPT Load CPT Template.
 */
add_filter( 'page_template', 'search_lqd_group_serve_template' );
function search_lqd_group_serve_template( $page_template )
{
	if ( is_singular( 'lqdoutreach' )) {
		$page_template = dirname( __FILE__ ) . '/search-lqdoutreach.php';
	}
	return $page_template;
}

/**
 * If Search is LQD Group Serve Custom Taxonomy, Load Custom Template.
 */
add_filter('template_include', 'project_locations_lqd_group_serve_template');
//add_filter( 'taxonomy_template', 'project_locations_lqd_group_serve_template' );
function project_locations_lqd_group_serve_template( $template )
{
	$lqdtaxonomies = array( 'project_location', 'DOW', 'family_friendly', 'SN_friendly' );
	if ( is_tax( $lqdtaxonomies )) {
		//$template = plugins_url( 'lqd-group-serve' . '/taxonomy-project_location.php');
		//$template = dirname( __FILE__ ) . '/taxonomy-project_location.php';
		$template = dirname( __FILE__ ) . '/taxonomy-lqdoutreach.php';
	}
	return $template;
}

/**
 * Add Lqd Group Serve CSS Styles
 */
add_action( 'wp_enqueue_scripts', 'callback_for_setting_up_scripts' );
function callback_for_setting_up_scripts() {
	//$css_location = plugins_url( 'lqd-group-serve' . '/css/style.css' );
	wp_enqueue_style( 'lqdoutreach-css', plugins_url( 'lqd-group-serve/css/style.css', array(),'0.9.4' ));
	//wp_register_style( 'lqdoutreach-css', $css_location);
	//wp_enqueue_style( 'lqdoutreach-css');
}

// Register lqdoutreach-cpt

register_activation_hook( __FILE__, array( &$LQD_Group_Serve_CPT, 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( &$LQD_Group_Serve_CPT, 'plugin_deactivation' ) );
