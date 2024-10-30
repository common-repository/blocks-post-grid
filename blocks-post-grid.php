<?php
/**
 * Plugin Name: Gutenberg - Blocks Post Grid
 * Description: Dynamic Post Layout for Gutenberg. It is a simple plugin that allows you to create blog posts for Gutenberg.
 * Version:     1.0.3
 * Author:      Jakir Hasan
 * Author URI: https://github.com/jakirhasan
 * Text Domain: blocks-post-grid
 * License:     GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Define Version
define('GPG_VERSION', '1.0.0');

// Define License
define('GPG_LICENSE', 'free');


// Define Dir URL
define('GPG_DIR_URL', plugin_dir_url(__FILE__));

// Define Physical Path
define('GPG_DIR_PATH', plugin_dir_path(__FILE__));

// Enqueue JS and CSS
require_once GPG_DIR_PATH.'lib/enqueue-scripts.php';

// Dynamic Blocks
require_once GPG_DIR_PATH.'blocks/postgrid/index.php';
require_once GPG_DIR_PATH.'blocks/postlist/index.php';








