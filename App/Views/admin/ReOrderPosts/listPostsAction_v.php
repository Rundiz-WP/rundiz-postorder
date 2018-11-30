<div class="wrap">
    <h1><?php _e('Re-order posts', 'rd-postorder'); ?></h1>


    <div class="form-result-placeholder"></div>
    <form id="re-order-posts-form" method="get">
        <input type="hidden" name="page" value="rd-postorder_reorder-posts">
        <?php 
        if (isset($PostsListTable) && is_object($PostsListTable) && method_exists($PostsListTable, 'display')) {
            $PostsListTable->display();
        }
        ?> 
    </form>
</div>


<script>
    var ajaxnonce = '<?php echo wp_create_nonce('rdPostOrderReOrderPostsAjaxNonce'); ?>';
    var ajaxnonce_error_message = '<?php _e('Please reload this page and try again.', 'rd-postorder'); ?>';
    var confirm_txt = '<?php _e('Are you sure?', 'rd-postorder'); ?>';
    var confirm_reorder_all = '<?php _e('Are you sure to doing this? (This may slow down your server if you have too many posts.)', 'rd-postorder'); ?>';
    var dismiss_notice_message = '<?php _e('Dismiss this notice.'); ?>';
</script>