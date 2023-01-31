<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */
?>
<div class='atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom'>
	<?php if ( ! empty( $display_image_map ) ) { ?>
		<div class='media-left'>
			<?php if ( ! $disable_single_listing ) { ?>
				<a href='<?php echo esc_url( get_the_permalink() ); ?>'>
				<?php
			}

			if ( ! empty( $ls_data['listing_prv_img'] ) ) { ?>
				<img src='<?php echo esc_url( $ls_data['prv_image'] ); ?>' alt='<?php echo esc_attr( get_the_title() ); ?>'>
				<?php
			}

			if ( ! empty( $ls_data['listing_img'][0] ) && empty( $ls_data['listing_prv_img'] ) ) { ?>
				<img src='<?php echo esc_url( $ls_data['gallery_img'] ); ?>' alt='<?php echo esc_attr( get_the_title() ); ?>'>
				<?php
			}

			if ( empty( $ls_data['listing_img'][0] ) && empty( $ls_data['listing_prv_img'] ) ) {?>
				<img src='<?php echo esc_url( $ls_data['default_image'] ); ?>' alt='<?php echo esc_attr( get_the_title() ); ?>'>
				<?php
			}

			if ( ! $disable_single_listing ) { ?>
				</a>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>

	<div class='media-body'>
		<?php if ( ! empty( $display_title_map ) ) { ?>
			<div class='atbdp-listings-title-block'>
				<?php if ( ! $disable_single_listing ) { ?>
					<h3 class='atbdp-no-margin'>
						<a href='<?php echo esc_url( get_the_permalink() ); ?>'><?php the_title(); ?></a>
					</h3>
					<?php
				}
				else { ?>
					<h3 class='atbdp-no-margin'><?php the_title();?></h3>
					<?php
				}
				?>
			</div>
			<?php
		}

		if ( ! empty( $ls_data['address'] ) ) {
			if ( ! empty( $display_address_map ) ) { ?>
				<div class='osm-iw-location'>
					<?php directorist_icon( 'las la-map-marker' ); ?>
					<a href='./' class='map-info-link'><?php echo esc_html( $ls_data['address'] ); ?></a>
				</div>
				<?php
			}

			if ( ! empty( $display_direction_map ) ) { ?>
				<div class='osm-iw-get-location'>
					<a href='http://www.google.com/maps?daddr=<?php echo esc_attr( $ls_data['manual_lat'] ) . ',' . esc_attr( $ls_data['manual_lng'] ); ?>' target='_blank'><?php esc_html_e( 'Get Directions', 'directorist' );?></a>
					<?php directorist_icon( 'las la-arrow-right' ); ?>
				</div>
				<?php
			}
		}
		?>
	</div>
</div>