<?php
/**
 * @package paone-personalization
 */
/*
Plugin Name: Product Personalization for WC
Plugin URI: https://endroit.in/paone-personalization
Description: Quickly add personalization fields like name, font color, font name on your woocommerce shop.
Version: 1.0.0
Author: Pawan Priyadarshi
Author URI: https://endroit.in/pawan-priyadarshi
License: GPLv2 or later
Text Domain: paone-personalization
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2019-2020 Pawan Priyadarshi.
*/
if (!defined('ABSPATH')) {
    exit;
}
define('PAONE_WP_PATH', plugin_dir_path(__FILE__));
define('PAONE_WP_URL', plugins_url('', __FILE__));
define('PAONE_PERSONALIZATION_LANG', 'paone_');
/*
 * Registration Hooks
 */
register_activation_hook(__FILE__, 'PAONE_woocommerce_personalization_install');
register_deactivation_hook(__FILE__, 'PAONE_woocommerce_personalization_deactivate');
register_uninstall_hook(__FILE__, 'PAONE_woocommerce_personalization_uninstall');
/*
 * Add Admin menu
 */
add_action('admin_menu', 'PAONE_woocommerce_personalization_menu');
function PAONE_woocommerce_personalization_menu()
{
    add_menu_page('Woocommerce Personalization', 'Personalization', 'manage_options', 'PAONE_woocommerce_personalization', 'PAONE_woocommerce_personalization_init', plugin_dir_url(__FILE__) . 'images/personalization.png');
}

function PAONE_woocommerce_personalization_init()
{
    if (is_admin()) {
        require_once(PAONE_WP_PATH . 'admin/class.paone_admin_model.php');
        require_once(PAONE_WP_PATH . 'admin/class.paone_admin.php');
        new Paone_Admin();
    }
}

/*
 * Function to run insall scripts
 */
function PAONE_woocommerce_personalization_install()
{

}

/*
 * Function to run scripts on plugin deactivation
 */
function PAONE_woocommerce_personalization_deactivate()
{

}

/*
 * Function to run scripts on plugin uninstallation
 */
function PAONE_woocommerce_personalization_uninstall()
{

}

if (!is_admin()) {
    require_once(PAONE_WP_PATH . 'frontend/class.paone-frontend.php');
    require_once(PAONE_WP_PATH . 'cart/class.cart.php');
    new PAONE_Frontend();
}
//setting link
function paone_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=PAONE_woocommerce_personalization">' . __( 'Settings' ) . '</a>';


    array_push( $links, $settings_link );
    return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'paone_settings_link' );