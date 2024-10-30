<?php
namespace Blocks_Post_Grid\Post_Grid;
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\gpg_enqueue_block_editor_assets' );

if ( ! function_exists( 'gpg_load_textdomain' ) ) {
    function gpg_load_textdomain() {
        load_plugin_textdomain( 'blocks-post-grid', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
    }
}

function gpg_enqueue_block_editor_assets() {
	$js_path = 'assets/js/editor.blocks.js';
	$css_path = 'assets/css/blocks.editor.css';
	wp_enqueue_script( 'gpg-blocks-js', GPG_DIR_URL . $js_path, [ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor' ], filemtime( GPG_DIR_PATH . $js_path ) );
	wp_enqueue_style( 'gpg-blocks-editor-css', GPG_DIR_URL . $css_path, [ ], filemtime( GPG_DIR_PATH . $css_path ) );
}

add_action( 'enqueue_block_assets', __NAMESPACE__ . '\gpg_enqueue_assets' );

function gpg_enqueue_assets() {
	$css_path = 'assets/css/blocks.style.css';
	wp_enqueue_style( 'gpg-blocks', GPG_DIR_URL . $css_path, null, filemtime( GPG_DIR_PATH . $css_path ) );
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\gpg_enqueue_frontend_assets' );


function gpg_enqueue_frontend_assets() {
	if ( is_admin() ) {
		return;
	}
	$js_path = 'assets/js/frontend.blocks.js';
	wp_enqueue_script( 'gpg-blocks-frontend', GPG_DIR_URL . $js_path, [], filemtime( GPG_DIR_PATH . $js_path ) );
}


// Block Categories
add_filter( 'block_categories', function( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'blocks-post-grid',
				'title' => __( 'Blocks Post Grid', 'blocks-post-grid' ),
			),
		)
	);
}, 10, 2 );