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
$ywqa = YITH_WooCommerce_Question_Answer::get_instance();
?>

<li id="li-question-<?php echo esc_attr( $question->ID ); ?>" class="question-container <?php echo esc_attr( $classes ); ?>">
	<?php do_action( 'yith_questions_answers_before_content', $question ); ?>

	<div class="question-text <?php echo esc_attr( $classes ); ?>">
		<div class="question-content">
			<span class="question-symbol"><?php echo esc_html__( 'Q', 'yith-woocommerce-questions-and-answers' ); ?></span>
			<span class="question"><a
					href="<?php echo esc_attr( add_query_arg( 'reply-to-question', $question->ID, remove_query_arg( 'show-all-questions' ) ) ); ?>"><?php echo wp_kses( $question->content, 'post' ); ?></a></span>
		</div>

		<div class="answer-content">
			<?php
			$first_answer = $question->get_answers( 1 );
			if ( isset( $first_answer[0] ) ) :
				if ( ! $ywqa->faq_mode && current_user_can( 'manage_options' ) ) {
					echo '<span class="admin-answer-symbol">' . esc_html__( 'Answered by the admin', 'yith-woocommerce-questions-and-answers' ) . '</span>';
				} else {
					echo '<span class="answer-symbol">' . esc_html__( 'A', 'yith-woocommerce-questions-and-answers' ) . '</span>';
				}
				?>

				<span class="answer">
						<?php echo wp_kses( $first_answer[0]->content, 'post' ); ?>
				</span>
			<?php else : ?>
				<span class="answer"><?php esc_html_e( 'There are no answers for this question yet.', 'yith-woocommerce-questions-and-answers' ); ?></span>
			<?php endif; ?>
		</div>
		<?php $count = $question->has_answers(); ?>
		<?php if ( ( $count ) > 1 ) : ?>
			<div class="all-answers-section">
				<a href="<?php echo esc_attr( add_query_arg( 'reply-to-question', $question->ID, remove_query_arg( 'show-all-questions' ) ) ); ?>"
				id="all-answers-<?php echo wp_kses( $question->ID, 'post' ); ?>" class="all-answers">
					<?php
					echo sprintf(
						/* translators: %s: count */                        esc_html__( 'Show all %s answers', 'yith-woocommerce-questions-and-answers' ),
						wp_kses( $count, 'post' )
					);
					?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</li>
