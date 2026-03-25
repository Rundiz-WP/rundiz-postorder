<?php
/**
 * Multi-site settings.
 * 
 * @package rundiz-postorder
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RundizPostOrder\App\Controllers\Admin\Settings;


if (!class_exists('\\RundizPostOrder\\App\\Controllers\\Admin\\Settings\\MultisiteSettings')) {
    /**
     * Multi site settings class.
     */
    class MultisiteSettings implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        use \RundizPostOrder\App\AppTrait;


        use Traits\SettingsTrait;


        /**
         * Add menu to network admin page.
         */
        public function adminMenuAction()
        {
            $hookSuffix = add_submenu_page('settings.php', __('Rundiz PostOrder', 'rundiz-postorder'), __('Rundiz PostOrder', 'rundiz-postorder'), 'manage_network_plugins', 'rd-postorder-networksettings', [$this, 'networkSettingsPageAction'], 10);

            if (is_string($hookSuffix)) {
                add_action('load-' . $hookSuffix, [$this, 'callEnqueueHook']);
            }

            unset($hookSuffix);
        }// adminMenuAction


        /**
         * Allow code/WordPress to call hook `admin_enqueue_scripts` 
         * then `wp_register_script()`, `wp_localize_script()`, `wp_enqueue_script()` functions will be working fine later.
         * 
         * This method was called from `adminMenuAction()` on hook `load-$suffix`.
         * 
         * @link https://wordpress.stackexchange.com/a/76420/41315 Original source code.
         */
        public function callEnqueueHook()
        {
            add_action('admin_enqueue_scripts', [$this, 'registerScripts']);
        }// callEnqueueHook


        /**
         * Display network settings page.
         * 
         * @global \wpdb $wpdb WordPress DB class.
         */
        public function networkSettingsPageAction()
        {
            // check permission.
            if (!current_user_can('manage_network_plugins')) {
                wp_die(esc_html__('You do not have permission to access this page.', 'rundiz-postorder'));
                exit();
            }
            if (!is_multisite()) {
                wp_die(esc_html__('You do not have permission to access this page.', 'rundiz-postorder'));
                exit();
            }

            $output = [];

            // phpcs:ignore WordPress.Security.NonceVerification.Missing
            if ($_POST) {
                // if form submitted.
                // phpcs:ignore WordPress.Security.NonceVerification.Missing
                if (!wp_verify_nonce((isset($_POST['_wpnonce']) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : ''))) {
                    wp_nonce_ays('-1');
                }

                global $wpdb;
                $btnAct = sanitize_text_field(wp_unslash(filter_input(INPUT_POST, 'btn-act')));
                $blog_ids = $wpdb->get_col('SELECT blog_id FROM ' . $wpdb->blogs);// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
                $original_blog_id = get_current_blog_id();

                if (is_array($blog_ids)) {
                    foreach ($blog_ids as $blog_id) {
                        switch_to_blog($blog_id);
                        if ('reset-menu-order-to-original' === $btnAct) {
                            $resetResult = $this->resetPostOrdersToOriginal();
                            $output = array_merge($output, $resetResult);
                            unset($resetResult);
                        } elseif ('reset-menu-order-to-zero' === $btnAct) {
                            $resetResult = $this->resetPostOrdersToZero();
                            $output = array_merge($output, $resetResult);
                            unset($resetResult);
                        }// endif; `btn-act`.
                    }// endforeach;
                    unset($blog_id);
                }// endif; $blog_ids

                unset($btnAct);
                // switch back to current site.
                switch_to_blog($original_blog_id);
                unset($blog_ids, $original_blog_id);
            }// endif; method POST.

            // get all options
            $output['options'] = get_option($this->main_option_name);

            $Loader = new \RundizPostOrder\App\Libraries\Loader();
            $Loader->loadView('admin/Settings/multisiteSettings_v', $output);
            unset($Loader, $output);
        }// networkSettingsPageAction


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            add_action('network_admin_menu', [$this, 'adminMenuAction']);
        }// registerHooks


        /**
         * Enqueue scripts and styles.
         */
        public function registerScripts()
        {
            wp_enqueue_style(
                'rd-postorder-settings-page',
                plugin_dir_url(RUNDIZPOSTORDER_FILE) . 'assets/css/Admin/Settings/settings.css',
                [],
                RUNDIZPOSTORDER_VERSION
            );

            wp_register_script(
                'rd-postorder-settings-page',
                plugin_dir_url(RUNDIZPOSTORDER_FILE) . 'assets/js/Admin/Settings/settings.js',
                [],
                RUNDIZPOSTORDER_VERSION,
                [
                    'in_footer' => true,
                ]
            );
            wp_localize_script(
                'rd-postorder-settings-page',
                'RdPostOrderSettingsObj',
                [
                    'txtAreYouSure' => __('Are you sure?', 'rundiz-postorder') . "\n" . __('Warning! This will be affect on all sites.', 'rundiz-postorder'),
                ]
            );
            wp_enqueue_script('rd-postorder-settings-page');
        }// registerScripts


    }// MultisiteSettings
}
