<?php
/**
 * Admin help tab 4.
 * 
 * @package rundiz-postorder
 */
?>
<p>
    <?php 
    printf(
        /* translators: %1$s re-number all posts text. */
        esc_html__('The %1$s action will be re-arrange numbers respectively on all posts in current listing order.', 'rundiz-postorder'),
        '<strong>' . esc_html__('Re-number all posts', 'rundiz-postorder') . '</strong>'
    );
    ?> 
    <br>
    <?php 
    printf(
        /* translators: %1$s reset all order  */
        esc_html__('The %1$s action will be reset the number on all posts order by date. It is the default order by WordPress core.', 'rundiz-postorder'),
        '<strong>' . esc_html__('Reset all order', 'rundiz-postorder') . '</strong>'
    );
    ?>
</p>