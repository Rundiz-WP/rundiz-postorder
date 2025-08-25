<?php
/**
 * Hook to alter posts per page for re-order admin page.
 * 
 * @package rd-postoder
 * @since 1.0.8
 */


namespace RdPostOrder\App\Controllers\Admin\Posts;


if (!class_exists('\\RdPostOrder\\App\\Controlers\\Admin\\Posts\\HookPostsPerPage')) {
    class HookPostsPerPage implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        /**
         * Hook on `edit_posts_per_page`.
         * 
         * @param int $posts_per_page Number of posts to be displayed. Default 20.
	 * @param string $post_type The post type.
         */
        public function hookPostsPerPage($posts_per_page, $post_type)
        {
            // allowed AJAX actions to use custom posts per page.
            $allowedActions = [
                'RdPostOrderReOrderPost',
                'RdPostOrderReNumberAll',
                'RdPostOrderResetAllPostsOrder',
                'RdPostOrderSaveAllNumbersChanged',
            ];

            if (
                is_admin() && // must always in admin pages AND ...
                (
                    (
                        isset($_GET['page']) && 
                        ReOrderPosts::MENU_SLUG === $_GET['page']
                    ) ||// contains ?page matched this plugin menu slug. OR
                    (
                        wp_doing_ajax() &&
                        isset($_REQUEST['action']) &&
                        in_array($_REQUEST['action'], $allowedActions)
                    )// is AJAX and in allowed actions in this plugin.
                )
            ) {
                $posts_per_page = 50;
            }

            unset($allowedActions);
            return $posts_per_page;
        }// hookPostsPerPage


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            add_filter('edit_posts_per_page', [$this, 'hookPostsPerPage'], 10, 2);
        }// registerHooks


    }// HookPostsPerPage
}
