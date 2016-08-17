<?php
/**
 * Title: Core Initializer
 *
 * Description: Initializes the core. Adds all required files.
 *
 * Please do not edit this file. This file is part of the Cyber Chimps Framework and all modifications
 * should be made in a child theme.
 *
 * @category Cyber Chimps Framework
 * @package  Framework
 * @since    1.0
 * @author   CyberChimps
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v3.0 (or later)
 * @link     http://www.cyberchimps.com/
 */

if ( !function_exists( 'cyberchimps_core_setup_theme' ) ):

// Setup the theme
	function cyberchimps_core_setup_theme() {

		// Set directory path
		$directory = get_template_directory();

		// Load core functions file
		require_once( $directory . '/cyberchimps/functions.php' );

		// Load core hooks file
		require_once( $directory . '/cyberchimps/inc/hooks.php' );

		// Load element files before meta and options
		require_once( $directory . '/elements/init.php' );

		// Load santize before options-init and options core
		require_once( $directory . '/cyberchimps/options/options-sanitize.php' );

		// Load core options file
		require_once( $directory . '/cyberchimps/options/options-init.php' );

		// Load core hooks file
		require_once( $directory . '/cyberchimps/inc/cc-custom-background.php' );

		// Load new meta box class
		require_once( $directory . '/cyberchimps/options/meta-box-class/my-meta-box-class.php' );

		// Load new meta box options
		require_once( $directory . '/cyberchimps/options/meta-box-class/meta-box.php' );

		// Load theme upsell.
		require_once( $directory . '/cyberchimps/options/theme-upsell.php' );

		// Core Translations can be filed in the /inc/languages/ directory
		load_theme_textdomain( 'cyberchimps_core', $directory . '/cyberchimps/lib/languages' );

		// Add support for the Aside Post Formats
		add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );

		// add theme support for backgrounds
		$defaults = array(
			'default-color'    => apply_filters( 'default_background_color', '' ),
			'default-image'    => apply_filters( 'default_background_image', '' ),
			'wp-head-callback' => 'cyberchimps_custom_background_cb'
		);

		$defaults = apply_filters( 'cyberchimps_background_default_args', $defaults );

		add_theme_support( 'custom-background', $defaults );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			                    'primary' => __( 'Primary Menu', 'cyberchimps_core' ),
		                    ) );
	}
endif; // cyberchimps_core_setup_theme
add_action( 'after_setup_theme', 'cyberchimps_core_setup_theme' );

function cyberchimps_custom_background_cb() {

	$style = "";
	// $background is the saved custom image, or the default image.
	$background = get_background_image();

	// $color is the saved custom color.
	// A default has to be specified in style.css. It will not be printed here.
	$color = esc_html(get_theme_mod( 'background_color' ));

	// CyberChimps background image
	$cc_background = esc_html(get_theme_mod( 'cyberchimps_background' ));
	
	if ( !$background && !$color && !$cc_background ) {
		return;
	}
	
	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = esc_html(get_theme_mod( 'background_repeat', 'repeat' ));
		if ( !in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) {
			$repeat = 'repeat';
		}
		$repeat = " background-repeat: $repeat;";

		$position = esc_html(get_theme_mod( 'background_position_x', 'left' ));
		if ( !in_array( $position, array( 'center', 'right', 'left' ) ) ) {
			$position = 'left';
		}
		$position = " background-position: top $position;";

		$attachment = esc_html(get_theme_mod( 'background_attachment', 'scroll' ));
		if ( !in_array( $attachment, array( 'fixed', 'scroll' ) ) ) {
			$attachment = 'scroll';
		}
		$attachment = " background-attachment: $attachment;";

		$style = $image . $repeat . $position . $attachment;
	}
	else if( $cc_background != 'none' && !empty( $cc_background ) ) {
		$img_url = get_template_directory_uri() . '/cyberchimps/lib/images/backgrounds/' . $cc_background . '.jpg';
		$style = "background-image: url( '$img_url' );";
	}
	else if( $color ) {
		$style = "background-color: #$color;";
		$style .= "background-image: none;";
	} ?>

	<style type="text/css">
		body {
		<?php echo trim( $style ); ?>
		}
	</style>
	
<?php
}

// Register our sidebars and widgetized areas.
function cyberchimps_widgets_init() {

	register_sidebar( array(
		                  'name'          => __( 'Sidebar Right', 'cyberchimps_core' ),
		                  'id'            => 'sidebar-right',
		                  'before_widget' => apply_filters( 'cyberchimps_sidebar_before_widget', '<aside id="%1$s" class="widget-container %2$s">' ),
		                  'after_widget'  => apply_filters( 'cyberchimps_sidebar_after_widget', '</aside>' ),
		                  'before_title'  => apply_filters( 'cyberchimps_sidebar_before_widget_title', '<h3 class="widget-title">' ),
		                  'after_title'   => apply_filters( 'cyberchimps_sidebar_after_widget_title', '</h3>' )
	                  ) );

	register_sidebar( array(
		                  'name'          => __( 'Footer Widgets', 'cyberchimps_core' ),
		                  'id'            => 'cyberchimps-footer-widgets',
		                  'before_widget' => apply_filters( 'cyberchimps_footer_before_widget', '<aside id="%1$s" class="widget-container span3 %2$s">' ),
		                  'after_widget'  => apply_filters( 'cyberchimps_footer_after_widget', '</aside>' ),
		                  'before_title'  => apply_filters( 'cyberchimps_footer_before_widget_title', '<h3 class="widget-title">' ),
		                  'after_title'   => apply_filters( 'cyberchimps_footer_after_widget_title', '</h3>' )
	                  ) );
}

add_action( 'widgets_init', 'cyberchimps_widgets_init' );

function cyberchimps_load_hooks() {

	// Set the path to hooks directory.
	$hooks_path = get_template_directory() . "/cyberchimps/hooks/";

	require_once( $hooks_path . 'wp-head-hooks.php' );
	require_once( $hooks_path . 'header-hooks.php' );
	require_once( $hooks_path . 'blog-hooks.php' );
	require_once( $hooks_path . 'page-hooks.php' );
	require_once( $hooks_path . 'footer-hooks.php' );
}

add_action( 'after_setup_theme', 'cyberchimps_load_hooks' );
