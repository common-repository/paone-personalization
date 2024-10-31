<?php
/**
 * Created by PhpStorm.
 * User: pawanpriyadarshi
 * Date: 2020-01-09
 * Time: 15:51
 */
if (!class_exists('PAONE_Frontend')) {
    class PAONE_Frontend
    {

        private $settings;

        public function __construct()
        {
            //Add Actions
            add_action('paone_enqueue_scripts_frontend', array($this, 'enqueue_scripts_frontend'));
            add_action('woocommerce_before_add_to_cart_button', array($this, 'display_fields'));

            //Get the Admin settings
            $this->settings = get_option('paon_wc_pn_data');
            //Do Actions
//            do_action('woocommerce_single_product_summary');
            do_action('paone_enqueue_scripts_frontend');

        }

        function display_fields()
        {
            require_once PAONE_WP_PATH . '/frontend/templates/single-product.php';
            new PAONE_single_product($this->settings);
        }

        function enqueue_scripts_frontend()
        {
            wp_enqueue_style('', PAONE_WP_URL . '/assets/css/style.css');
            wp_enqueue_style('select2-css', PAONE_WP_URL . '/assets/select2/css/select2.min.css');
            wp_enqueue_style('paone-css', PAONE_WP_URL . '/assets/css/style.css');
            wp_enqueue_script('select2-js', PAONE_WP_URL . '/assets/select2/js/select2.min.js', array('jquery'));
            wp_enqueue_script('paone-js', PAONE_WP_URL . '/assets/js/script.js', array('jquery'));
        }

    }
}