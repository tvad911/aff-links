<?php
/**
 * Plugin Name: Affiliate custom link
 * Plugin URI: https://anhduong.us
 * Description: A plugin, to help make a custom link for aff
 * Version: 1.0
 * Author: Ánh Dương
 * Author URI: https://anhduong.us
 */
// Load autoload vendor
require 'vendor/autoload.php';

use AnhDuong\Models\AffLink;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Add a custom product tab.
 */
function custom_product_tabs( $tabs) {

    $tabs['aff_tab'] = array(
        'label'     => __( 'Affiliate tab', 'woocommerce' ),
        'target'    => 'product_aff_cf',
        'class'     => array( 'show_if_simple', 'show_if_variable'  ),
    );

    return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'custom_product_tabs' );

/**
 * Contents of the affiliate options product tab.
 */
function aff_options_product_tab_content() {

    global $post;

    // Note the 'id' attribute needs to match the 'target' parameter set above

    include(__DIR__ . '/templates/aff_tab_content.php');

}
add_filter( 'woocommerce_product_data_panels', 'aff_options_product_tab_content' ); // WC 2.6 and up

add_action( 'admin_enqueue_scripts', 'add_admin_style' );

function add_admin_style($hook) {
    global $pagenow;
    global $post_type;

    if(('post-new.php' == $pagenow || 'post.php' == $pagenow) && $post_type == 'product' ) {
        wp_enqueue_style( 'aff_tab_woo', plugin_dir_url( __FILE__ ) . '/public/main.css', false, '1.0.0' );
        wp_enqueue_script('aff_tab_woo', plugin_dir_url(__FILE__) . '/public/main.js');

        wp_localize_script('aff_tab_woo', 'aff_tab_var', array(
            'add_aff_nonce'    => wp_create_nonce('add_aff_nonce'),
            'save_aff_nonce'   => wp_create_nonce('save_aff_nonce'),
            'remove_aff' => __( 'Remove this aff?', 'woocommerce' ),
        ));
    }
}

/**
 * Ajax save aff data
 */

add_action('wp_ajax_woocommerce_add_aff', 'woocommerce_add_aff');

function woocommerce_add_aff()
{
    ob_start();

    check_ajax_referer( 'add_aff_nonce', 'security' );

    if ( ! current_user_can( 'edit_products' ) || ! isset( $_POST['i'] ) ) {
        wp_die( -1 );
    }

    $i = absint( $_POST['i'] );

    include __DIR__ . '/templates/aff_tab_content_item.php';
    wp_die();
}

add_action('wp_ajax_woocommerce_save_aff', 'woocommerce_save_aff');

