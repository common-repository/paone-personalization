<?php
/**
 * Created by PhpStorm.
 * User: pawanpriyadarshi
 * Date: 2020-02-13
 * Time: 01:26
 */
add_action('woocommerce_add_to_cart', 'paone_add_to_cart');
function paone_add_to_cart()
{

    $product_id = sanitize_text_field($_POST['product_id']);
    if(empty($product_id)){
        $product_id=sanitize_text_field($_POST['add-to-cart']);
    }
    if(!empty($product_id))
        return;
    $quantity = sanitize_text_field($_POST['quantity']);
    $found = false;
//check if product is in cart
    if (sizeof(WC()->cart->get_cart()) > 0) {
        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
            $_product = $values['data'];
            if ($_product->id == $product_id)
                $found = true;
        }
// if product not found
        if (!$found)
            WC()->cart->add_to_cart($product_id, $quantity);
    } else {
// if there is no products in cart
        WC()->cart->add_to_cart($product_id, $quantity);
    }

}

add_filter('woocommerce_add_cart_item_data', 'paone_add_item_data', 10, 3);

/**
 * Add custom data to Cart
 * @param  [type] $cart_item_data [description]
 * @param  [type] $product_id     [description]
 * @param  [type] $variation_id   [description]
 * @return [type]                 [description]
 */
function paone_add_item_data($cart_item_data, $product_id, $variation_id)
{
    if (isset($_REQUEST['printed_name'])) {
        $cart_item_data['printed_name'] = sanitize_text_field($_REQUEST['printed_name']);
    }
    if (isset($_REQUEST['font'])) {
        $cart_item_data['font'] = sanitize_text_field($_REQUEST['font']);
    }
    if (isset($_REQUEST['font_color'])) {
        $cart_item_data['font_color'] = sanitize_text_field($_REQUEST['font_color']);
    }

    return $cart_item_data;
}

add_filter('woocommerce_get_item_data', 'paone_add_item_meta', 10, 2);

/**
 * Display information as Meta on Cart page
 * @param  [type] $item_data [description]
 * @param  [type] $cart_item [description]
 * @return [type]            [description]
 */
function paone_add_item_meta($item_data, $cart_item)
{
    if (array_key_exists('printed_name', $cart_item)) {
        $printed_name = $cart_item['printed_name'];
        $font = $cart_item['font'];
        $font_color = $cart_item['font_color'];
        $data = get_option('paon_wc_pn_data');
        $printed_name_label = "Printed Name";
        foreach ($data as $key => $value) {
            if ($key == "name") $printed_name_label = $value;
        }
        $item_data[] = array(
            'key' => $printed_name_label,
            'value' => $printed_name
        );
        $item_data[] = array(
            'key' => 'Font',
            'value' => $font
        );
        $item_data[] = array(
            'key' => 'Font Color',
            'value' => $font_color
        );
    }

    return $item_data;
}

add_action('woocommerce_checkout_create_order_line_item', 'paone_add_custom_order_line_item_meta', 10, 4);
/*
 *
 */
function paone_add_custom_order_line_item_meta($item, $cart_item_key, $values, $order)
{

    if (array_key_exists('printed_name', $values)) {
        $item->add_meta_data('printed_name', $values['printed_name']);
        $item->add_meta_data('font', $values['font']);
        $item->add_meta_data('font_color', $values['font_color']);

    }

}