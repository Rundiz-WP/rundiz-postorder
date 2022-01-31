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


    }
}