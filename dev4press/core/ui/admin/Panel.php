<?php

namespace Dev4Press\v38\Core\UI\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Panel {
	static private $_current_instance = null;

	/** @var \Dev4Press\v38\Core\Admin\Plugin|\Dev4Press\v38\Core\Admin\Menu\Plugin|\Dev4Press\v38\Core\Admin\Submenu\Plugin */
	private $admin;

	/** @var \Dev4Press\v38\Core\UI\Admin\Render */
	private $render;

	protected $sidebar = true;
	protected $form = false;
	protected $table = false;
	protected $subpanels = array();
	protected $render_class = '\\Dev4Press\\v38\\Core\\UI\\Admin\\Render';
	protected $wrapper_class = '';
	protected $default_subpanel = 'index';

	public $storage = array();

	public function __construct( $admin ) {
		$this->admin = $admin;

		$render       = $this->render_class;
		$this->render = $render::instance();

		add_action( 'load_' . $this->a()->screen_id, array( $this, 'screen_options_show' ) );
		add_filter( 'set-screen-option', array( $this, 'screen_options_save' ), 10, 3 );

		add_action( $this->h( 'enqueue_scripts' ), array( $this, 'enqueue_scripts' ) );
		add_action( $this->h( 'enqueue_scripts_early' ), array( $this, 'enqueue_scripts_early' ) );
	}

	/** @return \Dev4Press\v38\Core\UI\Admin\Panel */
	public static function instance( $admin = null ) {
		$class = get_called_class();

		if ( is_null( self::$_current_instance ) && ! is_null( $admin ) ) {
			self::$_current_instance = new $class( $admin );
		}

		return self::$_current_instance;
	}

	public function a() {
		return $this->admin;
	}

	public function r() {
		return $this->render;
	}

	public function h( $hook ) : string {
		return $this->a()->plugin_prefix . '_' . $hook;
	}

	public function subpanels() : array {
		return $this->subpanels;
	}

	public function current_subpanel() : string {
		$_subpanel = $this->a()->subpanel;

		if ( ! empty( $this->subpanels ) ) {
			$_available = array_keys( $this->subpanels );

			if ( ! in_array( $_subpanel, $_available ) ) {
				$_subpanel = $this->default_subpanel;
			}
		}

		return $_subpanel;
	}

	public function has_form() : bool {
		return $this->form;
	}

	public function has_sidebar() : bool {
		return $this->sidebar;
	}

	public function has_table() : bool {
		return $this->table;
	}

	public function validate_subpanel( $name ) {
		if ( empty( $this->subpanels ) ) {
			return '';
		}

		if ( isset( $this->subpanels[ $name ] ) ) {
			return $name;
		}

		$valid = array_keys( $this->subpanels );

		return $valid[0];
	}

	public function enqueue_scripts() {

	}

	public function screen_options_show() {
	}

	public function screen_options_save( $status, $option, $value ) {
		return $status;
	}

	public function wrapper_class() : array {
		$_classes = array(
			'd4p-wrap',
			'd4p-plugin-' . $this->a()->plugin,
			'd4p-panel-' . $this->a()->panel
		);

		$_subpanel = $this->current_subpanel();

		if ( ! empty( $_subpanel ) ) {
			$_classes[] = 'd4p-subpanel-' . $_subpanel;
		}

		if ( $this->has_sidebar() ) {
			$_classes[] = 'd4p-with-sidebar';
		} else {
			$_classes[] = 'd4p-full-width';
		}

		if ( $this->table ) {
			$_classes[] = 'd4p-with-table';
		}

		if ( ! empty( $this->wrapper_class ) ) {
			$_classes[] = $this->wrapper_class;
		}

		return $_classes;
	}

	public function prepare() {
	}

	public function show() {
		$this->include_header();

		echo '<div class="d4p-inside-wrapper">';
		if ( $this->has_form() ) {
			echo $this->form_tag_open();
		}

		echo '<div class="d4p-content-wrapper">';
		if ( $this->has_sidebar() ) {
			$this->include_sidebar();
		}

		$this->include_content();
		echo '</div>';

		if ( $this->has_form() ) {
			echo $this->form_tag_close();
		}
		echo '</div>';

		$this->include_footer();
	}

	public function forms_path_library() : string {
		return $this->a()->path . 'd4plib/forms/';
	}

	public function forms_path_plugin() : string {
		return $this->a()->path . 'forms/';
	}

	public function include_header( $name = '' ) {
		$name = empty( $name ) ? $this->a()->panel : $name;

		$this->interface_colors();
		$this->load( 'header-' . $name . '.php', 'header.php' );
	}

	public function include_footer( $name = '' ) {
		$name = empty( $name ) ? $this->a()->panel : $name;
		$this->load( 'footer-' . $name . '.php', 'footer.php' );
	}

	public function include_sidebar( $name = '' ) {
		$name = empty( $name ) ? $this->a()->panel : $name;
		$this->load( 'sidebar-' . $name . '.php', 'sidebar.php' );
	}

	public function include_messages() {
		$this->load( 'message.php' );
	}

	public function include_notices() {
		if ( $this->a()->panel == 'dashboard' ) {
			if ( $this->a()->settings()->i()->is_bbpress_plugin ) {
				$this->load( 'notices-bbpress.php' );
			}
		}
	}

	public function include_content() {
		$main = $name = 'content-' . $this->a()->panel;

		if ( ! empty( $this->a()->subpanel ) ) {
			$name .= '-' . $this->a()->subpanel;
		}

		$main .= '.php';
		$name .= '.php';

		$this->load( $name, $main );
	}

	public function form_tag_open() : string {
		$id = $this->a()->plugin_prefix . '-form-' . $this->a()->panel;

		return '<form method="post" action="" id="' . esc_attr( $id ) . '" enctype="multipart/form-data" autocomplete="off">';
	}

	public function form_tag_close() : string {
		return '</form>';
	}

	public function enqueue_scripts_early() {

	}

	public function include_accessibility_control() {
	}

	protected function interface_colors() {
		if ( $this->a()->auto_mod_interface_colors ) {
			?>

            <style>
                .<?php echo 'd4p-plugin-'.esc_html( $this->a()->plugin ); ?> {
                    --d4p-color-layout-accent: <?php echo esc_html( $this->a()->settings()->i()->color() ); ?>;
                    --d4p-color-sidebar-icon-text: <?php echo esc_html( $this->a()->settings()->i()->color() ); ?>;
                }
            </style>

			<?php
		}
	}

	protected function load( $name, $fallback = 'fallback.php' ) {
		if ( file_exists( $this->forms_path_plugin() . $name ) ) {
			include( $this->forms_path_plugin() . $name );
		} else if ( file_exists( $this->forms_path_library() . $name ) ) {
			include( $this->forms_path_library() . $name );
		} else if ( file_exists( $this->forms_path_plugin() . $fallback ) ) {
			include( $this->forms_path_plugin() . $fallback );
		} else {
			include( $this->forms_path_library() . $fallback );
		}
	}
}
