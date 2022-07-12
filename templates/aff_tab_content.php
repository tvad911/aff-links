<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="product_aff_cf" class="panel wc-metaboxes-wrapper hidden">
	<div class="toolbar toolbar-top">
		<span class="expand-close">
			<a href="#" class="expand_all">
				<?php esc_html_e( 'Expand', 'woocommerce' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'woocommerce' ); ?>
			</a>
		</span>
		<button type="button" class="button add_aff"><?php esc_html_e( 'Add', 'woocommerce' ); ?></button>
	</div>
	<div class="product_aff_cf wc-metaboxes">
		<?php
			if(isset($_GET['post']))
			{
				$i          = -1;
				$product_id = absint( wp_unslash( $_GET['post'] ) );
				$affData = \AnhDuong\Models\AffLink::where('product_id', $product_id)->first();
		        if(!empty($affData)){
		            $items = json_decode($affData->data);
		            foreach ( $items as $item ) {
		                $i++;
		                include __DIR__ . '/../templates/aff_tab_content_item.php';
		            }
		        }
			}
		?>
	</div>
	<div class="toolbar">
		<span class="expand-close">
			<a href="#" class="expand_all">
				<?php esc_html_e( 'Expand', 'woocommerce' ); ?></a> / <a href="#" class="close_all"><?php esc_html_e( 'Close', 'woocommerce' ); ?>
			</a>
		</span>
		<button type="button" class="button save_aff_cf button-primary"><?php esc_html_e( 'Save attributes', 'woocommerce' ); ?></button>
	</div>
	<?php do_action( 'woocommerce_product_options_aff_cf' ); ?>
</div>
