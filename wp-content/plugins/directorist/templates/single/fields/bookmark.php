<?php
/**
 * @author  wpWax
 * @since   6.7
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-single-listing-action directorist-action-save directorist-tooltip" data-label="<?php esc_attr_e('Favorite', 'directorist'); ?>" id="atbdp-favourites"><?php echo wp_kses_post( the_atbdp_favourites_link() ); ?></div>