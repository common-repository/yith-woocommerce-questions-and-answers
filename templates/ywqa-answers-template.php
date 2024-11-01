<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package yith-woocommerce-question-and-answer\templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $product;
$ywqa = YITH_WooCommerce_Question_Answer::get_instance();
?>

<div id="new-answer-header">
	<div id="parent-question">
		<div class="parent-question"><?php echo wp_kses( $question->content, 'post' ); ?><a class="back-to-product"
																		href="
																		<?php
																			echo esc_attr(
																				remove_query_arg(
																					array(
																						'show-all-questions',
																						'reply-to-question',
																					)
																				)
																			);
																			?>
																		"
																		title="<?php esc_html_e( 'Back to product', 'yith-woocommerce-questions-and-answers' ); ?>"><?php esc_html_e( 'Back to product', 'yith-woocommerce-questions-and-answers' ); ?></a>
		</div>

		<div
			class="question-owner">
			<?php
			echo sprintf(
				/* translators: %1: Author name %2:date */
				esc_html__( 'asked by %1$s on %2$s', 'yith-woocommerce-questions-and-answers' ),
				'<span class="question-author-name">' . wp_kses( $question->get_author_name(), 'post' ) . '</span>',
				'<span class="question-date">' . wp_kses( $question->date, 'post' ) . '</span>'
			);
			?>
		</div>
	</div>

	<?php
	// If the plugin is in FAQ mode, don't show the submit section.
	if ( ! $ywqa->faq_mode ) :
		?>
		<div id="submit_answer">
			<form id="submit_answer_form" method="POST">
				<input type="hidden" name="ywqa_product_id" value="<?php echo wp_kses( $question->product_id, 'post' ); ?>">
				<input type="hidden" name="ywqa_question_id" value="<?php echo wp_kses( $question->ID, 'post' ); ?>">
				<input type="hidden" name="add_new_answer" value="1">
				<?php wp_nonce_field( 'submit_answer_' . $question->ID, 'send_answer' ); ?>

				<div>
						<textarea placeholder="<?php esc_html_e( 'Type your answer here', 'yith-woocommerce-questions-and-answers' ); ?>" class="ywqa-send-answer-text"
							id="ywqa_send_answer_text"
							name="ywqa_send_answer_text"></textarea>
					<input id="ywqa-send-answer" type="submit" class="ywqa_submit_answer"
						value="<?php esc_html_e( 'Answer', 'yith-woocommerce-questions-and-answers' ); ?>"
						title="<?php esc_html_e( 'Answer now to the question', 'yith-woocommerce-questions-and-answers' ); ?>">
				</div>
			</form>
		</div>
	<?php endif; ?>
</div>

<div id="ywqa_answer_list">
<?php $question_count = $question->has_answers(); ?>
	<?php if ( $question_count ) : ?>
		<span
			class="answer-list-count">
			<?php
			echo sprintf(     /* translators: %s: Question count */
				esc_html__( '%s answers shown', 'yith-woocommerce-questions-and-answers' ),
				wp_kses( $question_count, 'post' )
			);
			?>
				</span>
		<ol class="ywqa-answer-list">
			<?php $ywqa->show_answers( $question ); ?>
		</ol>
	<?php elseif ( ! $ywqa->faq_mode ) : ?>

		<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no answers to this question, be the first to respond.', 'yith-woocommerce-questions-and-answers' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
</div>
