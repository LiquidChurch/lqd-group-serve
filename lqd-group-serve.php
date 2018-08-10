<?php

/*
Plugin Name: Liquid Group Serve
Plugin URI: https://github.com/LiquidChurch/lqd-group-serve
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
	function __construct() {
		add_action( 'init', array( $this, 'lqd_group_serve_cpt' ) );
        add_action( 'init', 'lqd_register_taxonomy_project_type' );
		add_action( 'init', 'lqd_register_taxonomy_project_location' );
		add_action( 'init', 'lqd_register_taxonomy_project_dow' );
		add_action( 'init', 'lqd_register_taxonomy_project_dates' );
		add_action( 'init', 'lqd_register_taxonomy_project_FFRating' );
        add_action( 'init', 'lqd_register_taxonomy_project_host_url' );
		add_action( 'init', 'lqd_register_taxonomy_project_host_organisation' );
		add_action( 'init', 'lqd_register_taxonomy_project_occurs' );
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
	        // Permissions for Project FFRating Taxonomy
            'manage_project_FFRatings'          => true,
            'edit_project_FFRatings'            => true,
            'delete_project_FFRatings'          => true,
            'assign_project_FFRatings'          => true,
	        // Permissions for Project Host URL Taxonomy
            'manage_project_host_url'          => true,
            'edit_project_host_url'            => true,
            'delete_project_host_url'          => true,
            'assign_project_host_url'          => true,
	        // Permissions for Project Host Organisation Taxonomy
            'manage_project_host_organisation'          => true,
            'edit_project_host_organisation'            => true,
            'delete_project_host_organisation'          => true,
            'assign_project_host_organisation'          => true,
            // Permissions for Project occurs Taxonomy
            'manage_project_occurences'     => true,
            'edit_project_occurences'       => true,
            'delete_project_occurences'     => true,
            'assign_project_occurences'     => true,
            // Permissions for teamsize Taxonomy
            'manage_project_team_sizes'     => true,
            'edit_project_team_sizes'       => true,
            'delete_project_team_sizes'     => true,
            'assign_project_team_sizes'     => true
    );

    // Create our Group Serve role and assign the custom capabilities to it
    add_role( 'group_serve_editor', __( 'Group Serve Editor', 'lqd-group-serve' ), $customCaps );

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
			'name'               => _x ('Service Projects', 'post type general name', 'lqd-group-serve' ),
			'singular_name'      => _x ('Service Project', 'post type singular name', 'lqd-group-serve' ),
			'manu_name'          => _x ('Service Projects', 'admin menu', 'lqd-group-serve'),
			'name_admin_bar'     => _x ('Service Projects', 'lqd-group-serve' ),
			'add_new'            => _x ('Add New', 'projects', 'lqd-group-serve' ),
			'add_new_item'       => _x ('Add New Project', 'lqd-group-serve' ),
			'new_item'           => _x ('New Project', 'lqd-group-serve' ),
			'edit_item'          => _x ('Edit Project', 'lqd-group-serve' ),
			'view_item'          => _x ('View Project', 'lqd-group-serve' ),
			'all_items'          => _x ('All Projects', 'lqd-group-serve' ),
			'search_items'       => _x ('Search Projects', 'lqd-group-serve' ),
			'parent_item_colon'  => _x ('Parent Projects:', 'lqd-group-serve' ),
			'not_found'          => _x ('No project found.', 'lqd-group-serve' ),
			'not_found_in_trash' => _x ('No project found in Trash.', 'lqd-group-serve' ),
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
			'hierarchical'  => true,
			'labels'        => $labels,
			'menu_icon'     => 'dashicons-calendar',
			'menu_position' => 31,
			'public'        => true,
			'rewrite'       => 'lqd-group-serve',
			'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats' ),
            'capabilities'  => $capabilities,
            'map_meta_cap' => true,
		);

		// Register CPT.
		register_post_type( 'lqd-group-serve', $args );
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
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'project-type' ),
        'capabilities'            => $capabilities,
        'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'project-type', array( 'lqd-group-serve' ), $args );
}

function lqd_register_taxonomy_project_dow() {
	// Define labels for taxonomy Project Day of Week - Sun, Mon, Tue, Wed, Thur, Fri ,Sat, Sat-Sun
	$labels = array(
		'name'              => _x( 'DOW', 'taxonomy general name' ),
		'singular_name'     => _x( 'DOW', 'taxonomy singular name' ),
		'search_items'      => __( 'Search DOW' ),
		'all_items'         => __( 'All DOW' ),
		'parent_item'       => __( 'Parent DOW' ),
		'parent_item_colon' => __( 'Parent DOW:'),
		'edit_item'         => __( 'Edit DOW' ),
		'update_item'       => __( 'Update DOW' ),
		'add_new_item'      => __( 'Add New DOW' ),
		'new_item_name'     => __( 'New DOW name' ),
		'menu_name'         => __( 'DOW' ),
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
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'DOW' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'DOW', array( 'lqd-group-serve' ), $args );
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
		'rewrite'                 => array( 'slug' => 'project-location' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'project-location', array( 'lqd-group-serve' ), $args );
}


function lqd_register_taxonomy_project_dates() {
	// Define labels for taxonomy Project Dates (if not recurring throughout year)
	$labels = array(
		'name'              => _x( 'Dates', 'taxonomy general name' ),
		'singular_name'     => _x( 'Date', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Dates' ),
		'all_items'         => __( 'All Dates' ),
		'parent_item'       => __( 'Parent Date' ),
		'parent_item_colon' => __( 'Parent Date:'),
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
	register_taxonomy( 'date', array( 'lqd-group-serve' ), $args );
}

function lqd_register_taxonomy_Project_FFRating() {
	// Define labels for taxonomy FFRating (lower age limt if any)
	$labels = array(
		'name'              => _x( 'FFRating', 'taxonomy general name' ),
		'singular_name'     => _x( 'FFRating', 'taxonomy singular name' ),
		'search_items'      => __( 'Search FFRatings' ),
		'all_items'         => __( 'All FFRatings' ),
		'parent_item'       => __( 'Parent FFRating' ),
		'parent_item_colon' => __( 'Parent FFRating:'),
		'edit_item'         => __( 'Edit FFRating' ),
		'update_item'       => __( 'Update FFRating' ),
		'add_new_item'      => __( 'Add New FFRating' ),
		'new_item_name'     => __( 'New FFRating Name' ),
		'menu_name'         => __( 'FFRatings' ),
	);
	// Define taxonomy
	$capabilities = array (
		'manage_terms'          => 'manage_project_FFRatings',
		'edit_terms'            => 'edit_project_FFRatings',
		'delete_terms'          => 'delete_project_FFRatings',
		'assign_terms'          => 'assign_project_FFRatings'
	);
	$args = array(
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'FFRating' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'FFRating', array( 'lqd-group-serve' ), $args );
}

function lqd_register_taxonomy_project_host_organisation() {
	// Define labels for taxonomy Host Organisation name
	$labels = array(
		'name'              => _x( 'Host Org.', 'taxonomy general name' ),
		'singular_name'     => _x( 'Host Org', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Host Org' ),
		'all_items'         => __( 'All Host Org' ),
		'parent_item'       => __( 'Parent Host Org' ),
		'parent_item_colon' => __( 'Parent Host Org:'),
		'edit_item'         => __( 'Edit Host Org' ),
		'update_item'       => __( 'Update Host Org' ),
		'add_new_item'      => __( 'Add New Host Org' ),
		'new_item_name'     => __( 'New Host Org Name' ),
		'menu_name'         => __( 'Host Org' ),
	);
	// Define taxonomy
	$capabilities = array (
		'manage_terms'          => 'manage_project_host_organisation',
		'edit_terms'            => 'edit_project_host_organisation',
		'delete_terms'          => 'delete_project_host_organisation',
		'assign_terms'          => 'assign_project_host_organisation'
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
	register_taxonomy( 'Host-Org', array( 'lqd-group-serve' ), $args );
}
function lqd_register_taxonomy_project_host_url() {
	// Define labels for taxonomy Host URL
	$labels = array(
		'name'              => _x( 'Host URL', 'taxonomy general name' ),
		'singular_name'     => _x( 'Host URL', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Host URLs' ),
		'all_items'         => __( 'All Host URLs' ),
		'parent_item'       => __( 'Parent Host URL' ),
		'parent_item_colon' => __( 'Parent Host URL:'),
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
	register_taxonomy( 'Host-URL', array( 'lqd-group-serve' ), $args );
}


function lqd_register_taxonomy_project_occurs() {
	// Define labels for taxonomy Project Occurs: Year round, Love Week, Christmas Outreach, One Off
	$labels = array(
		'name'              => _x( 'Occurs', 'taxonomy general name' ),
		'singular_name'     => _x( 'Occurs', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Occurences' ),
		'all_items'         => __( 'All Occurences' ),
		'parent_item'       => __( 'Parent occurences' ),
		'parent_item_colon' => __( 'Parent occurences:'),
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
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'occurs' ),
		'capabilities'            => $capabilities,
		'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'occurs', array( 'lqd-group-serve' ), $args );
}

function lqd_register_taxonomy_project_team_size() {
	// Define labels for taxonomy Project Occurs: Year round, Love Week, Christmas Outreach, One Off
	$labels = array(
		'name'              => _x( 'Team size', 'taxonomy general name' ),
		'singular_name'     => _x( 'Team size', 'taxonomy singular name' ),
		'search_items'      => __( 'Search team sizes' ),
		'all_items'         => __( 'All team sizes' ),
		'parent_item'       => __( 'Parent team size' ),
		'parent_item_colon' => __( 'Parent team size:'),
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
	register_taxonomy( 'team-size', array( 'lqd-group-serve' ), $args );
}


$LQD_Group_Serve_CPT = new LQD_Group_Serve_CPT;

// Define Shortcodes
// Pulls all posts of CPT group-guides

add_shortcode('group-serve','group_serve_query');

function group_serve_query() {
    $args = array(
        'post_type'       => 'lqd-group-serve',
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
	       // print_r($terms);
	    /*    $string .=$terms;
	      //  $terms = get_the_term_list($query->ID, 'project-type', '<li>', '</li><li>', '</li>');
	       // print_r($terms);
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'Host-Org', '<li>', '</li><li>', '</li>');
	       // print_r($terms);
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'Host-URL', '<li>', '</li><li>', '</li>');
	       // print_r($terms);
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'FFRating', '<li>', '</li><li>', '</li>');
	        //print_r($terms);
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'DOW', '<li>', '</li><li>', '</li>');
	       // print_r($terms);
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'date', '<li>', '</li><li>', '</li>');
	       // print_r($terms);
	        $string .=$terms;
	        $terms = wp_get_post_terms($query->ID, 'occurs', array("fields" => "all"));
	        //print_r($terms);
	        $term_list = wp_get_post_terms($query->ID, 'DOW', array("fields" => "names"));
	        $string .= $term_list;*/
        }
        $string .= '</ul>';
    }
    wp_reset_postdata();
    return $string;
}

