<?php

if (!defined('ABSPATH')) exit;

$_dismiss_url = d4p_panel()->a()->current_url(false).'&'.d4p_panel()->a()->v().'=getback&action=';
$_utm_medium = 'plugin-'.d4p_panel()->a()->plugin;

if (!d4p_has_plugin('gd-power-search-for-bbpress') && d4p_panel()->a()->settings()->get('notice_gdpos_hide', 'core') === false) {
    $url = 'https://plugins.dev4press.com/gd-power-search-for-bbpress/';
    $url = d4p_url_campaign_tracking($url, 'front-panel', $_utm_medium);

    ?>

    <div class="d4p-notice-info">
        <?php echo sprintf(__("Please, take a few minutes to check out another Dev4Press plugin for bbPress: %s.", "d4plib"), '<strong>GD Power Search Pro for bbPress</strong>'); ?>
        <blockquote>Enhanced and powerful search for bbPress powered forums, with options to filter results by post author, forums, publication period, topic tags and few other things.</blockquote>
        <a target="_blank" href="<?php echo $url; ?>" class="button-primary"><?php _e("Plugin Home Page", "d4plib"); ?></a>
        <a href="<?php echo $_dismiss_url; ?>dismiss-power-search" class="button-secondary"><?php _e("Do not show this notice anymore", "d4plib"); ?></a>
    </div>

    <?php
} else if (!d4p_has_plugin('gd-bbpress-toolbox') && d4p_panel()->a()->settings()->get('notice_gdbbx_hide', 'core') === false) {
    $url = 'https://plugins.dev4press.com/gd-bbpress-toolbox/';
    $url = d4p_url_campaign_tracking($url, 'front-panel', $_utm_medium);

    ?>

    <div class="d4p-notice-info">
        <?php echo sprintf(__("Please, take a few minutes to check out another Dev4Press plugin for bbPress: %s.", "d4plib"), '<strong>GD bbPress Toolbox Pro</strong>'); ?>
        <blockquote>Expand bbPress powered forums with attachments upload, BBCodes support, signatures, widgets, quotes, toolbar menu, activity tracking, enhanced widgets, extra views...</blockquote>
        <a target="_blank" href="<?php echo $url; ?>" class="button-primary"><?php _e("Plugin Home Page", "d4plib"); ?></a>
        <a href="<?php echo $_dismiss_url; ?>dismiss-bbpress-toolbox" class="button-secondary"><?php _e("Do not show this notice anymore", "d4plib"); ?></a>
    </div>

    <?php

} else if (!d4p_has_plugin('gd-topic-prefix') && d4p_panel()->a()->settings()->get('notice_gdtox_hide', 'core') === false) {
    $url = 'https://plugins.dev4press.com/gd-topic-prefix/';
    $url = d4p_url_campaign_tracking($url, 'front-panel', $_utm_medium);

    ?>

    <div class="d4p-notice-info">
        <?php echo sprintf(__("Please, take a few minutes to check out another Dev4Press plugin for bbPress: %s.", "d4plib"), '<strong>GD Topic Prefix Pro for bbPress</strong>'); ?>
        <blockquote>Implements topic prefixes system, with support for styling customization, forum specific prefix groups with use of user roles, default prefixes, filtering of topics by prefix and more.</blockquote>
        <a target="_blank" href="<?php echo $url; ?>" class="button-primary"><?php _e("Plugin Home Page", "d4plib"); ?></a>
        <a href="<?php echo $_dismiss_url; ?>dismiss-topic-prefix" class="button-secondary"><?php _e("Do not show this notice anymore", "d4plib"); ?></a>
    </div>

    <?php
} else if (!d4p_has_plugin('gd-topic-polls') && d4p_panel()->a()->settings()->get('notice_gdpol_hide', 'core') === false) {
    $url = 'https://plugins.dev4press.com/gd-topic-polls/';
    $url = d4p_url_campaign_tracking($url, 'front-panel', $_utm_medium);

    ?>

    <div class="d4p-notice-info">
        <?php echo sprintf(__("Please, take a few minutes to check out another Dev4Press plugin for bbPress: %s.", "d4plib"), '<strong>GD Topic Polls Pro for bbPress</strong>'); ?>
        <blockquote>Implements polls system for bbPress powered forums, where users can add polls to topics, with a wide range of settings to control voting, poll closing, display of results and more.</blockquote>
        <a target="_blank" href="<?php echo $url; ?>" class="button-primary"><?php _e("Plugin Home Page", "d4plib"); ?></a>
        <a href="<?php echo $_dismiss_url; ?>dismiss-topic-polls" class="button-secondary"><?php _e("Do not show this notice anymore", "d4plib"); ?></a>
    </div>

    <?php
} else if (!d4p_has_plugin('gd-quantum-theme-for-bbpress') && d4p_panel()->a()->settings()->get('notice_gdqnt_hide', 'core') === false) {
    $url = 'https://plugins.dev4press.com/gd-quantum-theme-for-bbpress/';
    $url = d4p_url_campaign_tracking($url, 'front-panel', $_utm_medium);

    ?>

    <div class="d4p-notice-info">
        <?php echo sprintf(__("Please, take a few minutes to check out another Dev4Press plugin for bbPress: %s.", "d4plib"), '<strong>GD Quantum Theme Pro for bbPress</strong>'); ?>
        <blockquote>Responsive and modern theme to fully replace default bbPress theme templates and styles, with multiple colour schemes and Customizer integration for more control.</blockquote>
        <a target="_blank" href="<?php echo $url; ?>" class="button-primary"><?php _e("Plugin Home Page", "d4plib"); ?></a>
        <a href="<?php echo $_dismiss_url; ?>dismiss-quantum-theme" class="button-secondary"><?php _e("Do not show this notice anymore", "d4plib"); ?></a>
    </div>

    <?php

} else if (!d4p_has_plugin('gd-members-directory-for-bbpress') && d4p_panel()->a()->settings()->get('notice_gdmed_hide', 'core') === false) {
    $url = 'https://plugins.dev4press.com/gd-members-directory-for-bbpress/';
    $url = d4p_url_campaign_tracking($url, 'front-panel', $_utm_medium);

    ?>

    <div class="d4p-notice-info">
        <?php echo sprintf(__("Please, take a few minutes to check out another Dev4Press plugin for bbPress: %s.", "d4plib"), '<strong>GD Members Directory Pro for bbPress</strong>'); ?>
        <blockquote>Easy to use plugin for adding forum members directory page into bbPress powered forums including members filtering and additional widgets for listing members in the sidebar.</blockquote>
        <a target="_blank" href="<?php echo $url; ?>" class="button-primary"><?php _e("Plugin Home Page", "d4plib"); ?></a>
        <a href="<?php echo $_dismiss_url; ?>dismiss-members-directory" class="button-secondary"><?php _e("Do not show this notice anymore", "d4plib"); ?></a>
    </div>

    <?php

}
