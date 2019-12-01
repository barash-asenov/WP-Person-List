<?php


namespace Pangolin\WPR;

use Pangolin\WPR\Plugin;


class Block {
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
		if ( ! defined( 'ABSPATH' ) ) {
			exit;
		}

		$plugin            = Plugin::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		$this->version     = $plugin->get_plugin_version();
	}


	/**
	 * Handle WP actions and filters.
	 *
	 * @since    1.0.0
	 */
	private function do_hooks() {
		add_action( 'init', array( $this, 'react_lifecycle_block_cgb_block_assets' ) );
	}

	public function react_lifecycle_block_cgb_block_assets() { // phpcs:ignore


		wp_register_style( 'person_block_style', 'https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css' );
		// Register block editor script for backend.
		wp_register_script(
			'react_lifecycle_block-cgb-block-js', // Handle.
			plugins_url( '/assets/js/blocks.js', dirname( __FILE__ ) ), // Block.build.js: We register the block here. Built with Webpack.
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), // Dependencies, defined above.
			null, // filemtime( plugin_dir_path( __DIR__ ) . 'dist/blocks.build.js' ), // Version: filemtime — Gets file modification time.
			true // Enqueue the script in the footer.
		);

		// WP Localized globals. Use dynamic PHP stuff in JavaScript via `cgbGlobal` object.
		wp_localize_script(
			'react_lifecycle_block-cgb-block-js',
			'cgbGlobal', // Array containing dynamic data for a JS Global.
			[
				'pluginDirPath' => plugin_dir_path( __DIR__ ),
				'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
				// Add more data here that you want to access from `cgbGlobal` object.
			]
		);

		/**
		 * Register Gutenberg block on server-side.
		 *
		 * Register the block on server-side to ensure that the block
		 * scripts and styles for both frontend and backend are
		 * enqueued when the editor loads.
		 *
		 * @link https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type#enqueuing-block-scripts
		 * @since 1.16.0
		 */
		register_block_type(
			'wrp-person-list/person-container', array(
				// Enqueue blocks.build.js in the editor only.
				'editor_script'   => 'react_lifecycle_block-cgb-block-js',
				'editor_style'    => 'person_block_style',
				'style'           => 'person_block_style',
				'render_callback' => function ( $attribute ) {
					wp_register_script( 'block-script', plugins_url( 'wpr-person-list/assets/js/personslist.js' ));
					wp_localize_script( 'block-script', 'wpr_object', $attribute );
					wp_enqueue_script( 'block-script' );

					return '<div id="render-person-block-container"></div>';
				}
			)
		);
	}
}