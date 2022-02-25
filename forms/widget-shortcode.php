<h4><?php _e( "Widget as a Shortcode", "d4plib" ); ?></h4>
<table>
    <tbody>
    <tr>
        <td class="cell-singular">
			<?php _e( "This widget has a shortcode equivalent with all the same settings. The shortcode is displayed below, and you can copy and use it in your posts or pages. This shortcode reflects latest saved widget settings - if you make changes to widget settings, save it, before getting shortcode from this tab.", "d4plib" ); ?>
        </td>
    </tr>
    <tr>
        <td class="cell-singular">
            <div class="cell-shortcode">
                [<?php echo esc_html( $this->shortcode_name ); ?> <?php echo $this->the_shortcode( $instance ); ?>]
            </div>
        </td>
    </tr>
    </tbody>
</table>