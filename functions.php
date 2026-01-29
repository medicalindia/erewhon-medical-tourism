<?php
/**
 * Erewhon Medical Tourism Theme
 * Functions and definitions
 *
 * @package Erewhon
 */

if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Theme setup
 */
function erewhon_setup() {
	// Translation
	load_theme_textdomain( 'erewhon', get_template_directory() . '/languages' );
	
	// RSS feeds
	add_theme_support( 'automatic-feed-links' );
	
	// Document title
	add_theme_support( 'title-tag' );
	
	// Post thumbnails
	add_theme_support( 'post-thumbnails' );
	
	// Custom image sizes
	add_image_size( 'doctor-avatar', 300, 300, true );
	add_image_size( 'hospital-featured', 800, 500, true );
	add_image_size( 'treatment-card', 400, 300, true );
	
	// Navigation menus
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'erewhon' ),
		'utility' => esc_html__( 'Utility', 'erewhon' ),
	) );
	
	// HTML5 support
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );
	
	// Custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );
	
	// Editor styles
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-styles.css' );
}
add_action( 'after_setup_theme', 'erewhon_setup' );

/**
 * Content width
 */
function erewhon_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'erewhon_content_width', 1366 );
}
add_action( 'after_setup_theme', 'erewhon_content_width', 0 );

/**
 * Enqueue styles and scripts
 */
function erewhon_scripts() {
	$version = _S_VERSION;
	$css_uri = get_template_directory_uri() . '/assets/css/';
	$js_uri  = get_template_directory_uri() . '/assets/js/';
	
	// 1. Fonts (MUST load first)
	wp_enqueue_style( 
		'erewhon-fonts', 
		$css_uri . 'fonts.css', 
		array(), 
		$version 
	);

	// 2. Design tokens (Foundation)
	wp_enqueue_style(
		'erewhon-tokens',
		$css_uri . 'token.css',
		array(),
		$version
	);

	// 3. Base styles (Reset & Defaults)
	wp_enqueue_style(
		'erewhon-base',
		$css_uri . 'base.css',
		array( 'erewhon-fonts', 'erewhon-tokens' ),
		$version
	);

	// 4. Layout (Structure)
	wp_enqueue_style(
		'erewhon-layout',
		$css_uri . 'layout.css',
		array( 'erewhon-base' ),
		$version
	);

	// 5. Components (UI Elements)
	wp_enqueue_style(
		'erewhon-components',
		$css_uri . 'component.css',
		array( 'erewhon-layout' ),
		$version
	);

	// 6. Utilities (Helpers)
	wp_enqueue_style(
		'erewhon-utilities',
		$css_uri . 'utilities.css',
		array( 'erewhon-components' ),
		$version
	);

	// 7. Main Stylesheet (Metadata & Overrides)
	wp_enqueue_style( 
		'erewhon-style',
		get_stylesheet_uri(),
		array( 'erewhon-utilities' ),
		$version 
	);
	
	// JavaScript
	wp_enqueue_script( 
		'erewhon-navigation', 
		$js_uri . 'navigation.js', 
		array(), 
		$version, 
		true 
	);
}
add_action( 'wp_enqueue_scripts', 'erewhon_scripts' );

/**
 * Preload critical fonts for performance
 */
function erewhon_preload_fonts() {
	?>
	<link rel="preload" href="<?php echo esc_url( get_template_directory_uri() . '/assets/fonts/Outfit-VariableFont_wght.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<?php
}
add_action( 'wp_head', 'erewhon_preload_fonts', 1 );

/**
 * Excerpt length
 */
function erewhon_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'erewhon_excerpt_length', 999 );

/**
 * Excerpt more
 */
function erewhon_excerpt_more( $more ) {
	return '...';
}
add_filter( 'excerpt_more', 'erewhon_excerpt_more' );

/**
 * Load required Underscores files
 */
$required_files = array(
	'/inc/custom-header.php',
	'/inc/template-tags.php',
	'/inc/template-functions.php',
	'/inc/customizer.php',
	'/inc/data-helpers.php',
	'/inc/seo-helpers.php',
);

foreach ( $required_files as $file ) {
	$filepath = get_template_directory() . $file;
	if ( file_exists( $filepath ) ) {
		require $filepath;
	}
}

/**
 * Load ACF Field Groups
 */
$acf_fields = get_template_directory() . '/inc/acf-fields.php';

if ( file_exists( $acf_fields ) ) {
	require $acf_fields;
	
	// Debug logging (only if WP_DEBUG is true)
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( 'Erewhon: ACF fields loaded from ' . $acf_fields );
	}
}