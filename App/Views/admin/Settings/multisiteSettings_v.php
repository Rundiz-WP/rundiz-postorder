<?php
/**
 * Multi site settings views file.
 * 
 * @package rundiz-postorder
 */
?>
<div class="wrap">
    <h1><?php esc_html_e('Rundiz PostOrder settings', 'rundiz-postorder'); ?></h1>
    <p><?php esc_html_e('This settings page is for manage all sites.', 'rundiz-postorder'); ?></p>

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

        <p class="txt-danger txt-white-box"><?php esc_html_e('Warning! This will be affect on all sites.', 'rundiz-postorder'); ?></p>
        <p>
            <button class="button button-danger" type="submit" name="btn-act" value="reset-menu-order-to-original"><?php esc_html_e('Reset post orders to their original value.', 'rundiz-postorder'); ?></button>
            <button class="button button-danger" type="submit" name="btn-act" value="reset-menu-order-to-zero"><?php esc_html_e('Reset post orders to zero. (WordPress default.)', 'rundiz-postorder'); ?></button>
        </p>
    </form>
</div>