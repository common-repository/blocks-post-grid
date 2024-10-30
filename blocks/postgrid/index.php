<?php

function gpg_render_block_latest_postgrid( $attributes, $content ) {
    $categories = isset( $attributes['categories'] ) ? $attributes['categories'] : '';
    $recent_posts = wp_get_recent_posts( array(
        'post_type' => $attributes['post_type'],
        'numberposts' => $attributes['postsToShow'],
        'order' => $attributes['order'],
		'orderby' => $attributes['orderBy'],
        'post_status' => 'publish',
        'category' => $categories,
    ) );
    if ( count( $recent_posts ) === 0 ) {
        return 'No posts';
    }
    $headingSelector = isset( $attributes['heading'] ) ? $attributes['heading'] : 'h3';

    $list_item_markup = '';

	foreach ( $recent_posts as $post ) {
		$post_id = $post['ID'];

		$title = get_the_title( $post_id );
        $titlelink = get_permalink( $post_id );
        $post_thumb_id = get_post_thumbnail_id( $post_id );

        $list_item_markup .= sprintf( '<div class="gpg-grid-post">' );

        // Category.
        if ( isset( $attributes['displayCategory'] ) && $attributes['displayCategory'] ) {
            $catItem = sprintf(
                '<div class="gpg-category-grid gpg-category-grid-withimg">%1$s</div>',
                get_the_category_list( esc_html__( ' ', 'blocks-post-grid' ), '', $post_id )
            );
        }

        // Get the featured image
        if ( isset( $attributes['displayPostImage'] ) && $attributes['displayPostImage'] ) {
            if ( has_post_thumbnail( $post_id ) ) {
                $list_item_markup .= sprintf(
                    '<div class="gpg-post-grid-image">%1$s<a href="%2$s" rel="bookmark">%3$s</a></div>',
                    $catItem,
                    esc_url( $titlelink ),
                    wp_get_attachment_image( $post_thumb_id, $attributes['imageCrop'] )
                );
            } else {
                $list_item_markup .= $catItem;
            }
        } else {
            $list_item_markup .= $catItem;
        }

        //title
        if ( isset( $attributes['displayPostTitle'] ) && $attributes['displayPostTitle'] ) {
            $list_item_markup .= sprintf(
                '<'.$headingSelector.' class="gpg-post-grid-title"><a href="%1$s">%2$s</a></'.$headingSelector.'>',
                esc_url( $titlelink ),
                esc_html( $title )
            );
        }

        // Get the post author
        if ( $attributes['displayPostAuthor'] || $attributes['displayPostDate'] || $attributes['displayCategory'] || $attributes['displayComments'] ) {
            $list_item_markup .= sprintf( '<div class="gpg-grid-post-meta">' );
            if ( isset( $attributes['displayPostAuthor'] ) && $attributes['displayPostAuthor'] ) {

				$list_item_markup .= sprintf(
					'<span class="gpg-post-grid-author"><a target="_blank" href="%1$s">%2$s</a></span>',
                    esc_url( get_author_posts_url( $post['post_author'] ) ),
                    esc_html( get_the_author_meta( 'display_name', $post['post_author'] ) )
				);
            }
            // Get the post date
            if ( isset( $attributes['displayPostDate'] ) && $attributes['displayPostDate'] ) {
                $list_item_markup .= sprintf(
                    '<span class="gpg-post-grid-date"><time datetime="%1$s">%2$s</time></span>',
                    esc_attr( get_the_date( 'c', $post_id ) ),
                    esc_html( get_the_date( '', $post_id ) )
                );
            }
            //comment
            if ( isset( $attributes['displayComments'] ) && $attributes['displayComments'] ) {    
                $num = get_comments_number( $post_id );
                $num = sprintf( _n( '%d comment', '%d comments', $num, 'blocks-post-grid' ), $num );
                $list_item_markup .= sprintf( '<span class="gpg-post-comment">%1$s</span>', $num );
            }
            $list_item_markup .= sprintf( '</div>' );
        }

        //content
        if ( isset( $attributes['displayExcerpt'] ) && $attributes['displayExcerpt'] ) {
            $limit = isset( $attributes['excerptlimit'] ) ? $attributes['excerptlimit'] : '';
            $post_content = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );
            $excerpt = apply_filters( 'the_excerpt', wp_trim_words( $post_content, $limit ) );
            if ( ! empty( $excerpt ) ) {
                $list_item_markup .= sprintf(
                    '<div class="gpg-blog-posts-content">%1$s</div>',
                    wp_kses_post( $excerpt )
                );
            }

        }

        //read more
        if ( isset( $attributes['displayPostLink'] ) && $attributes['displayPostLink'] ) {
            $readMore = isset( $attributes['readMoreText'] ) ? $attributes['readMoreText'] : 'Continue Reading';
            $list_item_markup .= sprintf(
                '<div class="gpg-post-grid-link"><a href="%1$s" rel="bookmark">%2$s</a></div>',
                esc_url( get_permalink( $post_id ) ),
                esc_html( $readMore )
            );
        }

        $list_item_markup .= sprintf( '</div>' );
    }//end foreach

    if ( isset( $attributes['columns'] ) && $attributes['columns'] ) {
		$col = 'gpg-grid-post-item gpg-grid-post-column-' . $attributes["columns"];
    }
    $mainStyles = array();
    //title style
    if ( ! empty( $attributes['textColor'] ) ) {
        $mainStyles[] = '--gpg-color: ' . $attributes['textColor'];
    }
    if ( ! empty( $attributes['textHoverColor'] ) ) {
        $mainStyles[] = '--gpg-hover-color: ' . $attributes['textHoverColor'];
    }
    if ( ! empty( $attributes['titleSize'] ) ) {
        $mainStyles[] = '--gpg-font-size: ' . $attributes['titleSize'].'px';
    }
    if ( ! empty( $attributes['titlelineHeight'] ) ) {
        $mainStyles[] = '--gpg-font-line: ' . $attributes['titlelineHeight'].'px';
    }
    if ( ! empty( $attributes['titleletterSpacing'] ) ) {
        $mainStyles[] = '--gpg-font-space: ' . $attributes['titleletterSpacing'].'px';
    }
    if ( ! empty( $attributes['titleWeight'] ) ) {
        $mainStyles[] = '--gpg-font-weight: ' . $attributes['titleWeight'];
    }
    if ( ! empty( $attributes['titleTransform'] ) ) {
        $mainStyles[] = '--gpg-font-transform: ' . $attributes['titleTransform'];
    }
    if ( ! empty( $attributes['titleDecoration'] ) ) {
        $mainStyles[] = '--gpg-font-decoration: ' . $attributes['titleDecoration'];
    }
    if ( ! empty( $attributes['titleStyle'] ) ) {
        $mainStyles[] = '--gpg-font-style: ' . $attributes['titleStyle'];
    }
    if ( ! empty( $attributes['titleMargin'] ) ) {
        $mainStyles[] = '--gpg-font-margin: ' . $attributes['titleMargin'].'px';
    }

    // meta
    if ( ! empty( $attributes['metaColor'] ) ) {
        $mainStyles[] = '--gpg-meta-color: ' . $attributes['metaColor'];
    }
    if ( ! empty( $attributes['metaSize'] ) ) {
        $mainStyles[] = '--gpg-meta-font-size: ' . $attributes['metaSize'].'px';
    }
    if ( ! empty( $attributes['metalineHeight'] ) ) {
        $mainStyles[] = '--gpg-meta-line-height: ' . $attributes['metalineHeight'].'px';
    }
    if ( ! empty( $attributes['metaWeight'] ) ) {
        $mainStyles[] = '--gpg-meta-weight: ' . $attributes['metaWeight'];
    }
    if ( ! empty( $attributes['metaTransform'] ) ) {
        $mainStyles[] = '--gpg-meta-transform: ' . $attributes['metaTransform'];
    }
    if ( ! empty( $attributes['metaStyle'] ) ) {
        $mainStyles[] = '--gpg-meta-style: ' . $attributes['metaStyle'];
    }
    if ( ! empty( $attributes['metaMargin'] ) ) {
        $mainStyles[] = '--gpg-meta-margin: ' . $attributes['metaMargin'].'px';
    }

    // Content
    if ( ! empty( $attributes['contentColor'] ) ) {
        $mainStyles[] = '--gpg-content-color: ' . $attributes['contentColor'];
    }
    if ( ! empty( $attributes['contentSize'] ) ) {
        $mainStyles[] = '--gpg-content-font-size: ' . $attributes['contentSize'].'px';
    }
    if ( ! empty( $attributes['contentlineHeight'] ) ) {
        $mainStyles[] = '--gpg-content-line-height: ' . $attributes['contentlineHeight'].'px';
    }
    if ( ! empty( $attributes['contentWeight'] ) ) {
        $mainStyles[] = '--gpg-content-weight: ' . $attributes['contentWeight'];
    }
    if ( ! empty( $attributes['contentTransform'] ) ) {
        $mainStyles[] = '--gpg-content-transform: ' . $attributes['contentTransform'];
    }
    if ( ! empty( $attributes['ContentMargin'] ) ) {
        $mainStyles[] = '--gpg-content-margin: ' . $attributes['ContentMargin'].'px';
    }

    //Button
    if ( ! empty( $attributes['buttonColor'] ) ) {
        $mainStyles[] = '--gpg-btn-color: ' . $attributes['buttonColor'];
    }
    if ( ! empty( $attributes['buttonHoverColor'] ) ) {
        $mainStyles[] = '--gpg-btn-hover-color: ' . $attributes['buttonHoverColor'];
    }
    if ( ! empty( $attributes['buttonBgColor'] ) ) {
        $mainStyles[] = '--gpg-btn-bg-color: ' . $attributes['buttonBgColor'];
    }
    if ( ! empty( $attributes['buttonBgHoverColor'] ) ) {
        $mainStyles[] = '--gpg-btn-bg-hover-color: ' . $attributes['buttonBgHoverColor'];
    }
    if ( ! empty( $attributes['buttonSize'] ) ) {
        $mainStyles[] = '--gpg-btn-font-size: ' . $attributes['buttonSize'].'px';
    }
    if ( ! empty( $attributes['buttonlineHeight'] ) ) {
        $mainStyles[] = '--gpg-btn-line-height: ' . $attributes['buttonlineHeight'].'px';
    }
    if ( ! empty( $attributes['buttonletterSpacing'] ) ) {
        $mainStyles[] = '--gpg-btn-letter-space: ' . $attributes['buttonletterSpacing'].'px';
    }
    if ( ! empty( $attributes['buttonFontWeight'] ) ) {
        $mainStyles[] = '--gpg-btn-font-weight: ' . $attributes['buttonFontWeight'];
    }
    if ( ! empty( $attributes['buttonTransform'] ) ) {
        $mainStyles[] = '--gpg-btn-transform: ' . $attributes['buttonTransform'];
    }
    if ( ! empty( $attributes['buttonDecoration'] ) ) {
        $mainStyles[] = '--gpg-btn-decoration: ' . $attributes['buttonDecoration'];
    }
    if ( ! empty( $attributes['buttonStyle'] ) ) {
        $mainStyles[] = '--gpg-btn-style: ' . $attributes['buttonStyle'];
    }
    if ( ! empty( $attributes['buttonWidth'] ) ) {
        $mainStyles[] = '--gpg-btn-width: ' . $attributes['buttonWidth'].'px';
    }
    if ( ! empty( $attributes['buttonHeight'] ) ) {
        $mainStyles[] = '--gpg-btn-height: ' . $attributes['buttonHeight'].'px';
    }
    if ( ! empty( $attributes['buttonradius'] ) ) {
        $mainStyles[] = '--gpg-btn-radius: ' . $attributes['buttonradius'].'px';
    }
    if ( ! empty( $attributes['buttonBorderColor'] ) ) {
        $mainStyles[] = '--gpg-btn-border-color: ' . $attributes['buttonBorderColor'];
    }
    if ( ! empty( $attributes['buttonBorderHoverColor'] ) ) {
        $mainStyles[] = '--gpg-btn-border-hcolor: ' . $attributes['buttonBorderHoverColor'];
    }
    if ( ! empty( $attributes['buttonBorderStyle'] ) ) {
        $mainStyles[] = '--gpg-btn-border-style: ' . $attributes['buttonBorderStyle'];
    }
    if ( ! empty( $attributes['buttonBorderWidth'] ) ) {
        $mainStyles[] = '--gpg-btn-border-width: ' . $attributes['buttonBorderWidth'].'px';
    }
    if ( ! empty( $attributes['buttonMargin'] ) ) {
        $mainStyles[] = '--gpg-btn-margin: ' . $attributes['buttonMargin'].'px';
    }

    // category
    if ( ! empty( $attributes['catColor'] ) ) {
        $mainStyles[] = '--gpg-cat-color: ' . $attributes['catColor'];
    }
    if ( ! empty( $attributes['catHoverColor'] ) ) {
        $mainStyles[] = '--gpg-cat-hover-color: ' . $attributes['catHoverColor'];
    }
    if ( ! empty( $attributes['catBgColor'] ) ) {
        $mainStyles[] = '--gpg-cat-bg-color: ' . $attributes['catBgColor'];
    }
    if ( ! empty( $attributes['catBgHoverColor'] ) ) {
        $mainStyles[] = '--gpg-cat-bg-hcolor: ' . $attributes['catBgHoverColor'];
    }
    if ( ! empty( $attributes['catSize'] ) ) {
        $mainStyles[] = '--gpg-cat-size: ' . $attributes['catSize'].'px';
    }
    if ( ! empty( $attributes['catlineHeight'] ) ) {
        $mainStyles[] = '--gpg-cat-line-height: ' . $attributes['catlineHeight'].'px';
    }
    if ( ! empty( $attributes['catletterSpacing'] ) ) {
        $mainStyles[] = '--gpg-cat-letter-space: ' . $attributes['catletterSpacing'].'px';
    }
    if ( ! empty( $attributes['catRadius'] ) ) {
        $mainStyles[] = '--gpg-cat-radius: ' . $attributes['catRadius'].'px';
    }
    if ( ! empty( $attributes['catFontWeight'] ) ) {
        $mainStyles[] = '--gpg-cat-font-weight: ' . $attributes['catFontWeight'];
    }
    if ( ! empty( $attributes['catTransform'] ) ) {
        $mainStyles[] = '--gpg-cat-transform: ' . $attributes['catTransform'];
    }

    //image
    if ( ! empty( $attributes['imgWidth'] ) ) {
        $mainStyles[] = '--gpg-img-width: ' . $attributes['imgWidth'].'px';
    }
    if ( ! empty( $attributes['imgHeight'] ) ) {
        $mainStyles[] = '--gpg-img-height: ' . $attributes['imgHeight'].'px';
    }
    if ( ! empty( $attributes['imgradius'] ) ) {
        $mainStyles[] = '--gpg-img-radius: ' . $attributes['imgradius'].'px';
    }
    if ( ! empty( $attributes['imgBorderColor'] ) ) {
        $mainStyles[] = '--gpg-img-border-color: ' . $attributes['imgBorderColor'];
    }
    if ( ! empty( $attributes['imgBorderStyle'] ) ) {
        $mainStyles[] = '--gpg-img-border-style: ' . $attributes['imgBorderStyle'];
    }
    if ( ! empty( $attributes['imgBorderWidth'] ) ) {
        $mainStyles[] = '--gpg-img-border-width: ' . $attributes['imgBorderWidth'].'px';
    }
    if ( ! empty( $attributes['imgMargin'] ) ) {
        $mainStyles[] = '--gpg-img-margin: ' . $attributes['imgMargin'].'px';
    }
    if ( ! empty( $attributes['wrapTopMargin'] ) ) {
        $mainStyles[] = '--gpg-wrap-img-tmargin: ' . $attributes['wrapTopMargin'].'px';
    }
    if ( ! empty( $attributes['wrapBottomMargin'] ) ) {
        $mainStyles[] = '--gpg-wrap-img-bmargin: ' . $attributes['wrapBottomMargin'].'px';
    }
    if ( ! empty( $attributes['wrapPadding'] ) ) {
        $mainStyles[] = '--gpg-wrap-padding: ' . $attributes['wrapPadding'].'px';
    }
    if ( ! empty( $attributes['wrapBackground'] ) ) {
        $mainStyles[] = '--gpg-wrap-bg: ' . $attributes['wrapBackground'];
    }

    $block_content = sprintf(
        '<div class="%1$s" style="%2$s">%3$s</div>',
        esc_attr( $col ),
        esc_attr( implode( ';', $mainStyles ) ),
        $list_item_markup
	);
	return $block_content;
}

