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

$count      = $ywqa->get_questions_count( yit_get_prop( $product, 'id' ) );
$item_shown = 0;
?>
<div class="questions-section">
	<h3><?php esc_html_e( 'Questions and answers of the customers', 'yith-woocommerce-questions-and-answers' ); ?></h3>

	<?php do_action( 'yith_question_answer_before_question_list_section' ); ?>
	<div id="ywqa_question_list">
		<?php if ( $count ) : ?>
			<?php do_action( 'yith_question_answer_before_question_list' ); ?>

			<ol class="ywqa-question-list">
				<?php $item_shown = $ywqa->show_questions( yit_get_prop( $product, 'id' ), $max_items, $only_answered ); ?>
			</ol>
			<?php do_action( 'yith_question_answer_after_question_list' ); ?>

			<?php
			if ( $item_shown < $count ) :
				?>
				<div id="show-all-questions">
					<a class="show-questions" href="<?php echo esc_attr( add_query_arg( 'show-all-questions', yit_get_prop( $product, 'id' ) ) ); ?>"
					title="
					<?php
					echo sprintf(  /* translators: %d: Count */
						esc_html__( 'Show all %d questions with answers', 'yith-woocommerce-questions-and-answers' ),
						wp_kses( $count, 'post' )
					);
					?>
						">
						<?php
						echo sprintf(
							/* translators: %s: Name of a city */
							esc_html__( 'Show all %d questions with answers', 'yith-woocommerce-questions-and-answers' ),
							wp_kses( $count, 'post' )
						);
						?>
							</a>
				</div>
			<?php endif; ?>
		<?php elseif ( ! $ywqa->faq_mode ) : ?>

			<p class="
			woocommerce-noreviews"><?php esc_html_e( 'There are no questions yet, be the first to ask something for this product.', 'yith-woocommerce-questions-and-answers' ); ?></p>
		<?php endif; ?>

		<div class="clear"></div>
	</div>
	<?php
	// If the plugin is in FAQ mode, don't show the submit section.
	if ( ! $ywqa->faq_mode ) :
		?>
		<div id="ask_question">
			<form id="ask_question_form" method="POST">
				<input type="hidden" name="ywqa_product_id" value="<?php echo wp_kses( yit_get_prop( $product, 'id' ), 'post' ); ?>">
				<input type="hidden" name="add_new_question" value="1">
				<?php wp_nonce_field( 'ask_question_' . yit_get_prop( $product, 'id' ), 'ask_question' ); ?>

				<div class="ywqa-ask-question">
					<input class="ywqa-ask-question-text" id="ywqa_ask_question_text" name="ywqa_ask_question_text"
						placeholder="<?php esc_html_e( 'Do you have any question? Ask now!', 'yith-woocommerce-questions-and-answers' ); ?>">

					<input id="ywqa-submit-question" type="submit" class="ywqa_submit_question"
						value="<?php esc_html_e( 'Ask', 'yith-woocommerce-questions-and-answers' ); ?>"
						title="<?php esc_html_e( 'Ask your question', 'yith-woocommerce-questions-and-answers' ); ?>">
				</div>
			</form>

		</div>
	<?php endif; ?>
	<div class="clearfix"></div>
	<?php do_action( 'yith_question_answer_after_question_list_section' ); ?>
</div>
