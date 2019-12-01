<?php


namespace Pangolin\WPR\MetaBox;


class SocialLinks {
	public function create_social_links_field( $screen, $context = 'normal', $priority = 'high' ) {
		add_meta_box(
			'social_links_field',
			'Social Links',
			array( $this, 'create_social_links_field_callback' ),
			$screen,
			$context,
			$priority
		);
	}

	public function create_social_links_field_callback() {
		global $post;
		$custom   = get_post_custom( $post->ID );
		$github   = $custom["github"][0];
		$linkedin = $custom["linkedin"][0];
		$xing     = $custom["xing"][0];
		$facebook = $custom["facebook"][0];
		?>
        <label>
            <input placeholder="Github Url" style="width:100%" name="github" value="<?= $github; ?>"/>
        </label>
        <label>
            <input placeholder="Linkedin Url" style="width:100%" name="linkedin" value="<?= $linkedin; ?>"/>
        </label>
        <label>
            <input placeholder="Xing Url" style="width:100%" name="xing" value="<?= $xing; ?>"/>
        </label>
        <label>
            <input placeholder="Facebook Url" style="width:100%" name="facebook" value="<?= $facebook; ?>"/>
        </label>
		<?php
	}

	public function save_social_links_field() {
		global $post;
		update_post_meta( $post->ID, "github", $_POST["github"] );
		update_post_meta( $post->ID, "linkedin", $_POST["linkedin"] );
		update_post_meta( $post->ID, "xing", $_POST["xing"] );
		update_post_meta( $post->ID, "facebook", $_POST["facebook"] );
	}
}