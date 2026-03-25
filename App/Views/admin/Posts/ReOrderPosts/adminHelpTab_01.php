<?php
/**
 * Admin help tab 1.
 * 
 * @package rundiz-postorder
 */
?>
<p>
    <?php
    /* translators: %1$s: The re-order icon. */
    printf(esc_html__('Put your cursor on the row you want to re-order and drag at the up/down icon (%1$s).', 'rundiz-postorder'), '<i class="fa fa-sort fa-fw"></i>');
    ?>
    <br>
    <?php esc_html_e('Once you stop dragging and release the mouse button it will be update automatically.', 'rundiz-postorder'); ?>
    <br>
    <?php 
    /* translators: %1$s Esc keyboard. */
    printf(esc_html__('To cancel re-order while dragging, please press %1$s on your keyboard.', 'rundiz-postorder'), '<kbd>Esc</kbd>'); 
    ?>
</p>