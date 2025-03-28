<?php


namespace RdPostOrder\App\Controllers\Common;

if (!class_exists('\\RdPostOrder\\App\\Controllers\\Common\\AlterPosts')) {
    /**
     * This controller will be working on front end to alter list post query.
     */
    class AlterPosts implements \RdPostOrder\App\Controllers\ControllerInterface
    {


        use \RdPostOrder\App\AppTrait;


        /**
         * Alter list post query.
         * 
         * @param \WP_Query $query
         */
        public function alterListPostAction($query)
        {
            if (is_admin()) {
                if (isset($query->query['post_type']) && 'post' == $query->query['post_type'] && !isset($_GET['orderby']) && !isset($_GET['order'])) {
                    $is_disable_customorder_admin = $this->isDisableCustomOrder('admin');

                    if (isset($is_disable_customorder_admin) && true !== $is_disable_customorder_admin) {
                        $query->set('orderby', 'menu_order');
                        $query->set('order', 'DESC');
                    }

                    unset($is_disable_customorder_admin);
                }
            } else {
                if (!$query->is_main_query()) {
                    // if not main query (such as widget recent posts).
                    return ;
                }

                $is_disable_customorder = $this->isDisableCustomOrder();

                if (isset($is_disable_customorder) && true !== $is_disable_customorder) {
                    $query->set('orderby', 'menu_order');
                    $query->set('order', 'DESC');
                }

                unset($is_disable_customorder);
            }
        }// alterListPostAction


        /**
         * Alter next post sort.<br>
         * This is working from single post page.
         * 
         * @see wp-includes/link-template.php at get_{$adjacent}_post_sort
         * @param string $order_by The `ORDER BY` clause in the SQL.
         * @param \WP_Post $post WP_Post object.
         * @return string Return the modified `order by`.
         */
        public function alterNextPostSort($order_by, $post)
        {
            $is_disable_customorder = $this->isDisableCustomOrder();

            if (isset($is_disable_customorder) && true !== $is_disable_customorder) {
                if (isset($post->post_type) && \RdPostOrder\App\Models\PostOrder::POST_TYPE === $post->post_type) {
                    $order_by = 'ORDER BY p.menu_order ASC LIMIT 1';
                }
            }

            unset($is_disable_customorder);
            return $order_by;
        }// alterNextPostSort


        /**
         * Alter next post where.<br>
         * This is working from single post page.
         * 
         * @see wp-includes/link-template.php at get_{$adjacent}_post_where
         * @param string $where The `WHERE` clause in the SQL.
         * @param boolean $in_same_term Whether post should be in a same taxonomy term.
         * @param array $excluded_terms Array of excluded term IDs.
         * @param string $taxonomy Taxonomy. Used to identify the term used when `$in_same_term` is true.
         * @param \WP_Post $post WP_Post object.
         * @return string Return the modified where from default to `menu_order` field.
         */
        public function alterNextPostWhere($where, $in_same_term, $excluded_terms, $taxonomy, $post)
        {
            $is_disable_customorder = $this->isDisableCustomOrder();

            if (isset($is_disable_customorder) && true !== $is_disable_customorder) {
                if (isset($post->post_type) && \RdPostOrder\App\Models\PostOrder::POST_TYPE === $post->post_type) {
                    $where = str_replace('p.post_date > \''.$post->post_date.'\'', 'p.menu_order > \''.$post->menu_order.'\'', $where);
                }
            }

            unset($is_disable_customorder);
            return $where;
        }// alterNextPostWhere


        /**
         * Alter previous post sort.<br>
         * This is working from single post page.
         * 
         * @see wp-includes/link-template.php at get_{$adjacent}_post_sort
         * @param string $order_by The `ORDER BY` clause in the SQL.
         * @param \WP_Post $post WP_Post object.
         * @return string Return the modified `order by`.
         */
        public function alterPreviousPostSort($order_by, $post)
        {
            $is_disable_customorder = $this->isDisableCustomOrder();

            if (isset($is_disable_customorder) && true !== $is_disable_customorder) {
                if (isset($post->post_type) && \RdPostOrder\App\Models\PostOrder::POST_TYPE === $post->post_type) {
                    $order_by = 'ORDER BY p.menu_order DESC LIMIT 1';
                }
            }

            unset($is_disable_customorder);
            return $order_by;
        }// alterPreviousPostSort


        /**
         * Alter previous post where.<br>
         * This is working from single post page.
         * 
         * @see wp-includes/link-template.php at get_{$adjacent}_post_where
         * @param string $where The `WHERE` clause in the SQL.
         * @param boolean $in_same_term Whether post should be in a same taxonomy term.
         * @param array $excluded_terms Array of excluded term IDs.
         * @param string $taxonomy Taxonomy. Used to identify the term used when `$in_same_term` is true.
         * @param \WP_Post $post WP_Post object.
         * @return string Return the modified where from default to `menu_order` field.
         */
        public function alterPreviousPostWhere($where, $in_same_term, $excluded_terms, $taxonomy, $post)
        {
            $is_disable_customorder = $this->isDisableCustomOrder();

            if (isset($is_disable_customorder) && true !== $is_disable_customorder) {
                if (isset($post->post_type) && \RdPostOrder\App\Models\PostOrder::POST_TYPE === $post->post_type) {
                    $where = str_replace('p.post_date < \''.$post->post_date.'\'', 'p.menu_order < \''.$post->menu_order.'\'', $where);
                }
            }

            unset($is_disable_customorder);
            return $where;
        }// alterPreviousPostWhere


        /**
         * Check that is there any filter hooks to disable custom order.<br>
         * Also check that is this plugin was set to disable in settings page.
         * 
         * @param string $checkFor Check for which part. Acceptable value is `front`, `admin`.
         * @return boolean Return `true` if it was set to disable, otherwise return `false`.
         */
        protected function isDisableCustomOrder($checkFor = 'front')
        {
            // check by using hook (filters). ----------------
            if ('front' === $checkFor) {
                $rd_postorder_is_working = apply_filters('rd_postorder_is_working', true);
                if (true !== $rd_postorder_is_working) {
                    // disable by plugin hooks (filter).
                    return true;
                }
                unset($rd_postorder_is_working);
            } elseif ('admin' === $checkFor) {
                $rd_postorder_admin_is_working = apply_filters('rd_postorder_admin_is_working', true);
                if (true !== $rd_postorder_admin_is_working) {
                    // disable by plugin hooks (filter).
                    return true;
                }
                unset($rd_postorder_admin_is_working);
            }// endif; $checkFor
            // end check by using hook (filters). ------------

            $plugin_options = get_option($this->main_option_name);

            if (is_array($plugin_options)) {
                // @link https://developer.wordpress.org/themes/basics/conditional-tags/ Conditional tags for check is admin or front pages etc.
                if (is_admin()) {
                    // if in admin pages.
                    if (
                        array_key_exists('disable_customorder_adminpage', $plugin_options) &&
                        '1' === $plugin_options['disable_customorder_adminpage']
                    ) {
                        // if setting to disabled on admin pages.
                        return true;
                    }
                } elseif (is_front_page() || is_home() || is_singular()) {
                    // if in front or home pages.
                    // `is_singluar()` is for make it work with `get_[next|previous]_post_[where|sort]` hooks. 
                    // if front, or home pages setting is disabled then single post should disabled also.
                    if (
                        array_key_exists('disable_customorder_frontpage', $plugin_options) && 
                        '1' === $plugin_options['disable_customorder_frontpage']
                    ) {
                        // if setting to disabled on front pages.
                        return true;
                    }
                }// endif; is_admin() or not.

                if (is_category()) {
                    // get current category id.
                    $this_category = get_the_category();
                    if (!is_object($this_category) && is_array($this_category)) {
                        $this_category = array_shift($this_category);
                    }
                    $this_category_id = (isset($this_category->term_id) ? $this_category->term_id : 0);
                    unset($this_category);

                    if (0 === $this_category_id) {
                        // if found no category.
                        // @link https://wordpress.stackexchange.com/questions/59476/get-current-category-id-php In case that website post don't select any category then this is the last chance.
                        $this_category = get_queried_object();
                        if (isset($this_category->term_id)) {
                            $this_category_id = $this_category->term_id;
                        }
                    }
                    unset($this_category);

                    if (
                        array_key_exists('disable_customorder_categories', $plugin_options) && 
                        is_array($plugin_options['disable_customorder_categories']) &&
                        in_array($this_category_id, $plugin_options['disable_customorder_categories'])
                    ) {
                        return true;
                    }
                }// endif; is home, front, category...
            }// endif; plugin options are array.

            unset($plugin_options);
            return false;
        }// isDisableCustomOrder


        /**
         * {@inheritDoc}
         */
        public function registerHooks()
        {
            add_action('pre_get_posts', [$this, 'alterListPostAction'], 20);

            add_filter('get_previous_post_where', [$this, 'alterPreviousPostWhere'], 10, 5);
            add_filter('get_previous_post_sort', [$this, 'alterPreviousPostSort'], 10, 2);
            add_filter('get_next_post_where', [$this, 'alterNextPostWhere'], 10, 5);
            add_filter('get_next_post_sort', [$this, 'alterNextPostSort'], 10, 2);
        }// registerHooks


    }
}