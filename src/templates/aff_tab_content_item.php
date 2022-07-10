<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- <div data-aff="" class="woocommerce_aff wc-metabox postbox closed" rel=""> -->
<div data-aff="" class="woocommerce_aff wc-metabox closed" rel="">
	<h3>
		<a href="#" class="remove_row delete"><?php esc_html_e( 'Remove', 'woocommerce' ); ?></a>
		<div class="handlediv" title="<?php esc_attr_e( 'Click to toggle', 'woocommerce' ); ?>"></div>
		<div class="tips sort" data-tip="<?php esc_attr_e( 'Drag and drop to set admin attribute order', 'woocommerce' ); ?>"></div>
		<strong class="aff_name"><?php esc_html_e( 'Product link', 'woocommerce' ); ?></strong>
	</h3>
	<div class="woocommerce_aff_data wc-metabox-content woocommerce_options_panel hidden" style="width: 100%;">
		<div class="options_group">
			<?php
				woocommerce_wp_select(
					array(
						'id'    => 'aff_type',
						'label' => __( 'Type', 'woocommerce' ),
						'name'  => 'aff_type[]',
						'options' => array(
							'shopee' => __( 'Shopee', 'woocommerce' ),
							'lazada' => __( 'Lazada', 'woocommerce' ),
							'tiki'   => __( 'Tiki', 'woocommerce' )
							)
						)
					);
			?>
			<input type="hidden" name="aff_position[]" class="aff_position" value="" />
		</div>
		<div class="options_group">
			<?php
				woocommerce_wp_text_input(
					array(
						'id'          => 'aff_title',
						'label'       => __( 'Title', 'woocommerce' ),
						'name'  => 'aff_title[]',
						'desc_tip'    => 'true',
						'description' => __( 'Enter title here.', 'woocommerce' )
					)
				);
			?>
		</div>
		<div class="options_group">
			<?php
				woocommerce_wp_text_input(
					array(
						'id'          => 'aff_price',
						'label'       => __( 'Price', 'woocommerce' ),
						'name'  => 'aff_price[]',
						'desc_tip'    => 'true',
						'description' => __( 'Enter price here.', 'woocommerce' )
					)
				);
			?>
		</div>
		<div class="options_group">
			<?php
				woocommerce_wp_text_input(
					array(
						'id'    => 'aff_link',
						'label' => __( 'Link', 'woocommerce' ),
						'name'  => 'aff_link[]',
						'desc_tip'    => 'true',
						'description' => __( 'Enter link here.', 'woocommerce' )
					)
				);
			?>
		</div>
	</div>
</div>
