<div class="wrap">
    <h1><?php _e('Rundiz PostOrder settings', 'rd-postorder'); ?></h1>
    <p><?php _e('This settings page is for manage all sites.', 'rd-postorder'); ?></p>

    <?php if (isset($form_result_class) && isset($form_result_msg)) { ?> 
    <div class="<?php echo $form_result_class; ?> notice is-dismissible">
        <p>
            <strong><?php echo $form_result_msg; ?></strong>
        </p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.'); ?></span></button>
    </div>
    <?php } ?> 

    <form id="rd-postorder-settings-form" method="post">
        <?php wp_nonce_field(); ?> 

        <p class="txt-danger txt-white-box"><?php _e('Warning! This will be affect on all sites.', 'rd-postorder'); ?></p>
        <p>
            <button class="button button-danger" type="submit" name="btn-act" value="reset-menu-order-to-original"><?php _e('Reset post orders to their original value.', 'rd-postorder'); ?></button>
            <button class="button button-danger" type="submit" name="btn-act" value="reset-menu-order-to-zero"><?php _e('Reset post orders to zero. (WordPress default.)', 'rd-postorder'); ?></button>
        </p>
    </form>
</div>