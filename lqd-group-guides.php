<?php

/*
Plugin Name: Liquid Group Guides
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Creates a Custom Post Type for Group Guides.
Version: 0.3
Author: Liquid Church, Dave Mackey
Author URI: http://www.liquidchurch.com/
License: GPL2
*/

class LQD_Group_Guides_CPT {

	function __construct() {
		add_action( 'init', array( $this, 'lqd_group_guides_cpt' ) );
	}

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
		$args = array(
			'description'   => 'Group Guides for Small Groups at Liquid Church.',
			'has_archive'   => true,
			'hierarchical'  => true,
			'labels'        => $labels,
			'menu_icon'     => 'dashicons-admin-comments',
			'menu_position' => 30,
			'public'        => true,
			'rewrite'       => 'group-guides',
			'supports'      => 'title, editor, author, thumbnail, excerpt, trackbacks, custom-fields, comments, revisions, page-attributes, post-formats',
			'taxonomies'    => array( 'post_tag' ), // used to be array( 'post_tag' )
		);

		// Register CPT.
		register_post_type( 'group-guides', array( 'public' => true, 'label' => 'Group Guides' ) );

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
	// Define taxonomy
	$args = array(
		'hierarchical'            => true,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'group-type' ),
	);

	// Register taxonomy.
	register_taxonomy( 'group-type', array( 'group-guides' ), $args );
}

add_action( 'init', 'lqd_register_taxonomy_type' );

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
	$args = array(
		'hierarchical'            => false,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'group-tag' ),
	);

	// Register taxonomy.
	register_taxonomy( 'group-tag', array( 'group-guides' ), $args );
}

add_action( 'init', 'lqd_register_taxonomy_tags' );

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
	$args = array(
		'hierarchical'            => true,
		'labels'                  => $labels,
		'show_ui'                 => true,
		'show_admin_column'       => true,
		'query_var'               => true,
		'rewrite'                 => array( 'slug' => 'group-series' ),
	);

	// Register taxonomy.
	register_taxonomy( 'group-series', array( 'group-guides' ), $args );
}

add_action( 'init', 'lqd_register_taxonomy_series' );

$LQD_Group_Guides_CPT = new LQD_Group_Guides_CPT;