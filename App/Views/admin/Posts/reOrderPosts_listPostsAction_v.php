<?php
/**
 * Re-order posts list table.
 * 
 * @package rundiz-postorder
 */
?>
<div class="wrap">
    <h1><?php esc_html_e('Re-order posts', 'rundiz-postorder'); ?></h1>


    <div class="form-result-placeholder"></div>
    <form id="re-order-posts-form" method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr(RundizPostOrder\App\Controllers\Admin\Posts\ReOrderPosts::MENU_SLUG); ?>">
        <?php 
        if (isset($PostsListTable) && is_object($PostsListTable) && method_exists($PostsListTable, 'display')) {
            $PostsListTable->display();
        }
        ?> 
    </form>
</div>