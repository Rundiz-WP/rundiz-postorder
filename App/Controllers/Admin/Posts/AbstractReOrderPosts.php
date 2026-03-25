<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RundizPostOrder\App\Controllers\Admin\Posts;


if (!class_exists('\\RundizPostOrder\\App\\Controllers\\Admin\\Posts\\AbstractReOrderPosts')) {
    abstract class AbstractReOrderPosts implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        use \RundizPostOrder\App\AppTrait;


        /**
         * @var string Admin menu slug.
         */
        const MENU_SLUG = 'rd-postorder_reorder-posts';


    }
}
