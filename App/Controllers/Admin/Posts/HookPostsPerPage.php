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
            if (
                is_admin() && 
                isset($_GET['page']) && 
                ReOrderPosts::MENU_SLUG === $_GET['page']
            ) {
                $posts_per_page = 50;
            }
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
