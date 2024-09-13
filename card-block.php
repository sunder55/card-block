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


