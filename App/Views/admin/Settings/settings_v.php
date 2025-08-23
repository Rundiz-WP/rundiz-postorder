<div class="wrap">
    <h1><?php _e('Rundiz PostOrder settings', 'rd-postorder'); ?></h1>

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

        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e('Disable on front pages', 'rd-postorder'); ?></th>
                    <td>
                        <label for="disable_customorder_frontpage">
                            <input id="disable_customorder_frontpage" type="checkbox" name="disable_customorder_frontpage" value="1"<?php checked((isset($options['disable_customorder_frontpage']) ? $options['disable_customorder_frontpage'] : null), '1'); ?>>
                            <?php _e('Disable custom post order on front pages.', 'rd-postorder'); ?> 
                        </label>
                        <p class="description"><?php 
                            _e('This included front or home page.', 'rd-postorder');
                        ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Disable on categories', 'rd-postorder'); ?></th>
                    <td>
                        <p><?php _e('Please tick on the check box of category you want to disable custom post order.', 'rd-postorder'); ?></p>
                        <fieldset>
                            <legend class="screen-reader-text"><?php _e('Disable on categories', 'rd-postorder'); ?></legend>
                            <?php
                            if (isset($categories) && is_array($categories) && !empty($categories)) { 
                                foreach ($categories as $id => $name) {
                                    if (
                                        isset($options['disable_customorder_categories']) && 
                                        is_array($options['disable_customorder_categories']) &&
                                        in_array($id, $options['disable_customorder_categories'])
                                    ) {
                                        $checked = ' checked="checked"';
                                    }
                            ?> 
                            <label>
                                <input type="checkbox" name="disable_customorder_categories[]" value="<?php echo $id; ?>"<?php if (isset($checked)) {echo $checked;} ?>>
                                <?php echo $name; ?> 
                            </label>
                            <br>
                            <?php
                                    unset($checked);
                                }// endforeach;
                                unset($id, $name);
                            }// endif $categories
                            unset($categories);
                            ?> 
                        </fieldset>
                        <p class="description"><?php 
                            _e('This setting affect on both admin and front, or home pages but admin or front pages custom post order must be enabled.', 'rd-postorder');
                        ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Disable on admin pages', 'rd-postorder'); ?></th>
                    <td>
                        <label for="disable_customorder_adminpage">
                            <input id="disable_customorder_adminpage" type="checkbox" name="disable_customorder_adminpage" value="1"<?php checked((isset($options['disable_customorder_adminpage']) ? $options['disable_customorder_adminpage'] : null), '1'); ?>>
                            <?php _e('Disable custom post order on admin pages.', 'rd-postorder'); ?> 
                            <?php _e('Please note that this will not disable on admin re-order posts page.', 'rd-postorder'); ?> 
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?> 
        <p>
            <button class="button button-danger" type="submit" name="btn-act" value="reset-menu-order-to-original"><?php _e('Reset post orders to their original value.', 'rd-postorder'); ?></button>
            <button class="button button-danger" type="submit" name="btn-act" value="reset-menu-order-to-zero"><?php _e('Reset post orders to zero. (WordPress default.)', 'rd-postorder'); ?></button>
        </p>
    </form>
</div>