add_shortcode('groupserveline','group_serve_query_line');

function group_serve_query_line() {
	$args = array(
		'post_type'       => 'lqd-group-serve',
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
			$terms = get_the_term_list($query->ID, 'Host-URL','  : Host site link : ', '', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'project-type', '   Type: ', ' : ', ' ');
			$string .=$terms;
			$terms = get_the_content() . '';
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', '   Day: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'project-location', 'Location: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'FFRating', 'FFRating: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'team-size', 'Team size: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'Host-Org', '<br/>    Host Organisation: ', '', '');
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





//add_shortcode('group-tax','group_tax');
//function group_tax() {
	/* Add your taxonomy. */
//	$taxonomies = array(
//		'DOW',
//	);
//$result = '';

/*	$args = array(
		'orderby'           => 'name',
		'order'             => 'ASC',
		'hide_empty'        => true,
		'exclude'           => array(),
		'exclude_tree'      => array(),
		'include'           => array(),
		'number'            => '',
		'fields'            => 'terms',
		'slug'              => '',
		'parent'            => '',
		'hierarchical'      => true,
		'child_of'          => 0,
		'get'               => '',
		'name__like'        => '',
		'description__like' => '',
		'pad_counts'        => false,
		'offset'            => '',
		'search'            => '',
		'cache_domain'      => 'core'
//	);
*/
//	$terms = get_terms( $taxonomies, $args );
//	foreach ( $terms as $term ) {
//		$result .=$terms;


// here's my code for getting the posts for custom post type

//		$posts_array = get_posts(
//			array( 'showposts' => -1,
//			       'post_type' => 'lqd-group-serve',
//			       'tax_query' => array(
//				       array(
//					       'taxonomy' => 'DOW',
//					       'field' => term_id,
//					       'terms' => $term->name,
//				       )
//			       )
//			)
//		);
//		print_r( $posts_array );
//	}
//	return $result;
//}

add_shortcode('group-serve-t','group_serve_query_t');

function group_serve_query_t() {
	$args = array(
		'post_type'       => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
	);
	$string = '';

	$query = new WP_Query( $args );
	if( $query->have_posts() ) {

		$terms = get_terms( 'post_tag', array('taxonomy' => 'post_tag',
			'hide_empty' => false,
		) );
			$string .=$terms;
			//$terms = get_the_term_list($query->ID, 'team-size', 'Team size: ', ' : ', ' : ');
			//$string .=$terms;
			//$terms = get_the_term_list($query->ID, 'location', 'Location: ', ' : ', ' : ');
			//$string .=$terms;
		//	$terms = get_the_term_list($query->ID, 'type', 'Type: ', ' : ', '');
			//$string .=$terms;
		//	$terms = get_the_term_list($query->ID, 'Host-Org', ' ', '', ' ');
			//$string .=$terms;
			//$terms = get_the_term_list($query->ID, 'FFRating', 'FFRating: ', ' : ', ' : ');
			//$string .=$terms;
			//$terms = get_the_term_list($query->ID, 'DOW', 'DOW: ', ' : ', ' : ');
			//$string .=$terms;
		}
		//$string .= '</ul>';

	wp_reset_postdata();
	return $string;
}

add_shortcode('group-serve-term','group_serve_query_term');

function group_serve_query_term() {
	$args = array(
		'post_type'       => 'lqd-group-serve',
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
			$terms = get_the_term_list($query->ID, 'project-type', 'Type: ', ' : ', '');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'Host-Org', ' ', '', ' ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'FFRating', 'FFRating: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'DOW', 'DOW: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'date', 'Dates: ', ' : ', '</li>');
			$string .=$terms;
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('Occurs','Occurs_query');

function Occurs_query() {
	$args = array(
		'post_type' => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'occurs',
				'field'            => 'name',
				'terms'            => 'Love Week',
				'operator'         => 'IN',
			),
		),
	);

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		echo "0";
		while( $query->have_posts() ) {
			print_r(1);
			$query->the_post();
			echo "2";
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title();
			echo "3";
			$string .= '</a>'. get_the_content() .'</li>';
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('Morris','Morris_query');

function Morris_query() {
	$args = array(
		'post_type' => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'location',
				'field'            => 'name',
				'terms'            => 'Morris',
				'operator'         => 'IN',
			),
		),
	);

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('Mon','DOW_Mon_query');

function DOW_Mon_query() {
    $args = array(
        'post_type' => 'lqd-group-serve',
        'post_status'     => 'publish',
        'posts_per_page'  => '20',
        'order'           => 'ASC',
        'tax_query' => array(
		        array(
			        'taxonomy'         => 'DOW',
			        'field'            => 'name',
			       // 'terms'            => 'Mon','Tue','Wed', 'Thu','Fri','Sat','Sun',
			        'terms'             => 'Mon',
			        'operator'         => 'IN',
		        ),
	        ),
    );

    $string = '';
    $query = new WP_Query( $args );
    if( $query->have_posts() ) {
        $string .= '<ul>';
        while( $query->have_posts() ) {
            $query->the_post();
            $string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
	        $terms = get_the_term_list($query->ID, 'DOW', 'DOW: ', ' : ', ' : ');
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'team-size', 'Team size: ', ' : ', ' : ');
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'project-location', 'Location: ', ' : ', ' : ');
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'project-type', 'Type: ', ' : ', '');
	        $string .=$terms;
	        $terms = get_the_term_list($query->ID, 'FFRating', 'FFRating: ', ' : ', ' : ');
	        $string .=$terms;
        }
        $string .= '</ul>';
    }
    wp_reset_postdata();
    return $string;
}

