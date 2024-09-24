<?php
/**
 * Plugin Name:       Card Block
 * Description:       Card Block for Webstarter
 * Requires at least: 6.6
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            CPM
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       card-block
 *
 * @package CreateBlock
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}


/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_card_block_block_init()
{
	register_block_type(__DIR__ . '/build');
}
add_action('init', 'create_block_card_block_block_init');

// Ensure render.php is included
// require_once __DIR__ . '/src/render.php';?


// function swiper_slide_enqueue_script()
// {
// 	// Enqueue Swiper CSS
// 	wp_enqueue_style(
// 		'swiper-style',
// 		'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css',
// 		array(),
// 		'8.0.7' // Swiper version
// 	);

// 	// Enqueue Swiper JS
// 	wp_enqueue_script(
// 		'swiper-script',
// 		'https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js',
// 		array('jquery'), // Add jQuery as a dependency if needed
// 		'8.0.7', // Swiper version
// 		true // Load in footer
// 	);
// }
// add_action('wp_enqueue_scripts', 'swiper_slide_enqueue_script');




function enqueue_slick_slider()
{
	// Enqueue Slick Slider CSS
	wp_enqueue_style('slick-slider-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), '1.8.1');

	// Enqueue Slick Slider theme CSS (optional, for default styling)
	wp_enqueue_style('slick-slider-theme-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css', array(), '1.8.1');

	// Enqueue jQuery (WordPress includes jQuery by default, so this is for safety)

	// Enqueue Slick Slider JS
	wp_enqueue_script('slick-slider-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '1.8.1', true);

	// Optionally, enqueue a custom script to initialize Slick Slider
	wp_enqueue_script('custom-slick-init', get_template_directory_uri() . '/assets/js/custom-slick-init.js', array('slick-slider-js'), false, true);
}
add_action('wp_enqueue_scripts', 'enqueue_slick_slider');



function card_block_register_block_styles()
{
	// Enqueue custom style for the 'card-block' block from theme
	wp_enqueue_block_style(
		'create-block/card-block', // Block name: namespace/block-name (ensure this matches your block)
		array(
			'handle' => 'card-block-style', // Unique handle for the style
			'src' => get_template_directory_uri() . '/assets/public/css/wstr_card_block_style.css', // Path to the CSS file in your theme
			'path' => get_template_directory() . '/assets/css/card-style.css', // Path to file for versioning
			'ver' => filemtime(get_template_directory() . '/assets/css/card-style.css'), // Version based on file modification time
		)
	);
}
add_action('init', 'card_block_register_block_styles');