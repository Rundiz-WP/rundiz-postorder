<?php
/**
 * Abstract class of re-order posts.
 * 
 * @package rundiz-postorder
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RundizPostOrder\App\Controllers\Admin\Posts;


if (!class_exists('\\RundizPostOrder\\App\\Controllers\\Admin\\Posts\\AbstractReOrderPosts')) {
    /**
     * Abstract class of re-order posts.
     */
    abstract class AbstractReOrderPosts implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        use \RundizPostOrder\App\AppTrait;


        /**
         * @var string Admin menu slug.
         */
        const MENU_SLUG = 'rd-postorder_reorder-posts';


    }// AbstractReOrderPosts
}
