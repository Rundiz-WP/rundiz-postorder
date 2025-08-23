<?php
/**
 * Settings trait.
 * 
 * @package rd-postorder
 * @since 1.0.8
 */


namespace RdPostOrder\App\Controllers\Admin\Settings\Traits;


if (!trait_exists('\\RdPostOrder\\App\\Controllers\\Admin\\Settings\Traits\\SettingsTrait')) {
    trait SettingsTrait
    {


        /**
         * Reset post orders to their original value.
         * 
         * @return array
         */
        protected function resetPostOrdersToOriginal()
        {
            $PostOrder = new \RdPostOrder\App\Models\PostOrder();
            $PostOrder->setMenuOrderToOriginal();
            // delete post meta that this plugin use to store their original value.
            delete_post_meta_by_key(\RdPostOrder\App\Models\PostOrder::POST_META_ORIG_MENUORDER_NAME);
            unset($PostOrder);

            $output = [];
            $output['form_result_class'] = 'notice-success';
            $output['form_result_msg'] =  __('Post order has been reset successfully.', 'rd-postorder');
            return $output;
        }// resetPostOrdersToOriginal


        /**
         * Reset post orders to ZERO.
         * 
         * @return array
         */
        protected function resetPostOrdersToZero()
        {
            $PostOrder = new \RdPostOrder\App\Models\PostOrder();
            $PostOrder->setMenuOrderToZero();
            // delete post meta that this plugin use to store their original value.
            delete_post_meta_by_key(\RdPostOrder\App\Models\PostOrder::POST_META_ORIG_MENUORDER_NAME);
            unset($PostOrder);

            $output = [];
            $output['form_result_class'] = 'notice-success';
            $output['form_result_msg'] =  __('Post order has been reset to zero successfully.', 'rd-postorder');
            return $output;
        }// resetPostOrdersToZero


    }// SettingsTrait
}
