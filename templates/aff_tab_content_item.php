<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div data-aff="<?php if(!empty($item) && !empty($item->id)) echo $item->id; ?>" class="woocommerce_aff wc-metabox closed" rel="">
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
						'name'  => 'aff_type['. esc_attr( $i ) .']',
						'options' => array(
							'shopee' => __( 'Shopee', 'woocommerce' ),
							'lazada' => __( 'Lazada', 'woocommerce' ),
							'tiki'   => __( 'Tiki', 'woocommerce' )
							),
						'value' => !empty($item) ? $item->type : 'shopee'
						),
					);
			?>
			<input type="hidden" name="aff_position[<?php echo esc_attr( $i ); ?>]" class="aff_position" value="<?php if(!empty($item)) echo $item->position; ?>" />
		</div>
		<div class="options_group">
			<?php
				woocommerce_wp_text_input(
					array(
						'id'    => 'aff_title',
						'label' => __( 'Title', 'woocommerce' ),
						'name'  => 'aff_title['. esc_attr( $i ) .']',
						'desc_tip'    => 'true',
						'description' => __( 'Enter title here.', 'woocommerce' ),
						'value' => !empty($item) ? $item->title : ''
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
						'name'  => 'aff_price['. esc_attr( $i ) .']',
						'desc_tip'    => 'true',
						'description' => __( 'Enter price here.', 'woocommerce' ),
						'value' => !empty($item) ? $item->price : ''
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
						'name'  => 'aff_link['. esc_attr( $i ) .']',
						'desc_tip'    => 'true',
						'description' => __( 'Enter link here.', 'woocommerce' ),
						'value' => !empty($item) ? $item->link : ''
					)
				);
			?>
		</div>
	</div>
</div>
