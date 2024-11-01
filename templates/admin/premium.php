<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 *
 * @package yith-woocommerce-question-and-answer\templates\admin
 */

?>
<style>
	.section{
		margin-left: -20px;
		margin-right: -20px;
		font-family: "Raleway",san-serif;
		overflow-x: hidden;
	}
	.section h1{
		text-align: center;
		text-transform: uppercase;
		color: #808a97;
		font-size: 35px;
		font-weight: 700;
		line-height: normal;
		display: inline-block;
		width: 100%;
		margin: 50px 0 0;
	}
	.section ul{
		list-style-type: disc;
		padding-left: 15px;
	}
	.section:nth-child(even){
		background-color: #fff;
	}
	.section:nth-child(odd){
		background-color: #f1f1f1;
	}
	.section .section-title img{
		display: table-cell;
		vertical-align: middle;
		width: auto;
		margin-right: 15px;
	}
	.section h2,
	.section h3 {
		display: inline-block;
		vertical-align: middle;
		padding: 0;
		font-size: 24px;
		font-weight: 700;
		color: #808a97;
		text-transform: uppercase;
	}

	.section .section-title h2{
		display: table-cell;
		vertical-align: middle;
		line-height: 24px;
	}

	.section-title{
		display: table;
	}

	.section h3 {
		font-size: 14px;
		line-height: 28px;
		margin-bottom: 0;
		display: block;
	}

	.section p{
		font-size: 13px;
		margin: 25px 0;
	}
	.section ul li{
		margin-bottom: 4px;
	}
	.landing-container{
		max-width: 750px;
		margin-left: auto;
		margin-right: auto;
		padding: 50px 0 30px;
	}
	.landing-container:after{
		display: block;
		clear: both;
		content: '';
	}
	.landing-container .col-1,
	.landing-container .col-2{
		float: left;
		box-sizing: border-box;
		padding: 0 15px;
	}
	.landing-container .col-1 img{
		width: 100%;
	}
	.landing-container .col-1{
		width: 55%;
	}
	.landing-container .col-2{
		width: 45%;
	}
	.premium-cta{
		background-color: #808a97;
		color: #fff;
		border-radius: 6px;
		padding: 20px 15px;
	}
	.premium-cta:after{
		content: '';
		display: block;
		clear: both;
	}
	.premium-cta p{
		margin: 7px 0;
		font-size: 14px;
		font-weight: 500;
		display: inline-block;
		width: 60%;
	}
	.premium-cta a.button{
		border-radius: 6px;
		height: 60px;
		float: right;
		background: url(<?php echo esc_url( YITH_YWQA_URL ); ?>assets/images/upgrade.png) #ff643f no-repeat 13px 13px;
		border-color: #ff643f;
		box-shadow: none;
		outline: none;
		color: #fff;
		position: relative;
		padding: 9px 50px 9px 70px;
	}
	.premium-cta a.button:hover,
	.premium-cta a.button:active,
	.premium-cta a.button:focus{
		color: #fff;
		background: url(<?php echo esc_url( YITH_YWQA_URL ); ?>assets/images/upgrade.png) #971d00 no-repeat 13px 13px;
		border-color: #971d00;
		box-shadow: none;
		outline: none;
	}
	.premium-cta a.button:focus{
		top: 1px;
	}
	.premium-cta a.button span{
		line-height: 13px;
	}
	.premium-cta a.button .highlight{
		display: block;
		font-size: 20px;
		font-weight: 700;
		line-height: 20px;
	}
	.premium-cta .highlight{
		text-transform: uppercase;
		background: none;
		font-weight: 800;
		color: #fff;
	}

	@media (max-width: 768px) {
		.section{margin: 0}
		.premium-cta p{
			width: 100%;
		}
		.premium-cta{
			text-align: center;
		}
		.premium-cta a.button{
			float: none;
		}
	}

	@media (max-width: 480px){
		.wrap{
			margin-right: 0;
		}
		.section{
			margin: 0;
		}
		.landing-container .col-1,
		.landing-container .col-2{
			width: 100%;
			padding: 0 15px;
		}
		.section-odd .col-1 {
			float: left;
			margin-right: -100%;
		}
		.section-odd .col-2 {
			float: right;
			margin-top: 65%;
		}
	}

	@media (max-width: 320px){
		.premium-cta a.button{
			padding: 9px 20px 9px 70px;
		}

		.section .section-title img{
			display: none;
		}
	}
