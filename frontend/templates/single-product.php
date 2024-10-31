<?php

/**
 * Created by PhpStorm.
 * User: pawanpriyadarshi
 * Date: 2020-01-11
 * Time: 17:36
 */
class PAONE_single_product extends PAONE_Frontend
{
    private $settings;

    function __construct($settings)
    {
        $this->settings = $settings;
        $this->paone_wc_pn_checkbox();

    }


    /*
     * Display the checkbox on the single product page
     */

    function paone_wc_pn_checkbox()
    {

        $this->paone_get_personalization_area();

    }

    function paone_get_personalization_area()
    {
        global $product;
        $sku = $product->get_sku();
        $product_id=$product->get_id();
        $config_fields = $this->settings;
        $blocked_skus = explode(",", esc_attr($config_fields['product_skus']));
        $blocked_term_ids = explode(",", esc_attr($config_fields['blocked_product_categories']));
        if (in_array($sku, $blocked_skus)) return;
        $terms = get_the_terms($product->get_id(), 'product_cat');
        if (isset($terms) && !empty($terms)) {
            foreach ($terms as $term) {
                if (in_array($term->term_id, $blocked_term_ids)) return;
            }

        }
        $fonts = esc_attr($config_fields['fonts'], '');
        $colors_names = esc_attr($config_fields['color_names'], '');
        $colors_codes = esc_attr($config_fields['color_codes'], '');
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
        $fonts_data = array();
        if (!empty($fonts)) {
            $fnts = explode("|", $fonts);
            if (is_array($fnts)) {
                foreach ($fnts as $font) {
                    $fonts_data[] = array("Name" => $font);
                }
            }
        }

        ?>
        <div class="personalization_area">
            <div class="personalization_checkbox">
                <label class="switch">
                    <input type="checkbox" class="personalization_checkbox_input" name="personalization_checkbox_input"
                           id="personalization_checkbox_input">
                    <span class="slider round"></span>
                </label>
                <span class="personalization_checkbox_text">
                <?php _e('Personalize', PAONE_PERSONALIZATION_LANG); ?>
                </span>
            </div>
            <div class="personalization_options">
                <?php if (isset($config_fields['sample_image']) && !empty($config_fields['sample_image'])) {
                    ?>
                    <div class="personalization_sample_image">
                        <label><?php esc_attr($config_fields['sample_image_label'] ? _e(esc_attr($config_fields['sample_image_label']),PAONE_PERSONALIZATION_LANG) : 'Sample Image', PAONE_PERSONALIZATION_LANG) ?></label>
                        <img src="<?php echo esc_attr($config_fields['sample_image']); ?>">
                    </div>
                    <?php
                }
                ?>
                <div class="clear">&nbsp;</div>
                <table class="table">

                    <tr>
                        <th><?php _e(esc_attr($config_fields['name']), PAONE_PERSONALIZATION_LANG); ?></th>
                        <td>
                            <input type="hidden" name="data_product_id" id="data_product_id" value="<?php echo $product_id;?>"/>
                            <input type="text" name="printed_name" id="printed_name-<?php echo get_the_ID(); ?>"
                                   class="printed_name"
                                   placeholder="<?php _e(esc_attr($config_fields['name']), PAONE_PERSONALIZATION_LANG); ?>"
                                   maxlength="<?php echo esc_attr($config_fields['max_characters']); ?>" min="1">
                        </td>
                    </tr>
                    <?php
                    if (!empty($config_fields['fonts'])) {

                        ?>
                        <tr>

                            <th><?php _e('Font', PAONE_PERSONALIZATION_LANG); ?></th>
                            <td>
                                <select id="font-<?php echo get_the_ID(); ?>" name="font" class="font">
                                    <?php
                                    foreach ($fonts_data as $font) {

                                        if (!empty($font['Name']))
                                            echo '<option value="' . $font['Name'] . '">' . $font['Name'] . '</option>';

                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                    }
                    if (!empty($config_fields['color_names'])) {
                        ?>
                        <tr>
                            <th><?php _e('Font Color', PAONE_PERSONALIZATION_LANG); ?></th>
                            <td>
                                <select id="font_color-<?php echo get_the_ID(); ?>" name="font_color" class="font_color"
                                        style="width: 100%">
                                    <?php
                                    foreach ($colors as $color) {
                                        if (!empty($color['Name'] && !empty($color['Code'])))
                                            echo '<option value="' . $color['Name'] . '" id="' . $color['Code'] . '">' . $color['Name'] . '</option>';
                                    }
                                    ?>

                                </select>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2">
                            <button type="button" class="btn_personalize" id="btn-person-<?php echo get_the_ID(); ?>">

                                <?php _e('Personalize', PAONE_PERSONALIZATION_LANG); ?>
                            </button>
                            <button type="button" class="btn_personalize_remove"
                                    id="btn-person-remove-<?php echo get_the_ID(); ?>">
                                <?php _e('Cancel', PAONE_PERSONALIZATION_LANG); ?>
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="personalization_selected"></div>
        </div>
        <?php

    }

}


?>