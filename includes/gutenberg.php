<?php
/**
 * Gutenberg.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get blocks.
 */
function newsletterglue_get_blocks() {

	$use_blocks = get_option( 'newsletterglue_use_blocks' );

	$blocks = array(
		'newsletterglue_block_show_hide_content' => array(
			'title'			=> __( 'Show/hide content', 'newsletter-glue' ),
			'description'	=> __( 'Hide selected content from your blog/newsletter.', 'newsletter-glue' ),
			'use_block'		=> isset( $use_blocks[ 'newsletterglue_block_show_hide_content' ] ) ? sanitize_text_field( $use_blocks[ 'newsletterglue_block_show_hide_content' ] ) : 'yes',
			'callback'		=> 'newsletterglue_block_show_hide_content',
			'icon'			=> NGL_PLUGIN_URL . 'includes/blocks/newsletterglue_block_show_hide_content/icon/icon.svg',
		),
		'newsletterglue_block_author' => array(
			'title'			=> __( 'Author byline', 'newsletter-glue' ),
			'description'	=> __( 'Add an author byline and follow button to your newsletter.', 'newsletter-glue' ),
			'use_block'		=> isset( $use_blocks[ 'newsletterglue_block_author' ] ) ? sanitize_text_field( $use_blocks[ 'newsletterglue_block_author' ] ) : 'no',
			'callback'		=> 'newsletterglue_block_author_byline',
			'icon'			=> NGL_PLUGIN_URL . 'includes/blocks/newsletterglue_block_author/icon/icon.svg',
			'pro'			=> 'yes',
		),
		'newsletterglue_block_callout' => array(
			'title'			=> __( 'Callout card', 'newsletter-glue' ),
			'description'	=> __( 'Customise the background and border of this card to help its content stand out.', 'newsletter-glue' ),
			'use_block'		=> isset( $use_blocks[ 'newsletterglue_block_callout' ] ) ? sanitize_text_field( $use_blocks[ 'newsletterglue_block_callout' ] ) : 'no',
			'callback'		=> 'newsletterglue_block_callout',
			'icon'			=> NGL_PLUGIN_URL . 'includes/blocks/newsletterglue_block_callout/icon/icon.svg',
			'pro'			=> 'yes',
		),
	);

	return apply_filters( 'newsletterglue_get_blocks', $blocks );

}

/**
 * Add our block category.
 */
function newsletterglue_add_block_category( $categories, $post ) {

	return array_merge(
		$categories,
		array(
			array(
				'slug' 		=> 'newsletterglue-blocks',
				'title' 	=> __( 'Newsletter Glue', 'newsletter-glue' ),
			),
		)
	);

}
add_filter( 'block_categories', 'newsletterglue_add_block_category', 10, 2 );