<?php
/**
 * Plugin Name: Affiliate custom link
 * Plugin URI: https://anhduong.us
 * Description: A plugin, to help make a custom link for aff
 * Version: 1.0
 * Author: Ánh Dương
 * Author URI: https://anhduong.us
 */

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

    include(__DIR__ . '/src/templates/aff_tab_content.php');

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


// /**
//  * Save the custom fields.
//  */
// function save_giftcard_option_fields( $post_id ) {

//     $allow_personal_message = isset( $_POST['_allow_personal_message'] ) ? 'yes' : 'no';
//     update_post_meta( $post_id, '_allow_personal_message', $allow_personal_message );

//     if ( isset( $_POST['_valid_for_days'] ) ) :
//         update_post_meta( $post_id, '_valid_for_days', absint( $_POST['_valid_for_days'] ) );
//     endif;

// }
// add_action( 'woocommerce_process_product_meta', 'save_giftcard_option_fields'  );

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

    $i             = absint( $_POST['i'] );
    $metabox_class = array();

    include __DIR__ . '/src/templates/aff_tab_content_item.php';
    wp_die();
}

add_action('wp_ajax_woocommerce_save_aff', 'woocommerce_save_aff');

function woocommerce_save_aff()
{
    check_ajax_referer( 'save_aff_nonce', 'security' );

    if ( ! current_user_can( 'edit_products' ) || ! isset( $_POST['data'], $_POST['post_id'] ) ) {
        wp_die( -1 );
    }

    $response = array();

    try {
        parse_str( wp_unslash( $_POST['data'] ), $data ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

        $attributes   = WC_Meta_Box_Product_Data::prepare_attributes( $data );
        $product_id   = absint( wp_unslash( $_POST['post_id'] ) );
        $product_type = ! empty( $_POST['product_type'] ) ? wc_clean( wp_unslash( $_POST['product_type'] ) ) : 'simple';
        $classname    = WC_Product_Factory::get_product_classname( $product_id, $product_type );
        $product      = new $classname( $product_id );

        $product->set_attributes( $attributes );
        $product->save();

        ob_start();
        $attributes = $product->get_attributes( 'edit' );
        $i          = -1;
        if ( ! empty( $data['attribute_names'] ) ) {
            foreach ( $data['attribute_names'] as $attribute_name ) {
                $attribute = isset( $attributes[ sanitize_title( $attribute_name ) ] ) ? $attributes[ sanitize_title( $attribute_name ) ] : false;
                if ( ! $attribute ) {
                    continue;
                }
                $i++;
                $metabox_class = array();

                if ( $attribute->is_taxonomy() ) {
                    $metabox_class[] = 'taxonomy';
                    $metabox_class[] = $attribute->get_name();
                }

                include __DIR__ . '/admin/meta-boxes/views/html-product-attribute.php';
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
// function prepare_affs( $data = false ) {
//     $affs = array();

//     if ( ! $data ) {
//         $data = stripslashes_deep( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
//     }

//     if ( isset( $data['aff_titles'], $data['attribute_values'] ) ) {
//         $attribute_names         = $data['attribute_names'];
//         $attribute_values        = $data['attribute_values'];
//         $attribute_visibility    = isset( $data['attribute_visibility'] ) ? $data['attribute_visibility'] : array();
//         $attribute_variation     = isset( $data['attribute_variation'] ) ? $data['attribute_variation'] : array();
//         $attribute_position      = $data['attribute_position'];
//         $attribute_names_max_key = max( array_keys( $attribute_names ) );

//         for ( $i = 0; $i <= $attribute_names_max_key; $i++ ) {
//             if ( empty( $attribute_names[ $i ] ) || ! isset( $attribute_values[ $i ] ) ) {
//                 continue;
//             }
//             $attribute_id   = 0;
//             $attribute_name = wc_clean( esc_html( $attribute_names[ $i ] ) );

//             if ( 'pa_' === substr( $attribute_name, 0, 3 ) ) {
//                 $attribute_id = wc_attribute_taxonomy_id_by_name( $attribute_name );
//             }

//             $options = isset( $attribute_values[ $i ] ) ? $attribute_values[ $i ] : '';

//             if ( is_array( $options ) ) {
//                 // Term ids sent as array.
//                 $options = wp_parse_id_list( $options );
//             } else {
//                 // Terms or text sent in textarea.
//                 $options = 0 < $attribute_id ? wc_sanitize_textarea( esc_html( wc_sanitize_term_text_based( $options ) ) ) : wc_sanitize_textarea( esc_html( $options ) );
//                 $options = wc_get_text_affs( $options );
//             }

//             if ( empty( $options ) ) {
//                 continue;
//             }

//             $attribute = new WC_Product_Attribute();
//             $attribute->set_id( $attribute_id );
//             $attribute->set_name( $attribute_name );
//             $attribute->set_options( $options );
//             $attribute->set_position( $attribute_position[ $i ] );
//             $attribute->set_visible( isset( $attribute_visibility[ $i ] ) );
//             $attribute->set_variation( isset( $attribute_variation[ $i ] ) );
//             $affs[] = apply_filters( 'woocommerce_admin_meta_boxes_prepare_attribute', $attribute, $data, $i );
//         }
//     }
//     return $attributes;
// }