add_shortcode('Sun','DOW_Sun_query');

function DOW_Sun_query() {
	$args = array(
		'post_type' => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'DOW',
				'field'            => 'name',
				'terms'            => 'Sun',
				'operator'         => 'IN',
			),
		),
	);

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
			$terms = get_the_term_list($query->ID, 'DOW', 'DOW: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'team-size', 'Team size: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'project-location', 'Location: ', ' : ', ' : ');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'project-type', 'Type: ', ' : ', '');
			$string .=$terms;
			$terms = get_the_term_list($query->ID, 'FFRating', 'FFRating: ', ' : ', ' : ');
			$string .=$terms;
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('Tue','DOW_Tue_query');

function DOW_Tue_query() {
	$args = array(
		'post_type' => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'DOW',
				'field'            => 'name',
				'terms'            => 'Tue',
				'operator'         => 'IN',
			),
		),
	);

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('Wed','DOW_Wed_query');

function DOW_Wed_query() {
	$args = array(
		'post_type' => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'DOW',
				'field'            => 'name',
				'terms'            => 'Wed',
				'operator'         => 'IN',
			),
		),
	);

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('Thu','DOW_Thu_query');

function DOW_Thu_query() {
	$args = array(
		'post_type' => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'DOW',
				'field'            => 'name',
				'terms'            => 'Thu',
				'operator'         => 'IN',
			),
		),
	);

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('Fri','DOW_Fri_query');

