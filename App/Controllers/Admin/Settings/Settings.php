<?php


namespace RdPostOrder\App\Controllers\Admin\Settings;

if (!class_exists('\\RdPostOrder\\App\\Controllers\\Admin\\Settings\\Settings')) {
    /**
     * This controller will be working as settings for rundiz postorder.
     */
    class Settings implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        use \RdPostOrder\App\AppTrait;


        use Traits\SettingsTrait;


        /**
         * Admin menu.<br>
         * Add sub menus in this method.
         */
        public function adminMenuAction()
        {
            $hookSuffix = add_options_page(__('Rundiz PostOrder', 'rd-postorder'), __('Rundiz PostOrder', 'rd-postorder'), 'manage_options', 'rd-postorder-settings', [$this, 'settingsPageAction']);

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
                'rd-postorder-settings-page',
                plugin_dir_url(RDPOSTORDER_FILE) . 'assets/css/Admin/Settings/settings.css',
                [],
                RDPOSTORDER_VERSION
            );

            wp_register_script(
                'rd-postorder-settings-page',
                plugin_dir_url(RDPOSTORDER_FILE) . 'assets/js/Admin/Settings/settings.js',
                [],
                RDPOSTORDER_VERSION,
                [
                    'in_footer' => true,
                ]
            );
            wp_localize_script(
                'rd-postorder-settings-page',
                'RdPostOrderSettingsObj',
                [
                    'txtAreYouSure' => __('Are you sure?', 'rd-postorder'),
                ]
            );
            wp_enqueue_script('rd-postorder-settings-page');
        }// registerScripts


        /**
         * Display plugin settings page.
         */
        public function settingsPageAction()
        {
            // check permission.
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have permission to access this page.'));
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
                $CategoryHelper = new \RdPostOrder\App\Libraries\CategoryHelper();
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

            if ($_POST) {
                // if form submitted.
                if (!wp_verify_nonce((isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : ''))) {
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
                    $data['disable_customorder_categories'] = (isset($_POST['disable_customorder_categories']) && is_array($_POST['disable_customorder_categories']) ? $_POST['disable_customorder_categories'] : []);
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
                    $output['form_result_msg'] =  __('Settings saved.');
                }// endif; `btn-act`.
                unset($btnAct);
            }// endif; method POST.

            // get all options
            $output['options'] = get_option($this->main_option_name);

            $Loader = new \RdPostOrder\App\Libraries\Loader();
            $Loader->loadView('admin/Settings/settings_v', $output);
            unset($Loader, $output);
        }// settingsPageAction


    }
}