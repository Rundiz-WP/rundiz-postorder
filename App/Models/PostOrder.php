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
         * Reset `menu_order` column on `posts` table to zero (its default value).
         * 
         * This will be use on uninstall or reset all posts order on multi-site admin settings.
         * 
         * @global \wpdb $wpdb WordPress DB class.
         */
        public function resetPosts()
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
                    ' WHERE `' . $wpdb->posts . '`.`post_type` = \'post\'' . 
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
        }// resetPosts


        /**
         * Set new post order number on new post created.
         * 
         * @global \wpdb $wpdb WordPress DB class.
         * @param int $post_id
         * @return false|array Return `false` on failure, `array` on success.<br>
         *      The associative array keys are:<br>
         *          `menu_order` (int) post order number.<br>
         *          `updated` (int) number of rows updated.
         */
        public function setNewPostOrderNumber($post_id)
        {
            if (!is_numeric($post_id)) {
                return false;
            }

            global $wpdb;

            // get new menu_order number (new post is latest menu_order+1).
            $sql = 'SELECT `post_status`, `menu_order`, `post_type` FROM `' . $wpdb->posts . '`'
                . ' WHERE `post_type` = \'post\''
                . ' AND `post_status` IN(\'' . implode('\', \'', $this->allowed_order_post_status) . '\')'
                . ' ORDER BY `menu_order` DESC LIMIT 0, 1';
            $LastPost = $wpdb->get_row($sql);
            unset($sql);
            if (is_object($LastPost) && isset($LastPost->menu_order)) {
                $menu_order = bcadd($LastPost->menu_order, 1);
            } else {
                $menu_order = 1;
            }
            unset($LastPost);

            $result = $wpdb->update($wpdb->posts, ['menu_order' => $menu_order], ['ID' => $post_id], ['%d'], ['%d']);

            if (false === $result) {
                return false;
            }

            return [
                'menu_order' => $menu_order,
                'updated' => $result,
            ];
        }// setNewPostOrderNumber


    }
}