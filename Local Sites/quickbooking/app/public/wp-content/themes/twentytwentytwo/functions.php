<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */

add_filter('rest_authentication_errors', 'custom_rest_api_authorization');

function isOwner($type, $id) {
  explode("/", $_SERVER['REQUEST_URI'], -1);
}

function custom_rest_api_authorization($error) {

  if(strpos($_SERVER['REQUEST_URI'], '/jwt-auth/') === false) {
    if ( ! is_user_logged_in() && ! user_can( get_current_user_id(), 'export' ) ) {
        
      return new WP_Error(
          'rest_not_logged_in',
          __( 'Not logged in' ),
          array( 'status' => 403 )
      );
  
    }else{
      if(strpos($_SERVER['REQUEST_URI'], '/users/')) {
        $user_id = explode("/", $_SERVER['REQUEST_URI'])[5];
        if(get_current_user_id() != intval($user_id)) {

          return new WP_Error(
            'rest_not_owner',
            __( 'Not owner of resource.' ),
            array( 'status' => 403 )
        );

        }
       }      
    }
  } 

  $jwtclass = new Jwt_Auth_Public("jwt plugin", "1.3.2");

 return $error;
}



if ( ! function_exists( 'twentytwentytwo_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

	}

endif;

add_action( 'after_setup_theme', 'twentytwentytwo_support' );

if ( ! function_exists( 'twentytwentytwo_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'twentytwentytwo-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';
