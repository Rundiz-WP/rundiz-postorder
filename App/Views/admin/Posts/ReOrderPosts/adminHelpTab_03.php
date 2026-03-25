<?php
/**
 * Admin help tab 3.
 * 
 * @package rundiz-postorder
 */
?>
<p>
    <?php esc_html_e('The manually change order number is very useful if you have many posts and you want to re-order the number manually.', 'rundiz-postorder'); ?>
    <br>
    <?php esc_html_e('You can just enter the number you want. The lowest number (example: 1) will be display on the last while the highest number will be display first.', 'rundiz-postorder'); ?>
    <br>
    <?php 
    printf(
        /* translators: %1$s The word save all changes on order numbers on select action; %2$s Apply text on button. */
        esc_html__('Once you okay with those numbers, please select %1$s from bulk actions and click %2$s.', 'rundiz-postorder'),
        '<strong>' . esc_html__('Save all changes on order numbers', 'rundiz-postorder') . '</strong>',
        '<strong>' . esc_html__('Apply', 'rundiz-postorder') . '</strong>'
    );
    ?>
</p>