/**
 * Create API fields for additional info
 */
function gpg_blocks_register_rest_fields() {
    $post_types = get_post_types();
    //featured image
    register_rest_field(
		$post_types,
		'gpg_featured_image_src',
		array(
			'get_callback'    => 'gpg_blocks_get_image_src',
			'update_callback' => null,
			'schema'          => null,
		)
    );
    // Category links.
    register_rest_field(
    $post_types,
    'gpg_category_name',
        array(
            'get_callback' => 'gpg_category_list',
            'update_callback' => null,
            'schema' => array(
                'description' => __( 'Category list links' ),
                'type' => 'string',
            ),
        )
    );
    // Number of comments.
    register_rest_field(
    $post_types,
    'gpg_comment',
    array(
        'get_callback' => 'gpg_commments_number',
        'update_callback' => null,
        'schema' => array(
            'description' => __( 'Number of comments' ),
            'type' => 'number',
        ),
      )
    );
	// Add excerpt info.
	register_rest_field(
        $post_types,
        'gpg_excerpt',
		array(
			'get_callback'    => 'gpg_blocks_get_excerpt',
			'update_callback' => null,
			'schema'          => null,
		)
	);
}
add_action( 'rest_api_init', 'gpg_blocks_register_rest_fields' );

if ( ! function_exists( 'gpg_blocks_get_excerpt' ) ) {
    function gpg_blocks_get_excerpt( $object, $post = null ) {
        $excerpt = wp_trim_words( get_the_excerpt( $object['id'] ) );
        if ( ! $excerpt ) {
            $excerpt = null;
        }
        return $excerpt;
    }
} 

