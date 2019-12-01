<?php


namespace Pangolin\WPR\MetaBox;


class LastName {
	public function create_last_name_field( $screen, $context = 'normal', $priority = 'high' ) {
		add_meta_box(
			'last_name_field',
			'Last Name',
			array( $this, 'create_last_name_field_callback' ),
			$screen,
			$context,
			$priority
		);
	}

	public function create_last_name_field_callback() {
		global $post;
		$custom     = get_post_custom( $post->ID );
		$last_name = $custom["last_name"][0];
		?>
        <label>
            <input placeholder="Your Last Name" style="width:100%" name="last_name" value="<?= $last_name; ?>"/>
        </label>
		<?php
	}

	public function save_last_name_field() {
		global $post;
		update_post_meta( $post->ID, "last_name",
			$_POST["last_name"] );
	}
}