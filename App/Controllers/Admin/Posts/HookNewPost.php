<?php
/**
 * Hook on create new post.
 * 
 * @package rundiz-postorder
 */


namespace RundizPostOrder\App\Controllers\Admin\Posts;


if (!class_exists('\\RundizPostOrder\\App\\Controlers\\Admin\\Posts\\HookNewPost')) {
    /**
     * HookNewPost class.
     */
    class HookNewPost implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        use \RundizPostOrder\App\AppTrait;


        /**
         * Admin users saving the post.
         * 
         * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_insert_post Reference.
         * @param int $post_id Post ID.
         * @param \WP_Post $post Post object.
         * @param bool $update Whether this is an existing post being updated.
         */
        public function hookInsertPostAction($post_id, $post, $update)
        {
            if (
                is_numeric($post_id)
                && is_object($post) 
                && isset($post->post_status) && in_array(strtolower($post->post_status), $this->allowed_order_post_status, true) 
                && isset($post->menu_order) && strval($post->menu_order) === '0' 
                && isset($post->post_type) && \RundizPostOrder\App\Models\PostOrder::POST_TYPE === $post->post_type 
            ) {
                // if this save is first time, whatever it status is.
                $PostOrder = new \RundizPostOrder\App\Models\PostOrder();
                $result = $PostOrder->setNewPostOrderNumber($post_id);
                unset($PostOrder);

                if (is_array($result)) {
                    $menu_order = $result['menu_order'];
                    $updated = $result['updated'];
                    $updatedScheduled = $result['updatedScheduled'];
                }
                unset($result);

                \RundizPostOrder\App\Libraries\Debug::writeLog(
                    'Debug: RundizPostOrder hookInsertPostAction() method was called. Admin is saving new post. The new `menu_order` value is ' . $menu_order . 
                        ' and the post `ID` is ' . $post_id . '.' .
                        ' updated: ' . var_export($updated, true) . '; updated scheduled posts: ' . var_export($updatedScheduled, true) . '.' // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
                );
                unset($menu_order, $updated, $updatedScheduled);
            }
        }// hookInsertPostAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            add_action('wp_insert_post', [$this, 'hookInsertPostAction'], 10, 3);
        }// registerHooks


    }// HookNewPost
}
