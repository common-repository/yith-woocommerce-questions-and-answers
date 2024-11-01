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

?>

<li id="li-answer-<?php echo esc_attr( $answer->ID ); ?>" class="answer-container <?php echo esc_attr( $classes ); ?>">

	<div class="answer-content">
		<span class="answer"><?php echo esc_attr( $answer->content ); ?></span>
	</div>

	<div
		class="answer-owner">
		<?php
		echo sprintf(
			/* translators: %1: Author name %2: date*/
			esc_html__( '%1$s answered on %2$s', 'yith-woocommerce-questions-and-answers' ),
			'<span class="answer-author-name">' . wp_kses( $answer->get_author_name(), 'post' ) . '</span>',
			'<span class="answer-date">' . wp_kses( $answer->date, 'post' ) . '</span>'
		);
		?>
	</div>


</li>
