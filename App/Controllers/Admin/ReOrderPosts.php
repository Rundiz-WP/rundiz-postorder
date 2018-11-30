<?php


namespace RdPostOrder\App\Controllers\Admin;

if (!class_exists('\\RdPostOrder\\App\\Controllers\\ReOrderPosts')) {
    /**
     * This controller will be working as re-order the posts page.
     */
    class ReOrderPosts implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        use \RdPostOrder\App\AppTrait;


        public function adminHelpTab()
        {
            $screen = get_current_screen();

            $screen->add_help_tab([
                'id' => 'rd-postorder_reorder-posts-helptab1',
                'title' => __('Re-order by dragging', 'rd-postorder'),
                'content' => '<p>'
                    . sprintf(__('Put your cursor on the row you want to re-order and drag at the up/down icon (%s) to re-order the post item.', 'rd-postorder'), '<i class="fa fa-sort fa-fw"></i>')
                    . '<br>'."\n"
                    . __('Once you stop dragging and release the mouse button it will be update automatically.', 'rd-postorder')
                    . '<br>'."\n"
                    . __('To cancel re-order while dragging, please press &quot;escape&quot; (Esc) on your keyboard.', 'rd-postorder')
                    . '</p>'."\n",
            ]);
            $screen->add_help_tab([
                'id' => 'rd-postorder_reorder-posts-helptab2',
                'title' => __('Re-order over next/previous pages', 'rd-postorder'),
                'content' => '<p>'
                    . __('To re-order a post over next or previous pages, move your cursor on the row you want to re-order and click on move up or move down.', 'rd-postorder')
                    . '<br>'."\n"
                    . __('The post that is on top of the list will be move up to previous page, the post that is on bottom of the list will be move down to next page.', 'rd-postorder')
                    . '</p>'."\n",
            ]);
            $screen->add_help_tab([
                'id' => 'rd-postorder_reorder-posts-helptab3',
                'title' => __('Manually change order number', 'rd-postorder'),
                'content' => '<p>'
                    . __('The manually change order number is very useful if you have many posts and you want some posts from the very bottom of whole list to move to the very top of it.', 'rd-postorder')
                    . '<br>'."\n"
                    . __('You can just enter the number you want. The lowest number (example: 1) will be display on the last while the highest number will be display first.', 'rd-postorder')
                    . '<br>'."\n"
                    . __('Once you okay with that numbers, please select &quot;Save all changes on order numbers&quot; from bulk actions and click &quot;Apply&quot;.', 'rd-postorder')
                    . '</p>'."\n",
            ]);
            $screen->add_help_tab([
                'id' => 'rd-postorder_reorder-posts-helptab4',
                'title' => __('Re-number and reset', 'rd-postorder'),
                'content' => '<p>'
                    . __('To re-number all the posts in current listing order, please select &quot;Re-number all posts&quot; from bulk actions and click &quot;Apply&quot;.', 'rd-postorder')
                    . '<br>'."\n"
                    . __('To reset all the posts order by date, please select &quot;Reset all order&quot; from bulk actions and click &quot;Apply&quot;.', 'rd-postorder')
                    . '</p>'."\n",
            ]);

            $sidebar_html = $screen->get_help_sidebar();
            $sidebar_content = '<i class="fa fa-info-circle fa-fw"></i> ' . __('Please note that sticky post can be re-order here because whenever it is unstick then it can be displayed in correct order.', 'rd-postorder');
            $screen->set_help_sidebar($sidebar_html . $sidebar_content);
            unset($sidebar_content, $sidebar_html);
        }// adminHelpTab


        /**
         * Admin menu.<br>
         * Add sub menus in this method.
         */
        public function adminMenuAction()
        {
            $hook = add_posts_page(__('Re-order posts', 'rd-postorder'), __('Re-order posts', 'rd-postorder'), 'edit_others_posts', 'rd-postorder_reorder-posts', [$this, 'listPostsAction']);
            // redirect to nice URL if there are un-necessary query string in it.
            add_action('load-' . $hook, [$this, 'redirectNiceUrl']);
            // register css & js
            add_action('load-' . $hook, [$this, 'registerScripts']);
            // add help tab
            add_action('load-' . $hook, [$this, 'adminHelpTab']);

            unset($hook);
        }// adminMenuAction


        /**
         * Ajax re-number all posts.
         * 
         * @global \wpdb $wpdb
         */
        public function ajaxReNumberAll()
        {
            // check permission
            if (!current_user_can('edit_others_posts')) {
                status_header(403);
                wp_die(__('You do not have permission to access this page.'));
            }

            if (strtolower($_SERVER['REQUEST_METHOD']) === 'post' && isset($_POST) && !empty($_POST)) {
                if (check_ajax_referer('rdPostOrderReOrderPostsAjaxNonce', 'security', false) === false) {
                    status_header(403);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Please reload this page and try again.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                $paged = (isset($_POST['paged']) ? intval($_POST['paged']) : null);
                global $wpdb;

                // get all posts order by current menu_order (even it contain wrong order number but keep most of current order).
                $sql = 'SELECT `ID`, `post_status`, `menu_order`, `post_type` FROM `' . $wpdb->posts . '`'
                    . ' WHERE `post_type` = \'post\''
                    . ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')'
                    . ' ORDER BY `menu_order` DESC';
                $result = $wpdb->get_results($sql, OBJECT_K);
                unset($sql);
                if (is_array($result)) {
                    $i_count = count($result);
                    foreach ($result as $row) {
                        $wpdb->update($wpdb->posts, ['menu_order' => $i_count], ['ID' => $row->ID], ['%d'], ['%d']);
                        $i_count--;
                    }
                    unset($i_count, $row);
                }
                unset($result);

                // done update menu_order numbers
                $output['form_result_class'] = 'notice-success';
                $output['form_result_msg'] = __('Update completed', 'rd-postorder');
                $output['save_result'] = true;

                // get list table for re-render and client side.
                $_GET['paged'] = $paged;
                unset($paged);
                ob_start();
                $PostsListTable = new \RdPostOrder\App\Models\PostsListTable();
                $PostsListTable->prepare_items();
                $PostsListTable->display();
                $output['list_table_updated'] = ob_get_contents();
                unset($PostsListTable);
                ob_end_clean();

                if (isset($output)) {
                    // response
                    echo wp_json_encode($output);
                }
            }

            wp_die();// required
        }// ajaxReNumberAll


        /**
         * Ajax re-order a single post (move up or down).
         * 
         * @global \wpdb $wpdb
         */
        public function ajaxReOrderPost()
        {
            // check permission
            if (!current_user_can('edit_others_posts')) {
                status_header(403);
                wp_die(__('You do not have permission to access this page.'));
            }

            if (strtolower($_SERVER['REQUEST_METHOD']) === 'post' && isset($_POST) && !empty($_POST)) {
                if (check_ajax_referer('rdPostOrderReOrderPostsAjaxNonce', 'security', false) === false) {
                    status_header(403);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Please reload this page and try again.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                $move_to = (isset($_POST['move_to']) ? $_POST['move_to'] : null);
                $postID = (isset($_POST['postID']) ? intval($_POST['postID']) : null);
                $menu_order = (isset($_POST['menu_order']) ? intval($_POST['menu_order']) : null);
                $paged = (isset($_POST['paged']) ? intval($_POST['paged']) : null);

                if ($menu_order <= 0) {
                    status_header(500);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Error! Unable to re-order the posts due the the currently order number is incorrect. Please click on &quot;Re-number all posts&quot; button to re-number all the posts.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                if (
                    ($move_to == null || ($move_to != 'up' && $move_to != 'down')) ||
                    ($postID == null) ||
                    ($paged == null)
                ) {
                    status_header(400);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Unable to re-order the post. The js form did not send required data to re-order. Please reload the page and try again.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                // sort and save process -------------------------------------------------------
                global $wpdb;
                $data = [];
                $output = [];

                // get menu_order of selected item to make very sure that it will be correctly order.
                $sql = 'SELECT `ID`, `menu_order` FROM `' . $wpdb->posts . '` WHERE `ID` = \'%d\'';
                $sql = $wpdb->prepare($sql, $postID);
                $Posts = $wpdb->get_row($sql);
                unset($sql);
                $menu_order = $Posts->menu_order;
                unset($Posts);

                // get value of menu_order next to this selected item.
                if ($move_to == 'up') {
                    $sql = 'SELECT `ID`, `menu_order`, `post_type`, `post_status` FROM `' . $wpdb->posts . '`'
                        . ' WHERE `menu_order` > \'%d\''
                        . ' AND `post_type` = \'post\''
                        . ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')'
                        . ' ORDER BY `menu_order` ASC';
                    $sql = $wpdb->prepare($sql, $menu_order);
                    $Posts = $wpdb->get_row($sql);
                    unset($sql);
                } elseif ($move_to == 'down') {
                    $sql = 'SELECT `ID`, `menu_order`, `post_type`, `post_status` FROM `' . $wpdb->posts . '`'
                        . ' WHERE `menu_order` < \'%d\''
                        . ' AND `post_type` = \'post\''
                        . ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')'
                        . ' ORDER BY `menu_order` DESC';
                    $sql = $wpdb->prepare($sql, $menu_order);
                    $Posts = $wpdb->get_row($sql);
                    unset($sql);
                }
                if (isset($Posts) && is_object($Posts)) {
                    $data[$postID] = [
                        'ID' => $postID,
                        'menu_order' => $Posts->menu_order,
                    ];
                    $data[$Posts->ID] = [
                        'ID' => $Posts->ID,
                        'menu_order' => $menu_order,
                    ];
                    unset($Posts);
                }
                unset($menu_order, $move_to, $postID);

                // update to db. ---------------------------------
                if (is_array($data)) {
                    foreach ($data as $a_post_id => $item) {
                        $wpdb->update(
                            $wpdb->posts, 
                            ['menu_order' => $item['menu_order']], 
                            ['ID' => $item['ID']],
                            ['%d'],
                            ['%d']
                        );
                    }// endforeach;
                    unset($a_post_id, $item);
                }

                $output['form_result_class'] = 'notice-success';
                $output['form_result_msg'] = __('Update completed', 'rd-postorder');
                $output['save_result'] = true;

                unset($data);
                // end update to db. ----------------------------

                // get list table for re-render and client side.
                $_GET['paged'] = $paged;
                unset($paged);
                ob_start();
                $PostsListTable = new \RdPostOrder\App\Models\PostsListTable();
                $PostsListTable->prepare_items();
                $PostsListTable->display();
                $output['list_table_updated'] = ob_get_contents();
                unset($PostsListTable);
                ob_end_clean();

                if (isset($output)) {
                    // response
                    echo wp_json_encode($output);
                }
                // end sort and save process --------------------------------------------------
            }

            wp_die();// required
        }// ajaxReOrderPost


        /**
         * Ajax re-order multiple posts. (sortable items)
         * 
         * @global \wpdb $wpdb
         */
        public function ajaxReOrderPosts()
        {
            // check permission
            if (!current_user_can('edit_others_posts')) {
                status_header(403);
                wp_die(__('You do not have permission to access this page.'));
            }

            if (strtolower($_SERVER['REQUEST_METHOD']) === 'post' && isset($_POST) && !empty($_POST)) {
                if (check_ajax_referer('rdPostOrderReOrderPostsAjaxNonce', 'security', false) === false) {
                    status_header(403);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Please reload this page and try again.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                $postIDs = (isset($_POST['postID']) ? $_POST['postID'] : []);
                $menu_orders = (isset($_POST['menu_order']) ? $_POST['menu_order'] : []);// menu_order[post ID] = menu order number.
                $max_menu_order = (isset($_POST['max_menu_order']) ? $_POST['max_menu_order'] : 0);

                if ($max_menu_order <= 0) {
                    // max menu_order is 0 or lower. 
                    // this maybe because admin delete some middle items (not first and last) and it is not re-arrange the order numbers until it gets 0 or minus (not sure but i think it is impossible).
                    // show error to prevent the unwanted result and let the admin/author reset number of all posts order instead.
                    status_header(500);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Error! Unable to re-order the posts due the the currently order number is incorrect. Please click on &quot;Re-number all posts&quot; button to re-number all the posts.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                if ((!is_array($postIDs) || empty($postIDs)) || (!is_array($menu_orders) || empty($menu_orders))) {
                    status_header(400);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Unable to re-order the posts. The js form did not send any data to re-order. Please reload the page and try again.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                // sort and save process -------------------------------------------------------
                // sort `$menu_orders` array values reverse.
                arsort($menu_orders);
                // prepare `variable for save`
                $data = [];
                // set current menu_order at max by default.
                $menu_order = intval($max_menu_order);
                foreach ($postIDs as $postID) {
                    // set values
                    $data[$postID] = [
                        'ID' => intval($postID),
                        'menu_order' => $menu_order,
                    ];

                    // current menu_order was set. remove it from `$menu_orders` array.
                    foreach ($menu_orders as $a_post_ID => $a_menu_order) {
                        if ($menu_order == $a_menu_order) {
                            // if current `variable for save` ($data)'s menu_order is match the menu_order in `$menu_orders` array.
                            // remove this array key from `menu_orders` array.
                            unset($menu_orders[$a_post_ID]);
                            break;
                        }
                    }// endforeach; $menu_orders
                    unset($a_menu_order, $a_post_ID);

                    // get next menu order.
                    reset($menu_orders);
                    $menu_order = intval(current($menu_orders));
                }// endforeach; $postIDs
                unset($menu_order, $postID);
                unset($max_menu_order, $postIDs, $menu_orders);

                // update to db.-------------------
                if (is_array($data) && !empty($data)) {
                    global $wpdb;
                    foreach ($data as $postID => $item) {
                        $wpdb->update(
                            $wpdb->posts, 
                            ['menu_order' => $item['menu_order']], 
                            ['ID' => $item['ID']],
                            ['%d'],
                            ['%d']
                        );
                    }// endforeach;
                    unset($item, $postID);
                }
                // end update to db. -------------
                $output['form_result_class'] = 'notice-success';
                $output['form_result_msg'] = __('Update completed', 'rd-postorder');
                $output['save_result'] = true;
                $output['re_ordered_data'] = $data;
                unset($data);

                // response
                echo wp_json_encode($output);
                // end sort and save process --------------------------------------------------
            }

            wp_die();// required
        }// ajaxReOrderPosts


        /**
         * Ajax reset all posts order.<br>
         * Start from the beginings.
         * 
         * @global \wpdb $wpdb
         */
        public function ajaxResetAllPostsOrder()
        {
            // check permission
            if (!current_user_can('edit_others_posts')) {
                wp_die(__('You do not have permission to access this page.'));
            }

            if (strtolower($_SERVER['REQUEST_METHOD']) === 'post' && isset($_POST) && !empty($_POST)) {
                if (check_ajax_referer('rdPostOrderReOrderPostsAjaxNonce', 'security', false) === false) {
                    status_header(403);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Please reload this page and try again.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                $paged = (isset($_POST['paged']) ? intval($_POST['paged']) : null);
                global $wpdb;

                // get all posts order by current menu_order (even it contain wrong order number but keep most of current order).
                $sql = 'SELECT `ID`, `post_date`, `post_status`, `menu_order`, `post_type` FROM `' . $wpdb->posts . '`'
                    . ' WHERE `post_type` = \'post\''
                    . ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')'
                    . ' ORDER BY `post_date` DESC';
                $result = $wpdb->get_results($sql, OBJECT_K);
                unset($sql);
                if (is_array($result)) {
                    $i_count = count($result);
                    foreach ($result as $row) {
                        $wpdb->update($wpdb->posts, ['menu_order' => $i_count], ['ID' => $row->ID], ['%d'], ['%d']);
                        $i_count--;
                    }
                    unset($i_count, $row);
                }
                unset($result);

                // done update menu_order numbers
                $output['form_result_class'] = 'notice-success';
                $output['form_result_msg'] = __('Update completed', 'rd-postorder');
                $output['save_result'] = true;

                // get list table for re-render and client side.
                $_GET['paged'] = $paged;
                unset($paged);
                ob_start();
                $PostsListTable = new \RdPostOrder\App\Models\PostsListTable();
                $PostsListTable->prepare_items();
                $PostsListTable->display();
                $output['list_table_updated'] = ob_get_contents();
                unset($PostsListTable);
                ob_end_clean();

                if (isset($output)) {
                    // response
                    echo wp_json_encode($output);
                }
            }

            wp_die();// required
        }// ajaxResetAllPostsOrder


        /**
         * Ajax save all numbers that were manually changed.
         * 
         * @global \wpdb $wpdb
         */
        public function ajaxSaveAllNumbersChanged()
        {
            // check permission
            if (!current_user_can('edit_others_posts')) {
                wp_die(__('You do not have permission to access this page.'));
            }

            if (strtolower($_SERVER['REQUEST_METHOD']) === 'post' && isset($_POST) && !empty($_POST)) {
                if (check_ajax_referer('rdPostOrderReOrderPostsAjaxNonce', 'security', false) === false) {
                    status_header(403);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Please reload this page and try again.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                $menu_orders = (isset($_POST['menu_order']) ? $_POST['menu_order'] : []);
                $paged = (isset($_POST['paged']) ? intval($_POST['paged']) : null);
                global $wpdb;

                if (!is_array($menu_orders) || empty($menu_orders)) {
                    status_header(400);
                    $output['form_result_class'] = 'notice-error';
                    $output['form_result_msg'] = __('Unable to re-order the posts. The js form did not send any data to re-order. Please reload the page and try again.', 'rd-postorder');
                    echo wp_json_encode($output);
                    exit;
                }

                foreach ($menu_orders as $a_post_id => $a_menu_order) {
                    $wpdb->update(
                        $wpdb->posts, 
                        ['menu_order' => $a_menu_order], 
                        ['ID' => $a_post_id], 
                        ['%d'], 
                        ['%d']
                    );
                }// endforeach;
                unset($a_menu_order, $a_post_id, $menu_orders);

                // done update menu_order numbers
                $output['form_result_class'] = 'notice-success';
                $output['form_result_msg'] = __('Update completed', 'rd-postorder');
                $output['save_result'] = true;

                // get list table for re-render and client side.
                $_GET['paged'] = $paged;
                unset($paged);
                ob_start();
                $PostsListTable = new \RdPostOrder\App\Models\PostsListTable();
                $PostsListTable->prepare_items();
                $PostsListTable->display();
                $output['list_table_updated'] = ob_get_contents();
                unset($PostsListTable);
                ob_end_clean();

                if (isset($output)) {
                    // response
                    echo wp_json_encode($output);
                }
            }

            wp_die();// required
        }// ajaxSaveAllNumbersChanged


        /**
         * List the posts for re-order.
         */
        public function listPostsAction()
        {
            // check permission
            if (!current_user_can('edit_others_posts')) {
                wp_die(__('You do not have permission to access this page.'));
            }

            $output = [];

            // list the posts
            $PostsListTable = new \RdPostOrder\App\Models\PostsListTable();
            $PostsListTable->prepare_items();
            $output['PostsListTable'] = $PostsListTable;
            unset($PostsListTable);

            // load views for displaying
            $Loader = new \RdPostOrder\App\Libraries\Loader();
            $Loader->loadView('admin/ReOrderPosts/listPostsAction_v', $output);
            unset($Loader, $output);
        }// listPostsAction


        /**
         * Redirect to nice URL with query string.<br>
         * This method will be filter out un-necessary query string and redirect to the new one.
         */
        public function redirectNiceUrl()
        {
            if (isset($_GET['page']) && $_GET['page'] == 'rd-postorder_reorder-posts') {
                // redirect to show nice URL
                $not_showing_queries = ['_wpnonce', '_wp_http_referer', 'menu_order'];
                if (is_array($_REQUEST)) {
                    foreach ($_REQUEST as $name => $value) {
                        if (in_array($name, $not_showing_queries)) {
                            $needs_redirect = true;
                            break;
                        }
                    }// endforeach;
                    unset($name, $value);

                    if (isset($needs_redirect) && $needs_redirect === true) {
                        $new_url = admin_url('edit.php') . '?';
                        $new_query = [];
                        foreach ($_REQUEST as $name => $value) {
                            if (!in_array($name, $not_showing_queries)) {
                                $new_query[$name] = $value;
                            }
                        }// endforeach;
                        unset($name, $value);
                        $new_url .= http_build_query($new_query);
                        unset($new_query);
                        wp_redirect($new_url);
                    }
                }
                unset($needs_redirect, $not_showing_queries);
            }
        }// redirectNiceUrl


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            if (is_admin()) {
                add_action('admin_menu', [$this, 'adminMenuAction']);

                // add ajax actions
                add_action('wp_ajax_RdPostOrderReOrderPosts', [$this, 'ajaxReOrderPosts']);// re-order multiple posts
                add_action('wp_ajax_RdPostOrderReOrderPost', [$this, 'ajaxReOrderPost']);// re-order a single post (move up or down)
                add_action('wp_ajax_RdPostOrderReNumberAll', [$this, 'ajaxReNumberAll']);
                add_action('wp_ajax_RdPostOrderResetAllPostsOrder', [$this, 'ajaxResetAllPostsOrder']);
                add_action('wp_ajax_RdPostOrderSaveAllNumbersChanged', [$this, 'ajaxSaveAllNumbersChanged']);
            }
        }// registerHooks


        /**
         * Enqueue scripts and styles here.
         */
        public function registerScripts()
        {
            wp_enqueue_style('rd-postorder-font-awesome-css', plugin_dir_url(RDPOSTORDER_FILE) . 'assets/css/font-awesome.min.css', [], '4.6.3');
            wp_enqueue_style('rd-postorder-ReOrderPosts-css', plugin_dir_url(RDPOSTORDER_FILE) . 'assets/css/ReOrderPosts.css');

            wp_enqueue_script('rd-postorder-ReOrderPosts-js', plugin_dir_url(RDPOSTORDER_FILE) . 'assets/js/ReOrderPosts.js', ['jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-touch-punch', 'jquery-query'], false, true);
        }// registerScripts


    }
}