<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Return early when review is disabled.
if ( ! directorist_is_review_enabled() ) {
	return;
}
?>
<span class="directorist-info-item directorist-rating-meta directorist-rating-transparent">
    <?php echo wp_kses_post( $listings->loop['review']['review_stars'] ); ?>
    <span class="directorist-rating-avg"><?php echo esc_html( $listings->loop['review']['average_reviews'] ); ?></span>
</span>
