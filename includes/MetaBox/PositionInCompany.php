<?php


namespace Pangolin\WPR\MetaBox;


class PositionInCompany {
	public function create_position_in_company_field( $screen, $context = 'normal', $priority = 'high' ) {
		add_meta_box(
			'position_in_company_field',
			'Position in Company',
			array( $this, 'create_position_in_company_field_callback' ),
			$screen,
			$context,
			$priority
		);
	}

	public function create_position_in_company_field_callback() {
		global $post;
		$custom      = get_post_custom( $post->ID );
		$position_in_company = $custom["position_in_company"][0];
		?>
		<label>
			<input placeholder="Position in the Company" style="width:100%" name="position_in_company" value="<?= $position_in_company; ?>"/>
		</label>
		<?php
	}

	public function save_position_in_company_field() {
		global $post;
		update_post_meta( $post->ID, "position_in_company",
			$_POST["position_in_company"] );
	}
}