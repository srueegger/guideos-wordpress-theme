<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/* Globale CSS Datei, im Frontend und auch im Block Editor im Backend laden */
function guideos_theme_enqueue_styles_and_scripts() {
	$css_url = get_stylesheet_directory_uri() . '/assets/css/global.css';
	$modificated_css = date( 'YmdHis', filemtime( get_stylesheet_directory() . '/assets/css/global.css' ) );
  $modificated_css_darkmode = date( 'YmdHis', filemtime( get_stylesheet_directory() . '/assets/css/darkmode.css' ) );
	// Für das Frontend
	wp_enqueue_style( 'guideos-global-styles', $css_url, array(), $modificated_css );
  wp_enqueue_style( 'guideos-darkmode-styles', get_stylesheet_directory_uri() . '/assets/css/darkmode.css', array('guideos-global-styles'), $modificated_css_darkmode );
	// Für den Block-Editor
	if (is_admin()) {
		/* Custom Styles im Editor laden */
		wp_enqueue_style( 'guideos-block-editor-global-styles', $css_url, array(), $modificated_css );
    wp_enqueue_style( 'guideos-block-editor-darkmode-styles', get_stylesheet_directory_uri() . '/assets/css/darkmode.css', array('guideos-block-editor-global-styles'), $modificated_css_darkmode );
	}
}
add_action( 'wp_enqueue_scripts', 'guideos_theme_enqueue_styles_and_scripts', 100 );
add_action( 'enqueue_block_editor_assets', 'guideos_theme_enqueue_styles_and_scripts', 100 );

/* Kommentare komplett deaktivieren */
add_action('admin_init', function () {
  // Redirect any user trying to access comments page
  global $pagenow;
  
  if ($pagenow === 'edit-comments.php') {
      wp_safe_redirect(admin_url());
      exit;
  }

  // Remove comments metabox from dashboard
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');

  // Disable support for comments and trackbacks in post types
  foreach (get_post_types() as $post_type) {
      if (post_type_supports($post_type, 'comments')) {
          remove_post_type_support($post_type, 'comments');
          remove_post_type_support($post_type, 'trackbacks');
      }
  }
});

// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
  remove_menu_page('edit-comments.php');
});

// Remove comments links from admin bar
add_action('init', function () {
  if (is_admin_bar_showing()) {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  }
});

/* Remove Post Tags */
function unregister_tags() {
  unregister_taxonomy_for_object_type( 'post_tag', 'post' );
}
add_action( 'init', 'unregister_tags' );