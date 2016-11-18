<?php
/*
Plugin Name: WSUWP YouTube Embed
Version: 0.0.1
Description: Embed YouTube videos using the YouTube JS API
Author: washingtonstateuniversity, jeremyfelt
Author URI: https://web.wsu.edu/
Plugin URI: https://github.com/washingtonstateuniversity/WSUWP-Plugin-YouTube-Embed
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// The core plugin class.
require dirname( __FILE__ ) . '/includes/class-wsuwp-youtube-embed.php';

add_action( 'after_setup_theme', 'WSUWP_YouTube_Embed' );
/**
 * Start things up.
 *
 * @return \WSUWP_YouTube_Embed
 */
function WSUWP_YouTube_Embed() {
	return WSUWP_YouTube_Embed::get_instance();
}
