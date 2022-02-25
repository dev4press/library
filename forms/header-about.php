<?php

use function Dev4Press\v37\Functions\panel;

$_subpanels = panel()->subpanels();
$_panel     = panel()->a()->panel;
$_subpanel  = panel()->a()->subpanel;

if ( ! empty( $_subpanels ) ) {
	$_available = array_keys( $_subpanels );

	if ( ! in_array( $_subpanel, $_available ) ) {
		$_subpanel = 'whatsnew';
	}
}

$_classes = array(
	'd4p-wrap',
	'd4p-page-about',
	'd4p-panel-' . $_panel
);

if ( ! empty( $_subpanel ) ) {
	$_classes[] = 'd4p-subpanel-' . $_subpanel;
}

?>
<div class="<?php echo join( ' ', $_classes ); ?>">
    <div class="d4p-about-head-wrapper">
        <div class="d4p-about-information">
            <h1><?php printf( __( "Welcome to %s %s&nbsp;%s", "d4plib" ), panel()->a()->title(), ucfirst( panel()->a()->settings()->i()->edition ), panel()->a()->settings()->i()->version ); ?></h1>
            <p class="d4p-about-text">
				<?php echo panel()->a()->settings()->i()->description(); ?>
            </p>
        </div>
        <div class="d4p-about-badge">
            <div class="d4p-about-badge-inner" style="background-color: <?php echo esc_attr( panel()->a()->settings()->i()->color() ); ?>;">
				<?php echo panel()->r()->icon( 'plugin-' . panel()->a()->plugin ); ?>
				<?php printf( __( "Version %s", "d4plib" ), panel()->a()->settings()->i()->version ); ?>
            </div>
        </div>
    </div>

    <h2 class="nav-tab-wrapper wp-clearfix">
		<?php

		if ( panel()->a()->variant == 'submenu' ) {
			echo '<a href="' . esc_url( panel()->a()->panel_url() ) . '" class="nav-tab"><i class="d4p-icon d4p-ui-home"></i></a>';
		}

		foreach ( $_subpanels as $_tab => $obj ) {
			echo '<a href="' . esc_url( panel()->a()->panel_url( 'about', $_tab ) ) . '" class="nav-tab' . ( $_tab == $_subpanel ? ' nav-tab-active' : '' ) . '">' . $obj['title'] . '</a>';
		}

		?>
    </h2>

    <div class="d4p-about-inner">