if ( ! function_exists( 'gpg_commments_number' ) ) {
    function gpg_commments_number( $object ) {
        $num = get_comments_number( $object['id'] );
        return sprintf( _n( '%d comment', '%d comments', $num, 'blocks-post-grid' ), $num );
    }
}

if ( ! function_exists( 'gpg_category_list' ) ) {
    function gpg_category_list( $object ) {
        return get_the_category_list( esc_html__( ' ', 'blocks-post-grid' ), '', $object['id'] );
    }
}

 if ( ! function_exists( 'gpg_blocks_get_image_src' ) ) {
    function gpg_blocks_get_image_src( $object, $field_name, $request ) {
        $coreimg['large'] = wp_get_attachment_image_src(
            $object['featured_media'],
            'large',
            false
        );

        $coreimg['medium'] = wp_get_attachment_image_src(
            $object['featured_media'],
            'medium',
            false
        );

        $coreimg['medium_large'] = wp_get_attachment_image_src(
            $object['featured_media'],
            'medium_large',
            false
        );

        $coreimg['thumbnail'] = wp_get_attachment_image_src(
            $object['featured_media'],
            'thumbnail',
            false
        );
        return $coreimg;
    }
}

function gpg_register_dynamic_post() {
	register_block_type(
		'blocks-post-grid/postgrid',
		array(
			'attributes' => array(
                'id' => array (
                    'type' => 'string',
                ),
                'categories' => array(
                    'type' => 'string',
                ),
                'post_type' => array(
                    'type' => 'string',
                    'default' => 'post'
                ),
                'postsToShow' => array(
                    'type' => 'number',
                    'default' => 4,
                ),
                'columns' => array(
                    'type' => 'number',
                    'default' => 2,
                ),
                'order' => array(
                    'type' => 'string',
                    'default' => 'desc',
                ),
                'orderBy'  => array(
                    'type' => 'string',
                    'default' => 'date',
                ),
                'displayPostTitle' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'heading' => array(
                    'type' => 'string',
                    'default' => 'h3',
                ),
                'displayPostAuthor' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'displayPostImage' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'displayPostDate' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'displayComments' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
                'displayCategory' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'displayExcerpt' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'excerptlimit'  => array(
                    'type' => 'number',
                    'default' => 15,
                ),
                'imageCrop'  => array(
                    'type' => 'string',
                    'default' => 'medium_large',
                ),
                'displayPostLink' => array(
                    'type' => 'boolean',
                    'default' => true,
                ),
                'readMoreText'  => array(
                    'type' => 'string',
                    'default' => 'Continue Reading',
                ),

                //title
                'textColor' => array (
                    'type' => 'string',
                    'default' => '#151515',
                ),
                'textHoverColor' => array (
                    'type' => 'string',
                    'default' => '#1275f1',
                ),
                'titleSize'  => array(
                    'type' => 'number',
                    'default' => 25,
                ),
                'titlelineHeight'  => array(
                    'type' => 'number',
                    'default' => 32,
                ),
                'titleletterSpacing'  => array(
                    'type' => 'number'
                ),
                'titleWeight'  => array(
                    'type' => 'string',
                    'default' => '600',
                ),
                'titleTransform'  => array(
                    'type' => 'string'
                ),
                'titleDecoration'  => array(
                    'type' => 'string',
                    'default' => 'none',
                ),
                'titleStyle'  => array(
                    'type' => 'string'
                ),
                'titleMargin'  => array(
                    'type' => 'number',
                    'default' => 15,
                ),

                //Image
                'imgWidth'  => array(
                    'type' => 'number'
                ),
                'imgHeight'  => array(
                    'type' => 'number'
                ),
                'imgradius'  => array(
                    'type' => 'number'
                ),
                'imgBorderWidth'  => array(
                    'type' => 'number'
                ),
                'imgBorderStyle'  => array(
                    'type' => 'string'
                ),
                'imgBorderColor'  => array(
                    'type' => 'string'
                ),
                'imgMargin'  => array(
                    'type' => 'number',
                    'default' => 25,
                ),

                //meta
                'metaColor' => array (
                    'type' => 'string',
                    'default' => '#8a8a8a',
                ),
                'metaSize'  => array(
                    'type' => 'number',
                    'default' => 14,
                ),
                'metalineHeight'  => array(
                    'type' => 'number',
                    'default' => 22,
                ),
                'metaWeight'  => array(
                    'type' => 'string',
                    'default' => '400',
                ),
                'metaTransform'  => array(
                    'type' => 'string',
                    'default' => 'capitalize',
                ),
                'metaStyle'  => array(
                    'type' => 'string',
                ),
                'metaMargin'  => array(
                    'type' => 'number',
                    'default' => 20,
                ),

                //content
                'contentColor' => array (
                    'type' => 'string'
                ),
                'contentSize'  => array(
                    'type' => 'number',
                    'default' => 16,
                ),
                'contentlineHeight'  => array(
                    'type' => 'number',
                    'default' => 30,
                ),
                'contentWeight'  => array(
                    'type' => 'string'
                ),
                'ContentMargin'  => array(
                    'type' => 'number',
                    'default' => 30,
                ),

                //category
                'catColor' => array (
                    'type' => 'string',
                    'default' => '#fff',
                ),
                'catHoverColor' => array (
                    'type' => 'string',
                    'default' => '#fff',
                ),
                'catBgColor' => array (
                    'type' => 'string',
                    'default' => '#1275f1',
                ),
                'catBgHoverColor' => array (
                    'type' => 'string',
                    'default' => '#0e5ec1',
                ),
                'catSize'  => array(
                    'type' => 'number',
                    'default' => 12,
                ),
                'catlineHeight'  => array(
                    'type' => 'number',
                    'default' => 17,
                ),
                'catletterSpacing'  => array(
                    'type' => 'number'
                ),
                'catFontWeight'  => array(
                    'type' => 'string'
                ),
                'catTransform'  => array(
                    'type' => 'string',
                    'default' => 'uppercase',
                ),
                'catRadius'  => array(
                    'type' => 'number',
                    'default' => 2,
                ),

                //button
                'buttonColor' => array (
                    'type' => 'string',
                    'default' => '#fff',
                ),
                'buttonHoverColor' => array (
                    'type' => 'string',
                    'default' => '#fff',
                ),
                'buttonBgColor' => array (
                    'type' => 'string',
                    'default' => '#2b2b2b',
                ),
                'buttonBgHoverColor' => array (
                    'type' => 'string',
                    'default' => '#0e5ec1',
                ),
                'buttonSize'  => array(
                    'type' => 'number',
                    'default' => 12,
                ),
                'buttonlineHeight'  => array(
                    'type' => 'number',
                    'default' => 19,
                ),
                'buttonletterSpacing'  => array(
                    'type' => 'number'
                ),
                'buttonFontWeight'  => array(
                    'type' => 'string',
                    'default' => '400',
                ),
                'buttonTransform'  => array(
                    'type' => 'string',
                    'default' => 'uppercase',
                ),
                'buttonDecoration'  => array(
                    'type' => 'string'
                ),
                'buttonStyle'  => array(
                    'type' => 'string'
                ),
                'buttonWidth'  => array(
                    'type' => 'number'
                ),
                'buttonHeight'  => array(
                    'type' => 'number'
                ),
                'buttonradius'  => array(
                    'type' => 'number',
                    'default' => 2,
                ),
                'buttonBorderWidth'  => array(
                    'type' => 'number'
                ),
                'buttonBorderStyle'  => array(
                    'type' => 'string'
                ),
                'buttonBorderColor'  => array(
                    'type' => 'string'
                ),
                'buttonBorderHoverColor'  => array(
                    'type' => 'string'
                ),
                'buttonMargin'  => array(
                    'type' => 'number',
                    'default' => 20,
                ),
                //wrap
                'wrapBackground' => array (
                    'type' => 'string',
                    'default' => '',
                ),
                'wrapTopMargin'  => array(
                    'type' => 'number',
                    'default' => 0,
                ),
                'wrapBottomMargin'  => array(
                    'type' => 'number',
                    'default' => 0,
                ),
                'wrapPadding'  => array(
                    'type' => 'number',
                    'default' => 0,
                ),
			),
			'render_callback' => 'gpg_render_block_latest_postgrid',
		)
	);
}
add_action( 'init', 'gpg_register_dynamic_post' );