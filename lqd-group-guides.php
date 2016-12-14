<?php

/*
Plugin Name: Liquid Group Guides
Plugin URI: https://github.com/LiquidChurch/lqd-group-guides
Description: Creates a Custom Post Type for Group Guides.
Version: 0.4.2
Author: Liquid Church, Dave Mackey
Author URI: http://www.liquidchurch.com/
License: GPL2
*/

class LQD_Group_Guides_CPT {

    /*
     * Constructor: Called when plugin is initialized.
     * TODO: Flush permalinks on activation.
     */
	function __construct() {
		add_action( 'init', array( $this, 'lqd_group_guides_cpt' ) );
        add_action( 'init', 'lqd_register_taxonomy_type' );
        add_action( 'init', 'lqd_register_taxonomy_tags' );
        add_action( 'init', 'lqd_register_taxonomy_series' );
	}

	/**
     * Activation Hook: Registers Group Guides Role to Group Guides CPT
     */
	function plugin_activation() {
	     // Set capabilities for role
        $customCaps = array(
            // Permissions for Groups CPT
            'edit_others_guides'          => true,
            'delete_others_guides'        => true,
            'delete_private_guides'       => true,
            'edit_private_guides'         => true,
            'read_private_guides'         => true,
            'edit_published_guides'       => true,
            'publish_guides'              => true,
            'delete_published_guides'     => true,
            'edit_guides'                 => true,
            'delete_guides'               => true,
            'edit_guide'                  => true,
            'read_guide'                  => true,
            'delete_guide'                => true,
            'read'                        => true,
            // Permissions for Guide Type Taxonomy
            'manage_guide_types'          => true,
            'edit_guide_types'            => true,
            'delete_guide_types'          => true,
            'assign_guide_types'          => true,
            // Permissions for Guide Tags Taxonomy
            'manage_guide_tags'     => true,
            'edit_guide_tags'       => true,
            'delete_guide_tags'     => true,
            'assign_guide_tags'     => true,
            // Permissions for Guide Series Taxonomy
            'manage_guide_series'     => true,
            'edit_guide_series'       => true,
            'delete_guide_series'     => true,
            'assign_guide_series'     => true
    );

    // Create our Group Guides role and assign the custom capabilities to it
    add_role( 'group_guide_editor', __( 'Group Guide Editor', 'lqd-group-guides' ), $customCaps );

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
	    remove_role (group_guide_editor);
    }

    /**
     * Registers a Custom Post Type: Group Guides.
     */
	function lqd_group_guides_cpt() {
		// Define the labels for CPT.
		$labels = array(
			'name'               => _x ('Group Guides', 'post type general name', 'lqd-group-guides' ),
			'singular_name'      => _x ('Group Guide', 'post type singular name', 'lqd-group-guides' ),
			'menu_name'          => _x ('Group Guides', 'admin menu', 'lqd-group-guides' ),
			'name_admin_bar'     => _x ('Group Guides', 'lqd-group-guides' ),
			'add_new'            => _x ('Add New', 'guides', 'lqd-group-guides' ),
			'add_new_item'       => _x ('Add New Guide', 'lqd-group-guides' ),
			'new_item'           => _x ('New Guide', 'lqd-group-guides' ),
			'edit_item'          => _x ('Edit Guide', 'lqd-group-guides' ),
			'view_item'          => _x ('View Guide', 'lqd-group-guides' ),
			'all_items'          => _x ('All Guides', 'lqd-group-guides' ),
			'search_items'       => _x ('Search Guides', 'lqd-group-guides' ),
			'parent_item_colon'  => _x ('Parent Guides:', 'lqd-group-guides' ),
			'not_found'          => _x ('No guide found.', 'lqd-group-guides' ),
			'not_found_in_trash' => _x ('No guide found in Trash.', 'lqd-group-guides' ),
		);

		// Define CPT.
        $capabilities = array(
            'edit_others_posts'     => 'edit_others_guides',
            'delete_others_posts'   => 'delete_others_guides',
            'delete_private_posts'  => 'delete_private_guides',
            'edit_private_posts'    => 'edit_private_guides',
            'read_private_posts'    => 'read_private_guides',
            'edit_published_posts'  => 'edit_published_guides',
            'publish_posts'         => 'publish_guides',
            'delete_published_posts'=> 'delete_published_guides',
            'edit_posts'            => 'edit_guides'   ,
            'delete_posts'          => 'delete_guides',
            'edit_post'             => 'edit_guide',
            'read_post'             => 'read_guide',
            'delete_post'           => 'delete_guide',
        );
		$args = array(
			'description'   => 'Group Guides for Small Groups at Liquid Church.',
			'has_archive'   => true,
			'hierarchical'  => true,
			'labels'        => $labels,
			'menu_icon'     => 'dashicons-admin-comments',
			'menu_position' => 30,
			'public'        => true,
			'rewrite'       => 'group-guides',
			'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'post-formats' ),
            'capabilities'  => $capabilities,
            'map_meta_cap' => true,
		);

		// Register CPT.
		register_post_type( 'group-guides', $args );
	}
}