function DOW_Fri_query() {
	$args = array(
		'post_type' => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'DOW',
				'field'            => 'name',
				'terms'            => 'MonFriIN',
			),
		),
	);

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}


add_shortcode('Sat','DOW_Sat_query');

function DOW_Sat_query() {
	$args = array(
		'post_type' => 'lqd-group-serve',
		'post_status'     => 'publish',
		'posts_per_page'  => '20',
		'order'           => 'ASC',
		'tax_query' => array(
			array(
				'taxonomy'         => 'DOW',
				'field'            => 'name',
				'terms'            => 'Sat',
				'operator'         => 'IN',
			),
		),
	);

	$string = '';
	$query = new WP_Query( $args );
	if( $query->have_posts() ) {
		$string .= '<ul>';
		while( $query->have_posts() ) {
			$query->the_post();
			$string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
		}
		$string .= '</ul>';
	}
	wp_reset_postdata();
	return $string;
}

add_shortcode('location','location_query');

function location_query() {
    $args = array(
        'post_type' => 'lqd-group-serve',
        'tax_query' => array(
            array(
                'taxonomy' => 'group-serve',
                'field' => 'term_id',
                'terms' => 262,  // TODO: Make generic
            ),
        ),
    );

    $string = '';
    $query = new WP_Query( $args );
    if( $query->have_posts() ) {
        $string .= '<ul>';
        while( $query->have_posts() ) {
            $query->the_post();
            $string .= '<li><a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_title() . '</a></li>';
        }
        $string .= '</ul>';
    }
    wp_reset_postdata();
    return $string;
}

register_activation_hook( __FILE__, array( &$LQD_Group_Serve_CPT, 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( &$LQD_Group_Serve_CPT, 'plugin_deactivation' ) );


//* add_shortcode('days-of-week', 'get_days_of_week');

//function get_days_of_week() {
//	$terms = get_terms( array(
//		'taxonomy' => 'DOW',
//		'hide_empty' => 'false',
//	));

//	$res_html  = '';

//	foreach ( $terms as $term ) {

//add_shortcode( 'wp', 'list_terms_custom_taxonomy' );

//function list_terms_custom_taxonomy( $atts) {
//	extract( shortcode_atts( array(
//		'custom_taxonomy' => '',
//	), $atts ) );

//	ob_start();
//	global $post;
//	$custom_taxonomy = 'occurs'
//	$string = '<ul class="tax">';
//	$string .= get_the_term_list( $post->ID , $custom_taxonomy, '<li>', '</li><li>', '</li>' );
//	$string .= ob_get_clean();
//	$string .= '</ul>';
//	return $string;

// $res_html .= json_encode($term);
//		$res_html .= 'Test :  <input type="checkbox" name="'. $term->name .'" / >'. $term->name .'<br />';
//	}

//	return $res_html;

//	}

//}