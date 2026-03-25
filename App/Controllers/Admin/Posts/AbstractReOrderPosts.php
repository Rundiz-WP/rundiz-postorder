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
         * @var string Admin re-order posts menu slug. This constant must be public.
         */
        const MENU_SLUG = 'rundiz-postorder_reorder-posts';


    }// AbstractReOrderPosts
}
