<?php
/**
 * @author  wpWax
 * @since   7.0
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$announcements = $dashboard->get_announcements();
?>

<div class="directorist-announcement-wrapper">

	<?php if ( !empty( $announcements ) ) : ?>

			<?php foreach ( $announcements as $announcement_id => $announcement ): ?>

				<div class="directorist-announcement" data-post-id="<?php echo esc_attr( $announcement_id ); ?>">

					<div class="directorist-announcement__date">
						<span class="directorist-announcement__date__part-one"><?php echo get_the_date( 'd', $announcement_id ) ?></span>
						<span class="directorist-announcement__date__part-two"><?php echo get_the_date( 'M', $announcement_id ) ?></span>
						<span class="directorist-announcement__date__part-three"><?php echo get_the_date( 'Y', $announcement_id ) ?></span>
					</div>

					<div class="directorist-announcement__content">
						<h3 class="directorist-announcement__title"><?php echo esc_html( $announcement['title'] ); ?></h3>
						<p class="directorist-announcement__message"><?php echo esc_html( $announcement['content'] ); ?></p>
					</div>

					<div class="directorist-announcement__close">
						<a href="#" class="close-announcement"><?php directorist_icon( 'las la-times' ); ?></a>
					</div>

				</div>

			<?php endforeach; ?>

	<?php else: ?>

		<p class="directorist_not-found"><?php esc_html_e( 'No announcements found', 'directorist' ) ?></p>

	<?php endif; ?>

</div>