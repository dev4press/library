<?php

$list_users_visibility = array(
    'all' => __("Everyone", "d4plib"),
    'users' => __("Logged in users", "d4plib"),
    'visitors' => __("Not logged in visitors", "d4plib"),
    'roles' => __("Users with specified roles", "d4plib"),
    'caps' => __("Users with specified capabilities", "d4plib")
);

?>
<h4><?php _e("Widget Visibility", "d4plib"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('_users'); ?>"><?php _e("Show widget to", "d4plib"); ?>:</label>
                <?php d4p_render_select($list_users_visibility, array('id' => $this->get_field_id('_users'), 'class' => 'widefat d4plib-div-switch', 'name' => $this->get_field_name('_users'), 'selected' => $instance['_users']), array('data-prefix' => 'visibility')); ?>
            </td>
        </tr>
    </tbody>
</table>

<div class="d4p-div-block-visibility d4p-div-block-visibility-roles" style="display: <?php echo $instance['_users'] == 'roles' ? 'block' : 'none'; ?>;">
    <table>
        <tbody>
            <tr>
                <td class="cell-singular">
                    <label for="<?php echo $this->get_field_id('_roles'); ?>"><?php _e("Roles", "d4plib"); ?>:</label>
                    <?php d4p_render_checkradios(d4p_get_wordpress_user_roles(), array('id' => $this->get_field_id('_roles'), 'class' => 'widefat', 'name' => $this->get_field_name('_roles'), 'selected' => $instance['_roles'])); ?>

                    <em>
                        <?php _e("If no roles are selected, widget will assume that all roles are selected.", "d4plib"); ?>
                    </em>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="d4p-div-block-visibility d4p-div-block-visibility-caps" style="display: <?php echo $instance['_users'] == 'caps' ? 'block' : 'none'; ?>;">
    <table>
        <tbody>
            <tr>
                <td class="cell-singular">
                    <label for="<?php echo $this->get_field_id('_caps'); ?>"><?php _e("Capabilities", "d4plib"); ?>:</label>
                    <input class="widefat" id="<?php echo $this->get_field_id('_caps'); ?>" name="<?php echo $this->get_field_name('_caps'); ?>" type="text" value="<?php echo esc_attr($instance['_caps']); ?>" />

                    <em>
                        <?php _e("One or more capabilities, separated by comma.", "d4plib"); ?>
                    </em>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<h4><?php _e("Visibility Filter", "d4plib"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('_hook'); ?>"><?php _e("Custom filter name", "d4plib"); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('_hook'); ?>" name="<?php echo $this->get_field_name('_hook'); ?>" type="text" value="<?php echo esc_attr($instance['_hook']); ?>" />

                <em>
                    <?php echo sprintf(__("Using this value, widget will run filter for the final visibility check. This filter will be %s, replace the %s with your custom filter name defined here.", "d4plib"), "<strong>'".$this->visibility_hook_format()."'</strong>", "<strong>'{filter_name}'</strong>"); ?>
                </em>
            </td>
        </tr>
    </tbody>
</table>