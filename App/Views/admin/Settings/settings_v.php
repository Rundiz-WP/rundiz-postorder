<?php
/**
 * Settings views file.
 * 
 * @package rundiz-postorder
 * 
 * phpcs:disable Generic.WhiteSpace.ScopeIndent.Incorrect, Generic.WhiteSpace.ScopeIndent.IncorrectExact, Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace
 */
?>
<div class="wrap">
    <h1><?php esc_html_e('Rundiz PostOrder settings', 'rundiz-postorder'); ?></h1>

    <?php if (isset($form_result_class) && isset($form_result_msg)) { ?> 
    <div class="<?php echo esc_attr($form_result_class); ?> notice is-dismissible">
        <p>
            <strong><?php echo wp_kses_post($form_result_msg); ?></strong>
        </p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php esc_html_e('Dismiss this notice.', 'rundiz-postorder'); ?></span></button>
    </div>
    <?php } ?> 

    <form id="rundiz-postorder-settings-form" method="post">
        <?php wp_nonce_field(); ?> 

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php esc_html_e('Disable on front pages', 'rundiz-postorder'); ?></th>
                    <td>
                        <label for="disable_customorder_frontpage">
                            <input id="disable_customorder_frontpage" type="checkbox" name="disable_customorder_frontpage" value="1"<?php checked((isset($options['disable_customorder_frontpage']) ? $options['disable_customorder_frontpage'] : null), '1'); ?>>
                            <?php esc_html_e('Disable custom post order on front pages.', 'rundiz-postorder'); ?> 
                        </label>
                        <p class="description"><?php 
                            esc_html_e('This included front or home page.', 'rundiz-postorder');
                        ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Disable on categories', 'rundiz-postorder'); ?></th>
                    <td>
                        <p><?php esc_html_e('Please tick on the check box of category you want to disable custom post order.', 'rundiz-postorder'); ?></p>
                        <fieldset>
                            <legend class="screen-reader-text"><?php esc_html_e('Disable on categories', 'rundiz-postorder'); ?></legend>
                            <?php
                            if (isset($categories) && is_array($categories) && !empty($categories)) { 
                                foreach ($categories as $rundiz_postorder_category_id => $rundiz_postorder_category_name) {
                                    if (
                                        isset($options['disable_customorder_categories']) && 
                                        is_array($options['disable_customorder_categories']) &&
                                        in_array($rundiz_postorder_category_id, $options['disable_customorder_categories']) // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
                                    ) {
                                        $rundiz_postorder_checked = ' checked="checked"';
                                    }
                            ?> 
                            <label>
                                <input type="checkbox" name="disable_customorder_categories[]" value="<?php echo esc_attr($rundiz_postorder_category_id); ?>"<?php if (isset($rundiz_postorder_checked)) {echo esc_attr($rundiz_postorder_checked);} ?>>
                                <?php echo esc_html($rundiz_postorder_category_name); ?> 
                            </label>
                            <br>
                            <?php
                                    unset($rundiz_postorder_checked);
                                }// endforeach;
                                unset($rundiz_postorder_category_id, $rundiz_postorder_category_name);
                            }// endif $categories
                            unset($categories);
                            ?> 
                        </fieldset>
                        <p class="description"><?php 
                            esc_html_e('This setting affect on both admin and front, or home pages but admin or front pages custom post order must be enabled.', 'rundiz-postorder');
                        ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Disable on admin pages', 'rundiz-postorder'); ?></th>
                    <td>
                        <label for="disable_customorder_adminpage">
                            <input id="disable_customorder_adminpage" type="checkbox" name="disable_customorder_adminpage" value="1"<?php checked((isset($options['disable_customorder_adminpage']) ? $options['disable_customorder_adminpage'] : null), '1'); ?>>
                            <?php esc_html_e('Disable custom post order on admin pages.', 'rundiz-postorder'); ?> 
                            <?php esc_html_e('Please note that this will not disable on admin re-order posts page.', 'rundiz-postorder'); ?> 
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?> 
        <p>
            <button class="button button-danger" type="submit" name="btn-act" value="reset-menu-order-to-original"><?php esc_html_e('Reset post orders to their original value.', 'rundiz-postorder'); ?></button>
            <button class="button button-danger" type="submit" name="btn-act" value="reset-menu-order-to-zero"><?php esc_html_e('Reset post orders to zero. (WordPress default.)', 'rundiz-postorder'); ?></button>
        </p>
    </form>
</div>