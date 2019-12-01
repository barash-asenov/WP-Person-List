<?php
/**
 * WP-Reactivate
 *
 *
 * @package   WP-Reactivate
 * @author    Pangolin
 * @license   GPL-3.0
 * @link      https://gopangolin.com
 * @copyright 2017 Pangolin (Pty) Ltd
 */

namespace Pangolin\WPR\Endpoint;

use Pangolin\WPR;
use WP_REST_Request;
use WP_REST_Response;

/**
 * @subpackage REST_Controller
 */
class Person {
	/**
	 * Instance of this class.
	 *
	 * @since    0.8.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.8.1
	 */
	private function __construct() {
		$plugin            = WPR\Plugin::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
	}

	/**
	 * Set up WordPress hooks and filters
	 *
	 * @return void
	 */
	public function do_hooks() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object    A single instance of this class.
	 * @since     0.8.1
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
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version   = '1';
		$namespace = $this->plugin_slug . '/v' . $version;
		$endpoint  = '/persons/';

		register_rest_route( $namespace, $endpoint, array(
			array(
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_all_persons' ),
				'args'                => array(),
			),
		) );

	}

	/**
	 * Get all persons data
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response
	 */
	public function get_all_persons( $request ) {
		$persons_posts = get_posts( array(
			'numberposts' => - 1,
			'post_type'   => 'person'
		) );

		$persons = array();

		foreach ($persons_posts as $person_post) {
			$person = get_post_custom($person_post->ID);
			$thumbnail = get_the_post_thumbnail_url($person_post->ID);
			$person['id'] = $person_post->ID;
			$person['photo_url'] = $thumbnail;
			array_push($persons, $person);
		}

		if ( isset($persons) && !empty($persons) ) {
			return new \WP_REST_Response( array(
				'success' => true,
				'value'   => $persons
			), 200 );
		} else {
			return new \WP_REST_Response( array(
				'success' => false,
				'value'   => 'Not Found'
			), 200 );
		}
	}
}