function lqd_register_taxonomy_type() {
	// Define labels for taxonomy
	$labels = array(
		'name'              => _x( 'Group Guide Types', 'taxonomy general name' ),
		'singular_name'     => _x( 'Group Guide Type', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Group Guide Types' ),
		'all_items'         => __( 'All Group Guide Types' ),
		'parent_item'       => __( 'Parent Group Guide Type' ),
		'parent_item_colon' => __( 'Parent Group Guide Type:'),
		'edit_item'         => __( 'Edit Group Guide Type' ),
		'update_item'       => __( 'Update Group Guide Type' ),
		'add_new_item'      => __( 'Add New Group Guide Type' ),
		'new_item_name'     => __( 'New Group Guide Name Type' ),
		'menu_name'         => __( 'Group Guide Types' ),
	);
	// Define capabilities of taxonomy
    $capabilities = array(
        'manage_terms'          => 'manage_guide_types',
        'edit_terms'            => 'edit_guide_types',
        'delete_terms'          => 'delete_guide_types',
        'assign_terms'          => 'assign_guide_types'
    );
    // Define taxonomy
	$args = array(
		'hierarchical'            => true,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'group-type' ),
        'capabilities'            => $capabilities,
        'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'group-type', array( 'group-guides' ), $args );
}

function lqd_register_taxonomy_tags() {
	// Define labels for taxonomy
	$labels = array(
		'name'              => _x( 'Group Guide Tags', 'taxonomy general name' ),
		'singular_name'     => _x( 'Group Guide Tag', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Group Guide Tags' ),
		'all_items'         => __( 'All Group Guide Tags' ),
		'parent_item'       => __( 'Parent Group Guide Tag' ),
		'parent_item_colon' => __( 'Parent Group Guide Tag:'),
		'edit_item'         => __( 'Edit Group Guide Tag' ),
		'update_item'       => __( 'Update Group Guide Tag' ),
		'add_new_item'      => __( 'Add New Group Guide Tag' ),
		'new_item_name'     => __( 'New Group Guide Tag Name' ),
		'menu_name'         => __( 'Group Guide Tags' ),
	);
	// Define taxonomy
    $capabilities = array (
        'manage_terms'          => 'manage_guide_tags',
        'edit_terms'            => 'edit_guide_tags',
        'delete_terms'          => 'delete_guide_tags',
        'assign_terms'          => 'assign_guide_tags'
    );
	$args = array(
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'group-tag' ),
        'capabilities'            => $capabilities,
        'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'group-tag', array( 'group-guides' ), $args );
}

function lqd_register_taxonomy_series() {
	// Define labels for taxonomy
	$labels = array(
		'name'              => _x( 'Group Guide Series', 'taxonomy general name' ),
		'singular_name'     => _x( 'Group Guide Series', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Group Guide Series' ),
		'all_items'         => __( 'All Group Guide Series' ),
		'parent_item'       => __( 'Parent Group Guide Series' ),
		'parent_item_colon' => __( 'Parent Group Guide Series:'),
		'edit_item'         => __( 'Edit Group Guide Series' ),
		'update_item'       => __( 'Update Group Guide Series' ),
		'add_new_item'      => __( 'Add New Group Guide Series' ),
		'new_item_name'     => __( 'New Group Guide Series Name' ),
		'menu_name'         => __( 'Group Guide Series' ),
	);
	// Define taxonomy
    $capabilities = array (
        'manage_terms'          => 'manage_guide_series',
        'edit_terms'            => 'edit_guide_series',
        'delete_terms'          => 'delete_guide_series',
        'assign_terms'          => 'assign_guide_series'
    );

	$args = array(
		'hierarchical'            => true,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'group-series' ),
        'capabilities'            => $capabilities,
        'map_meta_cap'            => 'true'
	);

	// Register taxonomy.
	register_taxonomy( 'group-series', array( 'group-guides' ), $args );
}

$LQD_Group_Guides_CPT = new LQD_Group_Guides_CPT;

// Define Shortcodes
// Pulls all posts of CPT group-guides
add_shortcode('group-guides','group_guides_query');

function group_guides_query() {
    $args = array(
        'post_type'    => 'group-guides',
        'post_status'  => 'publish'
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

add_shortcode('leader-guides','leader_guides_query');

function leader_guides_query() {
    $args = array(
        'post_type' => 'group-guides',
        'tax_query' => array(
            array(
             'taxonomy' => 'group-type',
             'field' => 'term_id',
             'terms' => 261, // TODO: Make generic
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

add_shortcode('leader-guides','leader_guides_query');

function member_guides_query() {
    $args = array(
        'post_type' => 'group-guides',
        'tax_query' => array(
            array(
                'taxonomy' => 'group-type',
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
register_activation_hook( __FILE__, array( &$LQD_Group_Guides_CPT, 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( &$LQD_Group_Guides_CPT, 'plugin_deactivation' ) );