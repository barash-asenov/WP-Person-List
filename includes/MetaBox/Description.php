<?php


namespace Pangolin\WPR\MetaBox;


class Description {
	public function create_description_field( $screen, $context = 'normal', $priority = 'high' ) {
		add_meta_box(
			'description_field',
			'Description',
			array( $this, 'create_description_field_callback' ),
			$screen,
			$context,
			$priority
		);
	}

	public function create_description_field_callback() {
		global $post;
		$custom      = get_post_custom( $post->ID );
		$description = $custom["description"][0];
		?>
        <label>
			<textarea placeholder="Short Description" style="width:100%" name="description" rows="10"><?= $description; ?></textarea>
        </label>
		<?php
	}

	public function save_description_field() {
		global $post;
		update_post_meta( $post->ID, "description",
			$_POST["description"] );
	}
}