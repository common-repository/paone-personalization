<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<?php

/**
 * Override the default upload path.
 *
 * @param   array $dir
 * @return  array
 */


if (isset($_POST['submit']) && wp_verify_nonce($_REQUEST[PAONE_PERSONALIZATION_LANG], 'paone_wc_admin_settings')) {
    $this->savePaonePersonalizationAdminFields();
}
$data=$this->getPaonePersonalizationAdminFields();

?>

<form action="" method="post" id="paone_wc_admin_setting" name="paone_wc_admin_setting"
      enctype="multipart/form-data">
    <?php wp_nonce_field('paone_wc_admin_settings', PAONE_PERSONALIZATION_LANG, false); ?>

    <p>
    <h2>
        <?php _e('Personalization Fields', PAONE_PERSONALIZATION_LANG); ?>
    </h2>
    </p>
    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row"><?php _e('Printed Name(eg. Enter Your Name)', PAONE_PERSONALIZATION_LANG); ?></th>
            <td>
                <input type="text" name="name" id="name" value="<?php echo $data['name']; ?>">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Fonts', PAONE_PERSONALIZATION_LANG); ?>
            </th>
            <td>
                <?php
                echo $this->tableInput("fonts", array("Name"),$data['fonts'] );
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Font Colors', PAONE_PERSONALIZATION_LANG); ?></th>
            <td>
                <?php
                echo $this->tableInput("colors", array("Code", "Name"), $data['colors'],array(),array('after_add_function'=>'jscolor.installByClassName(\'jscolor\')'));
                ?>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Maximum Number of characters', PAONE_PERSONALIZATION_LANG); ?></th>
            <td>
                <input type="number" id="max_characters" name="max_characters"
                       value="<?php echo $data['max_characters'] ? $data['max_characters'] : 20; ?>" min="1">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Disable on product categories ', PAONE_PERSONALIZATION_LANG); ?></th>
            <td>
                <select id="product_categories" name="product_categories[]" multiple="multiple">
                    <?php

                    foreach ($data['all_categories'] as $categories){
                        $selected="";
                        if(in_array($categories['term_id'],$data['blocked_categories'])){
                            $selected="selected";
                        }
                        echo '<option value="'.$categories['term_id'].'" '.$selected.'>'.$categories['name'].'</option>';
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Disable on products(Comma seperated SKUS) ', PAONE_PERSONALIZATION_LANG); ?></th>
            <td>
                <input id="product_skus" name="product_skus" value="<?php echo $data['product_skus'];?>">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Sample Image Label', PAONE_PERSONALIZATION_LANG); ?></th>
            <td>
                <input id="sample_image_label" name="sample_image_label" value="<?php echo $data['sample_image_label'];?>">
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e('Sample Image on Product Page', PAONE_PERSONALIZATION_LANG); ?></th>
            <td>
                <?php
                if (!empty($data['sample_image'])) {
                    echo '<div class="sample_image"><img src="' . esc_url($data['sample_image']) . '?e='.date('d-m-s').'" width="400"></div>';
                }
                ?>
                <input type="file" id="sample_image" name="sample_image">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button class="button button-primary" type="submit" name="submit"
                        value="submit"><?php _e('Save Options', PAONE_PERSONALIZATION_LANG) ?></button>
            </td>
        </tr>
</form>
