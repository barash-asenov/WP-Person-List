<?php

namespace Pangolin\WPR\PostType;

use Pangolin\WPR\Plugin;

use Pangolin\WPR\MetaBox\FirstName;
use Pangolin\WPR\MetaBox\LastName;
use Pangolin\WPR\MetaBox\Description;
use Pangolin\WPR\MetaBox\PositionInCompany;
use Pangolin\WPR\MetaBox\SocialLinks;

/**
 * Class Person
 * @subpackage Pangolin\WPR\PostType
 */
class Person {
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @return    object    A single instance of this class.
	 * @since     1.0.0
	 *
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$instance->do_hooks();
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		$this->first_name          = new FirstName();
		$this->last_name           = new LastName();
		$this->description         = new Description();
		$this->position_in_company = new PositionInCompany();
		$this->social_links        = new SocialLinks();
	}


	/**
	 * Handle WP actions and filters.
	 *
	 * @since    1.0.0
	 */
	private function do_hooks() {
		add_action( 'init', array( $this, 'add_post_type_function' ) );
	}

	/*
	 * Add new post type without single page and archive
	 */
	public function add_post_type_function() {
		$supports = array(
			'thumbnail', // Profile Photo Image For Default
		);
		$labels   = array(
			'name'           => _x( 'Person', 'plural' ),
			'singular_name'  => _x( 'Persons', 'singular' ),
			'menu_name'      => _x( 'Persons', 'admin menu' ),
			'name_admin_bar' => _x( 'Persons', 'admin bar' ),
			'add_new'        => _x( 'Add New Person', 'add new' ),
			'add_new_item'   => __( 'Add New Person' ),
			'new_item'       => __( 'New Person' ),
			'edit_item'      => __( 'Edit Person' ),
			'view_item'      => __( 'View Person' ),
			'all_items'      => __( 'All Persons' ),
			'search_items'   => __( 'Search Person' ),
			'not_found'      => __( 'No person found.' ),
		);
		$args     = array(
			'supports'     => $supports,
			'labels'       => $labels,
			'public'       => false,
			'query_var'    => false,
			'rewrite'      => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_nav_menus' => false,
			'exclude_from_search' => true,
			'has_archive'  => false,
			'hierarchical' => false,
		);

		// Create Persons Post Type
		register_post_type( 'person', $args );

		// Manage Columns
		add_filter( 'manage_person_posts_columns', array( $this, 'set_custom_edit_person_columns' ) );
		add_action( 'manage_person_posts_custom_column', array( $this, 'custom_person_column' ), 10, 2 );


		// Create First Name Field
		add_action( 'add_meta_boxes', function () {
			$this->first_name->create_first_name_field( 'person' );
		} );
		add_action( 'save_post', function () {
			$this->first_name->save_first_name_field();
		} );

		// Create Last Name Field
		add_action( 'add_meta_boxes', function () {
			$this->last_name->create_last_name_field( 'person' );
		} );
		add_action( 'save_post', function () {
			$this->last_name->save_last_name_field();
		} );

		// Create Description Field
		add_action( 'add_meta_boxes', function () {
			$this->description->create_description_field( 'person' );
		} );
		add_action( 'save_post', function () {
			$this->description->save_description_field();
		} );

		// Create Position in Company Field
		add_action( 'add_meta_boxes', function () {
			$this->position_in_company->create_position_in_company_field( 'person' );
		} );
		add_action( 'save_post', function () {
			$this->position_in_company->save_position_in_company_field();
		} );

		// Create Social Links Field
		add_action( 'add_meta_boxes', function () {
			$this->social_links->create_social_links_field( 'person', 'side', 'low' );
		} );
		add_action( 'save_post', function () {
			$this->social_links->save_social_links_field();
		} );
	}


	// Add the data to the custom columns for the book post type:
	function custom_person_column( $column, $post_id ) {
		switch ( $column ) {
			case 'featured_image':
				the_post_thumbnail( 'thumbnail' );
				break;
			case 'first_name':
				echo get_post_meta( $post_id, 'first_name', true );
				break;
			case 'last_name':
				echo get_post_meta( $post_id, 'last_name', true );
				break;
			case 'position_in_company':
				echo get_post_meta( $post_id, 'position_in_company', true );
				break;
		}
	}

	// Manage Columns for Persons Table
	function set_custom_edit_person_columns( $columns ) {
		unset( $columns['date'] );
		unset( $columns['title'] );
		$columns['featured_image']      = __( 'Image of Person' );
		$columns['first_name']          = __( 'First Name' );
		$columns['last_name']           = __( 'Last Name' );
		$columns['position_in_company'] = __( 'Position in Company' );

		return $columns;
	}
}