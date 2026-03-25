<?php
/**
 * Admin help tab 2.
 * 
 * @package rundiz-postorder
 */
?>
<p>
    <?php 
    printf(
        /* translators: %1$s Move up command; %2$s Move down command. */
        esc_html__('To re-order a post over next or previous pages, move your cursor on the row you want to re-order and click on %1$s or %2$s.', 'rundiz-postorder'),
        '<strong>' . esc_html__('Move up', 'rundiz-postorder') . '</strong>',
        '<strong>' . esc_html__('Move down', 'rundiz-postorder') . '</strong>'
    );
    ?>
    <br>
    <?php esc_html_e('The post that is on top of the list will be move up to previous page, the post that is on bottom of the list will be move down to next page.', 'rundiz-postorder'); ?>
</p>