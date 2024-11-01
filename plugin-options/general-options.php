<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package yith-woocommerce-question-and-answer\plugin-options
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

$general_options = array(

	'general' => array(

		'section_general_settings'     => array(
			'name' => __( 'General settings', 'yith-woocommerce-questions-and-answers' ),
			'type' => 'title',
			'id'   => 'ywqa_section_general',
		),
		'ywqa_questions_to_show'       => array(
			'name'              => __( 'Questions to show', 'yith-woocommerce-questions-and-answers' ),
			'type'              => 'number',
			'desc'              => __( 'Set how many questions you want to show for each product (set 0 to display all).', 'yith-woocommerce-questions-and-answers' ),
			'id'                => 'ywqa_questions_to_show',
			'default'           => '0',
			'custom_attributes' => array(
				'min'      => 0,
				'step'     => 1,
				'required' => 'required',
			),
		),
		'ywqa_faq_mode'                => array(
			'name'    => __( 'FAQ mode', 'yith-woocommerce-questions-and-answers' ),
			'type'    => 'checkbox',
			'desc'    => __( 'Don\'t allow users to add questions and answers, but let them read in FAQ mode.', 'yith-woocommerce-questions-and-answers' ),
			'id'      => 'ywqa_faq_mode',
			'default' => 'no',
		),
		'section_general_settings_end' => array(
			'type' => 'sectionend',
			'id'   => 'ywqa_section_general_end',
		),
	),
);

return $general_options;