</style>
<div class="landing">
	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Questions and Answers%2$s to benefit from all features!', 'yith-woocommerce-questions-and-answers' ),
						'<span class="highlight">',
						'</span>'
					);
					?>
				</p>
				<a href="<?php echo esc_attr( YWQA_Plugin_FW_Loader::get_instance()->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn">
					<span class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-questions-and-answers' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-questions-and-answers' ); ?></span>
				</a>
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_url( YITH_YWQA_URL ); ?>assets/images/01-bg.png) no-repeat #fff; background-position: 85% 75%">
		<h1><?php esc_html_e( 'Premium Features', 'yith-woocommerce-questions-and-answers' ); ?></h1>
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_url( YITH_YWQA_URL ); ?>assets/images/01.png" alt="" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/01-icon.png" alt=""/>
					<h2><?php esc_html_e( 'Number of answers ', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'The more followers has a question, the more answers are likely to be left for it. If you do not want your product page to get extremely long, enable %1$sanswer pagination%2$s by specifying the number of elements you want to show at a time.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_url( YITH_YWQA_URL ); ?>assets/images/02-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/02-icon.png" alt="icon 02" />
					<h2><?php esc_html_e( 'Voting system', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'Increase and improve interaction between users and your shop. With the premium version of the plugin, you will be able to allow all registered users to leave a %1$spositive or negative vote%2$s to questions and answers of each product. A very good strategy to highlight questions and answers that can be useful to other customers.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/02.png" alt="" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/03-bg.png) no-repeat #fff; background-position: 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/03.png" alt="" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/03-icon.png" alt="icon 03" />
					<h2><?php esc_html_e( 'Email notification', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'Keep always up-to-date about what users write in your shop. Enable %1$semail notification%2$s to be informed any time a new question is added to one of your products and read the content of it in the email message you get. With the option %1$s"User notification"%2$s, you can notify users as soon as an answer is given to the question they have previously posed.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-odd clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/04-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/04-icon.png" alt="icon 04" />
					<h2><?php esc_html_e( 'Inappropriate content', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'There might always be someone in the web, who wants to disturb or leave offensive, inappropriate or simply unsuitable answers. With the premium version of the plugin, your users will be able to "monitor" this kind of answers on their own and report questions and/or answers that are inappropriate. %1$sThese answers will be automatically removed%2$s if a specific number of users that you can set in your plugin reports them as an abuse.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/04.png" alt="" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/05-bg.png) no-repeat #fff; background-position: 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/05.png" alt="" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/05-icon.png" alt="icon 05" />
					<h2><?php esc_html_e( 'Incognito mode', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'Working principles are always the same. Each question will get its own answers. The only difference is that the name of users who have posed a question or given an answer will not be shown and they will be %1$sanonymous%2$s to users of the shop, either they are registered or not.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
						</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/06-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/06-icon.png" alt="icon 04" />
					<h2><?php esc_html_e( 'Invite to answer', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'Who may better answer to a new question than those who have purchased a product?  With this innovative feature, you will be able to automatically %1$ssend an email to customers that have purchased a product%2$s on which a question has been added and invite them to answer. You can choose to send an email to all customers or let the plugin select only some of them randomly. ', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/06.png" alt="" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/07-bg.png) no-repeat #fff; background-position: 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/07.png" alt="" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/07-icon.png" alt="icon 07" />
					<h2><?php esc_html_e( 'reCAPTCHA', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'Make your questions and answers system safer: activate the recaptcha and avoid all the spam contents you can get.The premium version of the plugin offers the new version of Google\'s captcha, the %1$sNo CAPTCHA reCAPTCHA%2$s, to give you the freedom to use an up-to-date and simple system for your users.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
						</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/08-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/08-icon.png" alt="icon 04" />
					<h2><?php esc_html_e( 'Insertion mode.', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'By default, the complete list of questions and answers is shown right inside a %1$stab of product detail page%2$s.%3$s However, you can change this behavior by inserting, through the specific %1$sshortcode%2$s, questions and answers in any spot of the page.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>',
						'<br>'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/08.png" alt="" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/09-bg.png) no-repeat #fff; background-position: 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/09.png" alt="" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/09-icon.png" alt="icon 07" />
					<h2><?php esc_html_e( 'Content moderation', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'That be a question or an answer, the content could be %1$spublished only after your approval%2$s. In this way you could avoid spam on your product pages and be sure to have only efficient contents for your customers.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
						</p>
			</div>
		</div>
	</div>
	<div class="section section-odd clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/10-bg.png) no-repeat #f1f1f1; background-position: 15% 100%">
		<div class="landing-container">
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/10-icon.png" alt="icon 10" />
					<h2><?php esc_html_e( 'Unlogged users', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'Give the possibility to unlogged users to insert a question or an answer. The insertion form allows user to insert %1$sname%2$s and %1$semail address%2$s which can be used later by administrator to stretch back to the owner of the question and/or answer.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
				</p>
			</div>
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/10.png" alt="" />
			</div>
		</div>
	</div>
	<div class="section section-even clear" style="background: url(<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/11-bg.png) no-repeat #fff; background-position: 85% 100%">
		<div class="landing-container">
			<div class="col-1">
				<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/11.png" alt="" />
			</div>
			<div class="col-2">
				<div class="section-title">
					<img src="<?php echo esc_attr( YITH_YWQA_URL ); ?>assets/images/11-icon.png" alt="icon 07" />
					<h2><?php esc_html_e( 'FAQ mode', 'yith-woocommerce-questions-and-answers' ); ?></h2>
				</div>
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'A complete list of questions and answers is made available to users, without any possibility of insertion for them. %1$sOnly the administrator of the shop%2$s will be enabled to insert and /or change the contents.', 'yith-woocommerce-questions-and-answers' ),
						'<b>',
						'</b>'
					);
					?>
				</p>
			</div>
		</div>
	</div>

	<div class="section section-cta section-odd">
		<div class="landing-container">
			<div class="premium-cta">
				<p>
					<?php
					echo sprintf(
						/* translators: %1: html %2: html*/
						esc_html__( 'Upgrade to %1$spremium version%2$s of %1$sYITH WooCommerce Questions and Answers%2$s to benefit from all features!', 'yith-woocommerce-questions-and-answers' ),
						'<span class="highlight">',
						'</span>'
					);
					?>
				</p>
				<a href="<?php echo esc_attr( YWQA_Plugin_FW_Loader::get_instance()->get_premium_landing_uri() ); ?>" target="_blank" class="premium-cta-button button btn">
					<span class="highlight"><?php esc_html_e( 'UPGRADE', 'yith-woocommerce-questions-and-answers' ); ?></span>
					<span><?php esc_html_e( 'to the premium version', 'yith-woocommerce-questions-and-answers' ); ?></span>
				</a>
			</div>
		</div>
	</div>
</div>
