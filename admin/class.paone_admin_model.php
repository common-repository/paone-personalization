<?php
/**
 * Created by PhpStorm.
 * User: pawanpriyadarshi
 * Date: 2020-03-18
 * Time: 03:01
 */
if (!class_exists("Paone_Admin_model")) {
    class Paone_Admin_model
    {
        /*
         * Function to save the Admin field as Option
         */
        protected function savePaonePersonalizationAdminFields()
        {

            $data = array();
            if (isset($_FILES['sample_image']['name']) && $_FILES['sample_image']['name'] != '') {
                add_filter('upload_dir', 'paone_personalization_upload_dir');

                add_filter('sanitize_file_name', 'paone_hash_filename', 10);
                $uploaded_file = $_FILES['sample_image'];
                $filename = sanitize_file_name($_FILES['sample_image']['name']);
                $file_type = wp_check_filetype_and_ext($uploaded_file['tmp_name'], $filename);
                $upload_overrides = array('test_form' => false);
                if (!wp_match_mime_types('image', $file_type['type'])) {
                    echo('<div class="paone_error">The uploaded file is not a valid image. Please try again.</div>');
                    return;
                }
                $movefile = wp_handle_upload($uploaded_file, $upload_overrides);
                if ($movefile && !isset($movefile['error'])) {
                    $imageurl = $movefile['url'];
                    $data['sample_image'] = sanitize_text_field($imageurl);

                } else {
                    echo "<div class='error notice'>" . $movefile['error'] . "</div>";
                }
                // Set everything back to normal.
                remove_filter('sanitize_file_name', 'paone_hash_filename', 10);
                remove_filter('upload_dir', 'paone_personalization_upload_dir');
            }
            if (!isset($data['sample_image'])) {
                $old_data = get_option('paon_wc_pn_data');

                $data['sample_image'] = esc_attr($old_data['sample_image']);
            }
            $data['name'] = sanitize_text_field($_POST['name']);
            $post_fonts = isset($_POST['fonts']) ? wp_unslash($_POST['fonts']) : array('Name' => array());
            $fonts = sanitize_text_field(implode("|", $post_fonts["Name"]));
            $data['fonts'] = $fonts;
            $post_colors = isset($_POST['colors']) ? wp_unslash($_POST['colors']) : array('Code' => array(), 'Name' => array());
            $colors_names = sanitize_text_field(implode("|", $post_colors['Name']));
            $colors_codes = sanitize_text_field(implode("|", $post_colors['Code']));
            $data['color_names'] = $colors_names;
            $data['color_codes'] = $colors_codes;
            $data['max_characters'] = sanitize_text_field($_POST['max_characters']);
            $data['sample_image_label'] = sanitize_text_field($_POST['sample_image_label']);
            if ($_POST['product_categories']) {
                $data['blocked_product_categories'] = sanitize_text_field(implode(',', $_POST['product_categories']));
            } else {
                $data['blocked_product_categories'] = 0;
            }
            $data['product_skus'] = sanitize_text_field($_POST['product_skus']);
            update_option('paon_wc_pn_data', $data);
        }

        /*
         * Function to fetch the saved admin fields used in the form template
         */
        protected function getPaonePersonalizationAdminFields()
        {
            $admin_data = array();
            $data = get_option('paon_wc_pn_data');
            $args = array(
                'taxonomy' => 'product_cat',
                'parent' => 0, // get top level categories
                'orderby' => 'modified',
                'order' => 'ASC',
                'hierarchical' => 1,
                'pad_counts' => 0,
                'hide_empty' => 0
            );
            $product_categories = array();
            $categories = get_categories($args);
            foreach ($categories as $cat) {
                $product_categories[] = array('term_id' => $cat->term_id, 'name' => $cat->name, 'slug' => $cat->slug);
            }
            $admin_data['all_categories'] = $product_categories;
            if (!empty($data)) {
                $admin_data['name'] = esc_attr($data['name'], '');
                $fonts = esc_attr($data['fonts'], '');
                $colors_names = esc_attr($data['color_names'], '');
                $colors_codes = esc_attr($data['color_codes'], '');
                $admin_data['max_characters'] = esc_attr($data['max_characters'], 20);
                $admin_data['sample_image'] = esc_attr($data['sample_image']);
                $blocked_categories = esc_attr($data['blocked_product_categories']);
                $admin_data['product_skus'] = esc_attr($data['product_skus'], '');
                $admin_data['sample_image_label'] = esc_attr($data['sample_image_label'], 'Sample Image');

            }
            $colors = array();
            if (!empty($colors_codes)) {
                $clr_codes = explode("|", $colors_codes);
                $clr_names = explode("|", $colors_names);
                if (is_array($clr_codes) && is_array($clr_names) && count($colors_codes) == count($colors_names)) {
                    for ($i = 0; $i < count($clr_codes); $i++) {
                        $colors[] = array("Name" => $clr_names[$i], 'Code' => $clr_codes[$i]);
                    }
                }
            }
            $admin_data['colors'] = $colors;
            $fonts_data = array();
            if (!empty($fonts)) {
                $fnts = explode("|", $fonts);
                if (is_array($fnts)) {
                    foreach ($fnts as $font) {
                        $fonts_data[] = array("Name" => $font);
                    }
                }
            }
            $admin_data['fonts'] = $fonts_data;
            if (!empty($blocked_categories)) {
                $admin_data['blocked_categories'] = explode(",", $blocked_categories);
            } else {
                $admin_data['blocked_categories'] = array();
            }
            return $admin_data;
        }
    }

    function paone_personalization_upload_dir($dir)
    {
        return array(
                'path' => $dir['basedir'] . '/paone_personalization',
                'url' => $dir['baseurl'] . '/paone_personalization',
                'subdir' => '/paone_personalization',
            ) + $dir;
    }

    function paone_hash_filename($filename)
    {
        $info = pathinfo($filename);
        $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
        $path = wp_upload_dir()['path'] . '/sample_image' . $ext;
        if (file_exists($path)) {
            unlink($path);
        }
        return 'sample_image' . $ext;
    }


    function paone_custom_mime_types($mimes)
    {
        $mimes['jpg'] = 'image/jpeg';
        $mimes['png'] = 'image/png';
        unset($mimes['exe']);

        return $mimes;
    }

    add_filter('upload_mimes', 'paone_custom_mime_types');
}
