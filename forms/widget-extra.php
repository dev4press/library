<h4><?php _e("Before and After Content", "d4plib"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('_before'); ?>"><?php _e("Before", "d4plib"); ?>:</label>
                <textarea class="widefat half-height" id="<?php echo $this->get_field_id('_before'); ?>" name="<?php echo $this->get_field_name('_before'); ?>"><?php echo esc_textarea($instance['_before']); ?></textarea>
            </td>
        </tr>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('_after'); ?>"><?php _e("After", "d4plib"); ?>:</label>
                <textarea class="widefat half-height" id="<?php echo $this->get_field_id('_after'); ?>" name="<?php echo $this->get_field_name('_after'); ?>"><?php echo esc_textarea($instance['_after']); ?></textarea>
            </td>
        </tr>
    </tbody>
</table>
