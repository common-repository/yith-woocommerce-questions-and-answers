<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package yith-woocommerce-question-and-answer
 */

if ( ! defined( 'YWQA_CUSTOM_POST_TYPE_NAME' ) ) {
	define( 'YWQA_CUSTOM_POST_TYPE_NAME', 'question_answer' );
}

// region    ***************  METAKEY name definition    *******************.
if ( ! defined( 'YWQA_METAKEY_PRODUCT_ID' ) ) {
	define( 'YWQA_METAKEY_PRODUCT_ID', '_ywqa_product_id' );
}

if ( ! defined( 'YWQA_METAKEY_DISCUSSION_TYPE' ) ) {
	define( 'YWQA_METAKEY_DISCUSSION_TYPE', '_ywqa_type' );
}

if ( ! defined( 'YWQA_METAKEY_APPROVED' ) ) {
	define( 'YWQA_METAKEY_APPROVED', '_ywqa_approved' );
}
// endregion.

defined( 'YWQA_METAKEY_DISCUSSION_AUTHOR_ID' ) || define( 'YWQA_METAKEY_DISCUSSION_AUTHOR_ID', '_ywqa_discussion_author_id' );

if ( ! function_exists( 'ywqa_strip_trim_text' ) ) {
	/**
	 * Ywqa_strip_trim_text
	 * Strip html tags from a text and trim to fixed length
	 *
	 * @param  mixed $text text.
	 * @param  mixed $chars chars.
	 * @return wc_trim_string
	 */
	function ywqa_strip_trim_text( $text, $chars = 50 ) {
		return wc_trim_string( wp_strip_all_tags( $text ), $chars );
	}
}
