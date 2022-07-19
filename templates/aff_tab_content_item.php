<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div data-aff="<?php if(!empty($item) && !empty($item->id)) echo $item->id; ?>" class="woocommerce_aff wc-metabox closed" rel="<?php if(!empty($item)) echo $item->position; ?>">
	<h3>
		<a href="#" class="remove_row delete"><?php esc_html_e( 'Remove', 'woocommerce' ); ?></a>
		<div class="handlediv" title="<?php esc_attr_e('Click to toggle', 'woocommerce' ); ?>"></div>
		<div class="tips sort" data-tip="<?php esc_attr_e( 'Drag and drop to set admin attribute order', 'woocommerce' ); ?>"></div>
		<strong class="aff_name"><?php esc_html_e( 'Product link', 'woocommerce' ); ?></strong>
	</h3>
	<div class="woocommerce_aff_data wc-metabox-content woocommerce_options_panel hidden" style="width: 100%;">
		<div class="options_group">
			<p class=" form-field aff_type_field">
				<label for="aff_type"><?php _e( 'Type', 'woocommerce' ); ?></label>
				<select style="" id="aff_type" name="aff_type[<?php echo esc_attr( $i ); ?>]" class="select short">
					<?php
						if(!empty($item))
						{
					?>
						<option value="shopee" <?php if($item->type == 'shopee') echo 'selected'; ?>><?php _e( 'Shopee', 'woocommerce' ); ?></option>
						<option value="lazada" <?php if($item->type == 'lazada') echo 'selected'; ?>><?php _e( 'Lazada', 'woocommerce' ); ?></option>
						<option value="tiki" <?php if($item->type == 'tiki') echo 'selected'; ?>><?php _e( 'Tiki', 'woocommerce' ); ?></option>
					<?php
						}
						else{
					?>
							<option value="shopee" selected><?php _e('Shopee', 'woocommerce'); ?></option>
							<option value="lazada"><?php _e('Lazada', 'woocommerce'); ?></option>
							<option value="tiki"><?php _e('Tiki', 'woocommerce'); ?></option>
					<?php
						}
					?>
				</select>
			</p>
			<input type="hidden" name="aff_position[<?php echo esc_attr( $i ); ?>]" class="aff_position" value="<?php if(!empty($item)) echo $item->position; ?>" />
		</div>
		<div class="options_group">
			<p class="form-field aff_title_field ">
				<label for="aff_title"><?php _e( 'Title', 'woocommerce' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php _e( 'Enter title here.', 'woocommerce' ); ?>"></span>
				<input type="text" class="short" style="" name="aff_title[<?php echo esc_attr( $i ); ?>]" id="aff_title" value="<?php echo !empty($item) ? $item->title : ''; ?>" placeholder="">
			</p>
		</div>
		<div class="options_group">
			<p class="form-field aff_title_field ">
				<label for="aff_title"><?php _e( 'Price', 'woocommerce' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php _e( 'Enter price here.', 'woocommerce' ); ?>"></span>
				<input type="text" class="short" style="" name="aff_price[<?php echo esc_attr( $i ); ?>]" id="aff_price" value="<?php echo !empty($item) ? $item->price : ''; ?>" placeholder="">
			</p>
		</div>
		<div class="options_group">
			<p class="form-field aff_title_field ">
				<label for="aff_title"><?php _e( 'Link', 'woocommerce' ); ?></label>
				<span class="woocommerce-help-tip" data-tip="<?php _e( 'Enter link here.', 'woocommerce' ); ?>"></span>
				<input type="text" class="short" style="" name="aff_link[<?php echo esc_attr( $i ); ?>]" id="aff_link" value="<?php echo !empty($item) ? esc_url($item->link) : ''; ?>" placeholder="">
			</p>
		</div>
	</div>
</div>
