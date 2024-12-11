<?php
/**
 * @license http://opensource.org/licenses/MIT MIT
 */


namespace RdPostOrder\App\Models;


if (!class_exists('\\RdPostOrder\\App\\Models\\PostOrder')) {
    class PostOrder
    {


        use \RdPostOrder\App\AppTrait;


        /**
         * @var string Working on post type.
         */
        const POST_TYPE = 'post';


        /**
         * Get latest menu order number.
         * 
         * @global \wpdb $wpdb WordPress DB class.
         * @return int Return latest menu order number that is exists in DB. If not found then return zero. This method will not increase the latest order.
         */
        public function getLatestMenuOrder()
        {
            global $wpdb;

            // get new menu_order number (new post is latest menu_order+1).
            $sql = 'SELECT `post_status`, `menu_order`, `post_type` FROM `' . $wpdb->posts . '`'
                . ' WHERE `post_type` = \'' . \RdPostOrder\App\Models\PostOrder::POST_TYPE . '\''
                . ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')'
                . ' ORDER BY `menu_order` DESC LIMIT 0, 1';
            $LastPost = $wpdb->get_row($sql);
            unset($sql);

            if (is_object($LastPost) && isset($LastPost->menu_order)) {
                return (int) $LastPost->menu_order;
            }
            unset($LastPost);
            return (int) 0;
        }// getLatestMenuOrder


        /**
         * Reset or restart all posts order to be based on WordPress default post order on front page.
         * 
         * @since 1.0.5
         * @global \wpdb $wpdb WordPress DB class.
         * @param bool $updateOnlyMenuOrderZero Set to `true` to update only if menu order is zero. This is good for first activation that prevent a mess while deactivate then activate. Default is `false`.
         * @return int|false Return `false` on failure, return number of rows found and updated which can be zero.
         */
        public function resetAllPostsOrder($updateOnlyMenuOrderZero = false)
        {
            global $wpdb;
            // get sticky posts by its default order based on front page. ---------------
            $stickies = get_option('sticky_posts');
            if (!empty($stickies)) {
                $stickiesPlaceholders = array_fill(0, count($stickies), '%d');
                $sql = 'SELECT `ID`, `menu_order` FROM `' . $wpdb->posts . '` WHERE ';
                $sql .= ' `post_type` = %s';
                $sql .= ' AND `ID` IN (' . implode(', ', $stickiesPlaceholders) . ')';
                $sql .= ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')';
                $sql .= ' ORDER BY `post_date` DESC';
                $prepared = $wpdb->prepare($sql, array_merge([\RdPostOrder\App\Models\PostOrder::POST_TYPE], $stickies));
                $stickyPosts = $wpdb->get_results($prepared);
                unset($prepared, $sql);
            }
            unset($stickies);
            // end get sticky posts by its default order based on front page. -----------

            // get all the rest posts by its default order based on front page. ----------
            $sql = 'SELECT `ID`, `menu_order` FROM `' . $wpdb->posts . '` WHERE ';
            $sql .= ' `post_type` = %s';
            $sql .= ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')';
            if (!empty($stickiesPlaceholders) && isset($stickyPosts) && is_array($stickyPosts) && !empty($stickyPosts)) {
                $sql .= ' AND `ID` NOT IN (' . implode(', ', $stickiesPlaceholders) . ')';
            }
            $sql .= ' ORDER BY `post_date` DESC';
            $values = [\RdPostOrder\App\Models\PostOrder::POST_TYPE];
            if (isset($stickyPosts) && is_iterable($stickyPosts)) {
                foreach ($stickyPosts as $stickyPost) {
                    $values[] = $stickyPost->ID;
                }// endforeach; stickies
                unset($stickyPost);
            }// endif;
            $prepared = $wpdb->prepare($sql, $values);
            $postsResult = $wpdb->get_results($prepared);
            unset($prepared, $sql, $values);
            // end get all the rest posts by its default order based on front page. ------

            // merge sticky posts to the beginning of before normal posts.
            if (isset($stickyPosts) && is_array($stickyPosts)) {
                $allPosts = array_merge($stickyPosts, $postsResult);
            } else {
                $allPosts = $postsResult;
            }
            unset($postsResult, $stickyPosts);

            if (!is_array($allPosts)) {
                return false;
            }

            $i_count = count($allPosts);
            $updated = 0;
            foreach ($allPosts as $row) {
                if (
                    (
                        true === $updateOnlyMenuOrderZero && '0' === strval($row->menu_order)
                    ) ||
                    false === $updateOnlyMenuOrderZero
                ) {
                    $updateResult = $wpdb->update(
                        $wpdb->posts, 
                        ['menu_order' => $i_count], 
                        ['ID' => $row->ID], 
                        ['%d'], 
                        ['%d']
                    );
                    if (false !== $updateResult) {
                        ++$updated;
                    }
                }// endif; update only menu order is 0.
                --$i_count;
            }// endforeach; all posts
            unset($i_count, $row);
            unset($allPosts);

            return $updated;
        }// resetAllPostsOrder


        /**
         * Set `menu_order` column on `posts` table to zero (its default value).
         * 
         * This will be use on uninstall or reset all posts order on multi-site admin settings.
         * 
         * @global \wpdb $wpdb WordPress DB class.
         */
        public function setMenuOrderToZero()
        {
          global $wpdb;

          $results = $wpdb->get_results(
                'SELECT ' . 
                    '`ID`, ' . 
                    '`post_date`, ' . 
                    '`post_name`, ' . 
                    '`post_status`, ' . 
                    '`post_type`' . 
                    ' FROM `' . $wpdb->posts . '`' . 
                    ' WHERE `' . $wpdb->posts . '`.`post_type` = \'' . \RdPostOrder\App\Models\PostOrder::POST_TYPE . '\'' . 
                    ' AND `' . $wpdb->posts . '`.`post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')' . 
                    ' ORDER BY `' . $wpdb->posts . '`.`post_date` ASC',
                OBJECT
            );

            if (is_array($results)) {
                foreach ($results as $row) {
                    $wpdb->update(
                        $wpdb->posts,
                        ['menu_order' => 0],
                        ['ID' => $row->ID],
                        ['%d'],
                        ['%d']
                    );
                }// endforeach;
                unset($row);
            }
            unset($results);
        }// setMenuOrderToZero


        /**
         * Set new post order number on new post created.
         * 
         * This will also update shceduled posts to latest number that newer than the latest number updated by this method.<br>
         * See `updateScheduledPostsOrderToLatest()` method for more info.
         * 
         * @global \wpdb $wpdb WordPress DB class.
         * @param int $post_id
         * @return false|array Return `false` on failure, `array` on success.<br>
         *      The associative array keys are:<br>
         *          `menu_order` (int) post order number.<br>
         *          `updated` (int) number of rows updated.<br>
         *          `updatedScheduled` (int) number of rows updated for scheduled posts.<br>
         */
        public function setNewPostOrderNumber($post_id)
        {
            if (!is_numeric($post_id)) {
                return false;
            }

            global $wpdb;

            // get new `menu_order` number (new post is latest `menu_order`+1).
            $latestMenuOrder = $this->getLatestMenuOrder();
            $menu_order = ($latestMenuOrder + 1);
            unset($latestMenuOrder);

            $result = $wpdb->update($wpdb->posts, ['menu_order' => $menu_order], ['ID' => $post_id], ['%d'], ['%d']);

            if (false === $result) {
                return false;
            }

            $updateScheduledPosts = $this->updateScheduledPostsOrderToLatest();

            return [
                'menu_order' => $menu_order,
                'updated' => $result,
                'updatedScheduled' => $updateScheduledPosts,
            ];
        }// setNewPostOrderNumber


        /**
         * Update scheduled posts order to latest/newest (largest `menu_order` number++) that is newer than the new one updated on `setNewPostOrderNumber()` method.
         * 
         * Example: scheduled post had `menu_order` 13.<br>
         *      New post created and was set `menu_order` to 14 in `setNewPostOrderNumber()`.<br>
         *      This will be update the scheduled post `menu_order` to 15.
         * 
         * This method was called from `setNewPostOrderNumber()`.
         * 
         * @global \wpdb $wpdb WordPress DB class.
         * @return int Return number of rows updated.
         */
        protected function updateScheduledPostsOrderToLatest()
        {
            global $wpdb;

            // prepare latest post order number.
            $latestMenuOrder = $this->getLatestMenuOrder();

            $gmtDateTime = gmdate('Y-m-d H:i:s');
            $dateTime = get_date_from_gmt($gmtDateTime);

            // get scheduled posts by order ascending (for increase from latest order +1 each).
            $sql = 'SELECT `ID`, `post_date`, `post_date_gmt`, `post_status`, `menu_order`, `post_type` FROM `' . $wpdb->posts . '`'
                . ' WHERE `post_type` = \'' . \RdPostOrder\App\Models\PostOrder::POST_TYPE . '\''
                . ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')'
                . ' AND (`post_date` > \'%s\' OR `post_date_gmt` > \'%s\')'
                . ' ORDER BY `menu_order` ASC';
            $sql = $wpdb->prepare($sql, [$dateTime, $gmtDateTime]);
            unset($dateTime, $gmtDateTime);
            $Posts = $wpdb->get_results($sql);
            unset($sql);

            $updated = 0;
            if (is_array($Posts)) {
                foreach ($Posts as $row) {
                    $latestMenuOrder = ($latestMenuOrder + 1);
                    $result = $wpdb->update(
                        $wpdb->posts, 
                        ['menu_order' => $latestMenuOrder], 
                        ['ID' => $row->ID], 
                        ['%d'], 
                        ['%d']
                    );
                    if (is_numeric($result) && false !== $result) {
                        $updated = ($updated + $result);
                    }
                }// endforeach;
                unset($result, $row);
            }
            unset($latestMenuOrder, $Posts);

            return $updated;
        }// updateScheduledPostsOrderToLatest


    }
}