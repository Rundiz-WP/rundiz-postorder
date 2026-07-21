<?php
/**
 * Main app trait for common works.
 * 
 * @package rundiz-postorder
 */


namespace RundizPostOrder\App;


if (!trait_exists('\\RundizPostOrder\\App\\AppTrait')) {
    /**
     * Main application trait.
     */
    trait AppTrait
    {


        /**
         * @var \RundizPostOrder\App\Libraries\Loader The loader class if it has been initiated. Make sure that this property must be set before use.
         */
        protected $Loader = null;


        /**
         * @var array Allowed post status that can be change order.<br>
         *              These post status can be convert into publish or private but *auto-draft* and *inherit* is not (trash status can also be revert to publish or private).
         * @link https://wordpress.org/support/article/post-status/ Reference
         */
        protected $allowed_order_post_status = ['publish', 'future', 'draft', 'pending', 'private', 'trash'];


        /**
         * @var string Main options name for use with add_option() or get_option().
         */
        public $main_option_name = 'rundiz_postorder_options';


        /**
         * Get `Loader` object from `Loader` property.
         * 
         * This method is in main AppTrait.
         *
         * @return \RundizPostOrder\App\Libraries\Loader Return the `Loader` object.
         */
        protected function getLoader()
        {
            if (!$this->Loader instanceof \RundizPostOrder\App\Libraries\Loader) {
                $this->Loader = new \RundizPostOrder\App\Libraries\Loader();
            }
            return $this->Loader;
        }// getLoader


        /**
         * Set `Loader` object to `Loader` property.
         * 
         * This method is in main AppTrait.
         *
         * @param \RundizPostOrder\App\Libraries\Loader $Loader The `Loader` object.
         */
        public function setLoader(\RundizPostOrder\App\Libraries\Loader $Loader)
        {
            $this->Loader = $Loader;
        }// setLoader


    }// AppTrait
}
