<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.3.1
 */

use \Directorist\Helper;

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-author-listing-top directorist-flex directorist-justify-content-between">

	<div>
		<h2 class="directorist-author-listing-top__title"><?php esc_html_e( 'Author Listings' , 'directorist'); ?></h2>
		<div class="directorist-author-listing-type">
		<?php $author->archive_type( $author ); ?>
		</div>
	</div>

	<?php if ( $author->cat_filter_enabled() ): ?>

		<div class="directorist-dropdown directorist-dropdown-js directorist-author-listing-top__dropdown directorist-dropdown-update-js">

			<a class="directorist-dropdown__toggle directorist-dropdown__toggle-js directorist-toggle-has-icon directorist-btn directorist-btn-outline-primary" href="#" id="directorist-dropdown-menu-link"><?php esc_html_e( 'Filter by category', 'directorist'); ?> <span class="atbd_drop-caret"></span></a>

			<div class="directorist-dropdown__links directorist-dropdown__links-js">

				<?php
				foreach ($author->get_listing_categories() as $category) {
					$active_class = ( isset($_GET['category']) && ($category->slug == $_GET['category']) ) ? 'active' : '';
					$link = directorist_add_query_args_with_no_pagination( [ 'category' => $category->slug ] );
					printf( '<a class="directorist-dropdown__links--single %s" href="%s">%s</a>', esc_attr( $active_class ), esc_url( $link ), esc_html( $category->name ) );
				}
				?>

			</div>

		</div>

	<?php endif; ?>

</div>

<?php do_action( 'directorist_author_listings_before_loop' ); ?>
<div class="directorist-author-listing-content">

	<div class="<?php Helper::directorist_row(); ?>">

		<?php foreach ( $listings->post_ids() as $listing_id ): ?>

			<div class="<?php Helper::directorist_column( $author->columns ); ?>">
				<?php $listings->loop_template( 'grid', $listing_id ); ?>
			</div>

		<?php endforeach; ?>

	</div>

	<?php
	if ( $author->listing_pagination_enabled() ) {
		$listings->pagination();
	}
	?>

</div>