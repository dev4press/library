<?php

$list_users_visibility = array(
    'all' => __("Everyone"),
    'users' => __("Logged in users"),
    'visitors' => __("Not logged in visitors"),
    'roles' => __("Users with specified roles"),
    'caps' => __("Users with specified capabilities")
);

?>
<h4><?php _e("Widget Visibility", "d4plib"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('_users'); ?>"><?php _e("Show widget to", "d4plib"); ?>:</label>
                <?php d4p_render_select($list_users_visibility, array('id' => $this->get_field_id('_users'), 'class' => 'widefat', 'name' => $this->get_field_name('_users'), 'selected' => $instance['_users'])); ?>
            </td>
        </tr>
    </tbody>
</table>
