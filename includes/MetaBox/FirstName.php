<?php


namespace Pangolin\WPR\MetaBox;

use Pangolin\WPR\Plugin;

/**
 * Class FirstName for MetaBoxes
 * @subpackage Pangolin\WPR\MetaBox
 */
class FirstName {

	public function create_first_name_field( $screen, $context = 'normal', $priority = 'high' ) {
		add_meta_box(
			'first_name_field',
			'First Name',
			array( $this, 'create_first_name_field_callback' ),
			$screen,
			$context,
			$priority
		);
	}

	public function create_first_name_field_callback() {
		global $post;
		$custom     = get_post_custom( $post->ID );
		$first_name = $custom["first_name"][0];
		?>
        <label>
            <input placeholder="Your First Name" style="width:100%" name="first_name" value="<?= $first_name; ?>"/>
        </label>
		<?php
	}

	public function save_first_name_field() {
		global $post;
		update_post_meta( $post->ID, "first_name",
			$_POST["first_name"] );
	}

}

