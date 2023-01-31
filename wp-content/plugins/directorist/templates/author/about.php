<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<?php do_action( 'directorist_before_author_about_section' ); ?>

<div class="<?php Helper::directorist_row(); ?> directorist-mb-40">

	<div class="<?php Helper::directorist_column( 'md-8' ); ?>">
		<div class="directorist-card directorist-author-about">

			<div class="directorist-card__header">
				<h4 class="directorist-card__header--title"><?php directorist_icon( 'las la-user' ); ?><?php esc_html_e( 'About', 'directorist' ); ?></h4>
			</div>

			<div class="directorist-card__body">
				<div class="directorist-author-about__content">
					<p><?php echo $bio ? wp_kses_post( $bio ) : esc_html__( 'Nothing to show!', 'directorist' );?></p>
				</div>
			</div>

		</div>
	</div>

	<div class="<?php Helper::directorist_column( 'md-4' ); ?>">
		<div class="directorist-card directorist-widget directorist-author-contact">

			<div class="directorist-card__header">
				<h4 class="directorist-card__header--title directorist-widget-title"><?php esc_html_e( 'Contact Info', 'directorist' ); ?></h4>
			</div>

			<div class="directorist-card__body">
				<div class="directorist-author-info-widget">

					<ul class="directorist-author-info-list">

						<?php if ( $address ): ?>
							<li class="directorist-author-info-list__item">
								<?php directorist_icon( 'las la-map-marker' ); ?>
								<span class="directorist-info"><?php echo esc_html( $address ); ?></span>
							</li>
						<?php endif; ?>

						<?php if ( $phone ): ?>
							<li class="directorist-author-info-list__item">
								<?php directorist_icon( 'las la-phone' ); ?>
								<span class="directorist-info"><a href="tel:<?php ATBDP_Helper::sanitize_tel_attr( $phone ); ?>"><?php echo esc_html( $phone ); ?></a></span>
							</li>
						<?php endif; ?>

						<?php if ( $email_endabled && $email ): ?>
							<li class="directorist-author-info-list__item">
								<?php directorist_icon( 'las la-envelope' ); ?>
								<span class="directorist-info"><?php echo esc_html( $email ); ?></span>
							</li>
						<?php endif; ?>

						<?php if ( $website ): ?>
							<li class="directorist-author-info-list__item">
								<?php directorist_icon( 'las la-globe' ); ?>
								<span class="directorist-info"><a target="_blank" href="<?php echo esc_url( $website ); ?>"><?php echo esc_html( $website) ; ?></a></span>
							</li>
						<?php endif; ?>

					</ul>

					<?php if ( $facebook || $twitter || $linkedin || $youtube ): ?>

						<ul class="directorist-author-social">

							<?php if ( $facebook ): ?>
								<li class="directorist-author-social-item"><a target="_blank" href="<?php echo esc_url( $facebook ); ?>"><?php directorist_icon( 'lab la-facebook' ); ?></a></li>
							<?php endif; ?>

							<?php if ( $twitter ): ?>
								<li class="directorist-author-social-item"><a target="_blank" href="<?php echo esc_url( $twitter ); ?>"><?php directorist_icon( 'lab la-twitter' ); ?></a></li>
							<?php endif; ?>

							<?php if ( $linkedin ): ?>
								<li class="directorist-author-social-item"><a target="_blank" href="<?php echo esc_url( $linkedin ); ?>"><?php directorist_icon( 'lab la-linkedin' ); ?></a></li>
							<?php endif; ?>

							<?php if ( $youtube ): ?>
								<li class="directorist-author-social-item"><a target="_blank" href="<?php echo esc_url( $youtube ); ?>"><?php directorist_icon( 'lab la-youtube' ); ?></a></li>
							<?php endif; ?>

						</ul>

					<?php endif; ?>
				</div>
			</div>

		</div>
	</div>

</div>

<?php do_action( 'directorist_author_listing_after_about_section' ); ?>