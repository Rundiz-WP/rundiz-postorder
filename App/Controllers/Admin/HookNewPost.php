<?php


namespace RdPostOrder\App\Controllers\Admin;

if (!class_exists('\\RdPostOrder\\App\\Controlers\\Admin\\HookNewPost')) {
    class HookNewPost implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        use \RdPostOrder\App\AppTrait;


        /**
         * Admin users saving the post.
         * 
         * @link https://codex.wordpress.org/Plugin_API/Action_Reference/wp_insert_post Referrer.
         * @global \wpdb $wpdb
         * @param integer $post_id
         * @param object $post
         * @param boolean $update
         */
        public function hookInsertPostAction($post_id, $post, $update)
        {
            if (
                is_object($post) 
                && isset($post->post_status) && in_array($post->post_status, $this->allowed_order_post_status) 
                && isset($post->menu_order) && $post->menu_order == '0' 
                && isset($post->post_type) && $post->post_type == 'post' 
            ) {
                // if this save is first time, whatever it status is.
                global $wpdb;

                // get new menu_order number (new post is latest menu_order+1).
                $sql = 'SELECT `post_status`, `menu_order`, `post_type` FROM `' . $wpdb->posts . '`'
                    . ' WHERE `post_type` = \'post\''
                    . ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')'
                    . ' ORDER BY `menu_order` DESC';
                $LastPost = $wpdb->get_row($sql);
                unset($sql);
                if (is_object($LastPost) && isset($LastPost->menu_order)) {
                    $menu_order = bcadd($LastPost->menu_order, 1);
                } else {
                    $menu_order = 1;
                }
                unset($LastPost);

                $wpdb->update($wpdb->posts, ['menu_order' => $menu_order], ['ID' => $post_id], ['%d'], ['%d']);

                \RdPostOrder\App\Libraries\Debug::writeLog('RundizPostOrder hookInsertPostAction() method was called. Admin is saving new post. The new `menu_order` value is ' . $menu_order . ' and the post `ID` is ' . $post_id . '.');
            }
        }// hookInsertPostAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            if (is_admin()) {
                add_action('wp_insert_post', [$this, 'hookInsertPostAction'], 10, 3);
            }
        }// registerHooks


    }
}