function woocommerce_save_aff()
{
    check_ajax_referer( 'save_aff_nonce', 'security' );

    if ( !current_user_can( 'edit_products' ) || !isset( $_POST['data'], $_POST['post_id'] ) ) {
        wp_die( -1 );
    }

    $response = array();

    try {
        parse_str( wp_unslash( $_POST['data'] ), $data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        $product_id = absint( wp_unslash( $_POST['post_id'] ) );
        $affs       = save_affs( $data , $product_id);

        ob_start();
        $i          = -1;
        $affData = AffLink::where('product_id', $product_id)->first();
        if(!empty($affData)){
            $items = json_decode($affData->data);
            foreach($items as $item ) {
                $i++;
                include __DIR__ . '/templates/aff_tab_content_item.php';
            }
        }

        $response['html'] = ob_get_clean();
    } catch ( Exception $e ) {
        wp_send_json_error( array( 'error' => $e->getMessage() ) );
    }

    // wp_send_json_success must be outside the try block not to break phpunit tests.
    wp_send_json_success( $response );
}

/**
 * Prepare affs for save.
 *
 * @param array $data Attribute data.
 *
 * @return array
 */
function save_affs( $data = false, int $product_id) {

    if($product_id == 0 || empty($product_id))
        return false;

    $affs = array();

    if ( ! $data ) {
        $data = stripslashes_deep( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
    }

    if(isset($data['aff_title'], $data['aff_link'])) {

        $aff_title         = $data['aff_title'];
        $aff_type          = $data['aff_type'];
        $aff_link          = $data['aff_link'];
        $aff_price         = isset( $data['aff_price'] ) ? $data['aff_price'] : 0;
        $aff_position      = $data['aff_position'];
        $aff_title_max_key = max( array_keys( $aff_title ) );

        for($i = 0; $i <= $aff_title_max_key; $i++) {
            if(empty( $aff_title[$i]) || !isset($aff_link[$i]) ) {
                continue;
            }

            $title    = wc_clean(wp_unslash($aff_title[$i]));
            $type     = wc_clean(wp_unslash($aff_type[$i]));
            $link     = esc_url_raw(wp_unslash($aff_link[$i]));
            $price    = wc_clean(wp_unslash($aff_price[$i]));
            $position = absint(wc_clean(wp_unslash($aff_position[$i])));

            $affJson = collect([
                'title'    => $title,
                'type'     => $type,
                'link'     => $link,
                'price'    => $price,
                'position' => $position,
                'visible'  => true,
            ]);

            array_push($affs, $affJson);
        }

        $affData = AffLink::where('product_id', $product_id)->first();
        if(empty($affData)){
            $affData = new AffLink();
        }

        $affData->product_id = $product_id;
        $affData->data = json_encode($affs);
        $affData->save();
    }

    return true;
}

/**
 * Prepare attributes for save.
 *
 * @param array $data Attribute data.
 *
 * @return array
 */
function prepare_aff( $data = false, $product_id ) {
    if($product_id == 0 || empty($product_id))
        return false;

    $affs = array();

    if ( ! $data ) {
        $data = stripslashes_deep( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
    }

    if(isset($data['aff_title'], $data['aff_link'])) {

        $aff_title         = $data['aff_title'];
        $aff_type          = $data['aff_type'];
        $aff_link          = $data['aff_link'];
        $aff_price         = isset( $data['aff_price'] ) ? $data['aff_price'] : 0;
        $aff_position      = $data['aff_position'];
        $aff_title_max_key = max( array_keys( $aff_title ) );

        for($i = 0; $i <= $aff_title_max_key; $i++) {
            if(empty( $aff_title[$i]) || !isset($aff_link[$i]) ) {
                continue;
            }

            $title    = wc_clean(wp_unslash($aff_title[$i]));
            $type     = wc_clean(wp_unslash($aff_type[$i]));
            $link     = esc_url_raw(wp_unslash($aff_link[$i]));
            $price    = wc_clean(wp_unslash($aff_price[$i]));
            $position = absint(wc_clean(wp_unslash($aff_position[$i])));

            $affJson = collect([
                'title'    => $title,
                'type'     => $type,
                'link'     => $link,
                'price'    => $price,
                'position' => $position,
                'visible'  => true,
            ]);

            array_push($affs, $affJson);
        }

        $affData = AffLink::where('product_id', $product_id)->first();
        if(empty($affData)){
            $affData = new AffLink();
        }

        $affData->product_id = $product_id;
        $affData->data = json_encode($affs);
        $affData->save();
    }

    return true;
}

function saveAffs( $product_id) {
    prepare_aff( $data = false, $product_id);
}
add_action( 'woocommerce_process_product_meta_simple', 'saveAffs', 10, 1 );
add_action( 'woocommerce_process_product_meta_grouped', 'saveAffs', 10, 1 );
add_action( 'woocommerce_process_product_meta_variable', 'saveAffs', 10, 1 );
add_action( 'woocommerce_process_product_meta_external', 'saveAffs', 10, 1 );

add_shortcode('affiliate-links', 'affiliateLink');

function affiliateLink() {
    global $post;

    $product_id = $post->ID;

    ob_start();
    $affData = AffLink::where('product_id', $product_id)->first();
    if(!empty($affData)){
        $items = json_decode($affData->data);

        include __DIR__ . '/templates/affiliate-links.php';
    }

    $html = ob_get_clean();

    echo $html;
}