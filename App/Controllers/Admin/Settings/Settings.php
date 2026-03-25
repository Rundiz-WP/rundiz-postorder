<?php
/**
 * Settings.
 * 
 * @package rundiz-postorder
 */


namespace RundizPostOrder\App\Controllers\Admin\Settings;


if (!class_exists('\\RundizPostOrder\\App\\Controllers\\Admin\\Settings\\Settings')) {
    /**
     * This controller will be working as settings for rundiz postorder.
     */
    class Settings implements \RundizPostOrder\App\Controllers\ControllerInterface
    {


        use \RundizPostOrder\App\AppTrait;


        use Traits\SettingsTrait;


        /**
         * @var string Settings page menu slug. This constant must be public.
         */
        const MENU_SLUG = 'rundiz-postorder-settings';


        /**
         * Admin menu.<br>
         * Add sub menus in this method.
         */
        public function adminMenuAction()
        {
            $hookSuffix = add_options_page(__('Rundiz PostOrder', 'rundiz-postorder'), __('Rundiz PostOrder', 'rundiz-postorder'), 'manage_options', self::MENU_SLUG, [$this, 'settingsPageAction']);

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
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            if (is_admin()) {
                add_action('admin_menu', [$this, 'adminMenuAction']);
            }
        }// registerHooks


        /**
         * Enqueue scripts and styles.
         */
        public function registerScripts()
        {
            wp_enqueue_style(
                'rundiz-postorder-settings-css',
                plugin_dir_url(RUNDIZPOSTORDER_FILE) . 'assets/css/Admin/Settings/settings.css',
                [],
                RUNDIZPOSTORDER_VERSION
            );

            wp_register_script(
                'rundiz-postorder-settings-js',
                plugin_dir_url(RUNDIZPOSTORDER_FILE) . 'assets/js/Admin/Settings/settings.js',
                [],
                RUNDIZPOSTORDER_VERSION,
                [
                    'in_footer' => true,
                ]
            );
            wp_localize_script(
                'rundiz-postorder-settings-js',
                'RdPostOrderSettingsObj',
                [
                    'txtAreYouSure' => __('Are you sure?', 'rundiz-postorder'),
                ]
            );
            wp_enqueue_script('rundiz-postorder-settings-js');
        }// registerScripts


        /**
         * Display plugin settings page.
         */
        public function settingsPageAction()
        {
            // check permission.
            if (!current_user_can('manage_options')) {
                wp_die(esc_html__('You do not have permission to access this page.', 'rundiz-postorder'));
            }

            $output = [];

            // get all categories for check box disable per category.
            $args = [
                'taxonomy' => 'category',
                'hide_empty' => false,
                'hierarchical' => true,
                'pad_counts' => false,
            ];
            $categories = get_categories($args);
            unset($args);
            if (is_array($categories)) {
                $CategoryHelper = new \RundizPostOrder\App\Libraries\CategoryHelper();
                $output_tree = $CategoryHelper->buildCategoryHierarchyArray($categories);
                $output_tree_2d = [];
                static $output_tree_2d;
                $output_tree_2d = $CategoryHelper->buildCategoryNestedFlat2DArray($output_tree);
                if (is_array($output_tree_2d)) {
                    $categories = $output_tree_2d;
                }
                unset($CategoryHelper, $output_tree, $output_tree_2d);
            }
            $output['categories'] = $categories;
            unset($categories);

            // phpcs:ignore WordPress.Security.NonceVerification.Missing
            if ($_POST) {
                // if form submitted.
                if (!wp_verify_nonce((isset($_POST['_wpnonce']) ? sanitize_text_field(wp_unslash($_POST['_wpnonce'])) : ''))) {
                    wp_nonce_ays('-1');
                }

                $btnAct = sanitize_text_field(wp_unslash(filter_input(INPUT_POST, 'btn-act')));
                if ('reset-menu-order-to-original' === $btnAct) {
                    $resetResult = $this->resetPostOrdersToOriginal();
                    $output = array_merge($output, $resetResult);
                    unset($resetResult);
                } elseif ('reset-menu-order-to-zero' === $btnAct) {
                    $resetResult = $this->resetPostOrdersToZero();
                    $output = array_merge($output, $resetResult);
                    unset($resetResult);
                } else {
                    // normal save form process. --------------------------------------
                    $data = [];
                    $data['disable_customorder_frontpage'] = (isset($_POST['disable_customorder_frontpage']) && '1' === $_POST['disable_customorder_frontpage'] ? '1' : null);
                    $data['disable_customorder_categories'] = (isset($_POST['disable_customorder_categories']) && is_array($_POST['disable_customorder_categories']) ? wp_unslash($_POST['disable_customorder_categories']) : []);// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                    $data['disable_customorder_adminpage'] = (isset($_POST['disable_customorder_adminpage']) && '1' === $_POST['disable_customorder_adminpage'] ? '1' : null);
                    // validate selected categories.
                    foreach ($data['disable_customorder_categories'] as $index => $eachCategory) {
                        if (!is_numeric($eachCategory)) {
                            unset($data['disable_customorder_categories'][$index]);
                        }
                    }// endforeach;
                    unset($eachCategory, $index);

                    update_option($this->main_option_name, $data);

                    $output['form_result_class'] = 'notice-success';
                    $output['form_result_msg'] = __('Settings saved.', 'rundiz-postorder');
                }// endif; `btn-act`.
                unset($btnAct);
            }// endif; method POST.

            // get all options
            $output['options'] = get_option($this->main_option_name);

            $Loader = new \RundizPostOrder\App\Libraries\Loader();
            $Loader->loadView('admin/Settings/settings_v', $output);
            unset($Loader, $output);
        }// settingsPageAction


    }// Settings
}
