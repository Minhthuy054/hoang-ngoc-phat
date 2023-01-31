<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 7.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$p_id                    = $listing_form->get_add_listing_id();
$price                   = get_post_meta( $p_id, '_price', true );
$price_range             = get_post_meta( $p_id, '_price_range', true );
$atbd_listing_pricing    = get_post_meta( $p_id, '_atbd_listing_pricing', true );
$price_placeholder       = get_directorist_option( 'price_placeholder', __( 'Price of this listing. Eg. 100', 'directorist' ) );
$price_range_placeholder = get_directorist_option( 'price_range_placeholder', __( 'Price Range', 'directorist' ) );
$allow_decimal           = get_directorist_option( 'allow_decimal', 1 );
$currency                = get_directorist_option( 'g_currency', 'USD' );
$c_symbol                = atbdp_currency_symbol( $currency );
$current_price_type      = '';
?>

<div class="directorist-form-group directorist-form-pricing-field price-type-<?php echo esc_attr( $data['pricing_type'] ); ?>">

	<?php $listing_form->field_label_template( $data ); ?>

	<input type="hidden" id="atbd_listing_pricing" value="<?php echo esc_attr( $atbd_listing_pricing ); ?>">

	<?php
	if ( $data['pricing_type'] == 'price_unit' || $data['pricing_type'] == 'price_range' ) {
		$pricing_type_value = ( $data['pricing_type'] == 'price_unit' ) ? 'price' : 'range';
		?>
		<input type="hidden" name="atbd_listing_pricing" value="<?php echo esc_attr( $pricing_type_value ); ?>">
		<?php
	}
	?>

	<div class="directorist-form-pricing-field__options">
		<?php
		if ( $data['pricing_type'] == 'both' ) {
			$checked =  ( $atbd_listing_pricing == 'price' || empty($p_id) ) ? ' checked' : '';
			$current_price_type = ( ! empty( $checked ) ) ? 'price_unit' : $current_price_type;
			?>
			<div class="directorist-checkbox directorist_pricing_options">
				<input type="checkbox" id="price_selected" value="price" name="atbd_listing_pricing"<?php echo esc_attr( $checked ); ?>>
				<label for="price_selected" class="directorist-checkbox__label" data-option="price"><?php echo esc_html( $data['price_unit_field_label'] );?></label>
			</div>
			<?php
		}


		if ( $data['pricing_type'] == 'both' ) {

			$current_price_type = ( checked( $atbd_listing_pricing, 'range', false ) ) ? 'price_range' : $current_price_type;

			if ( ! empty( $price_unit_checkbox ) ) : ?>
				<span class="directorist-form-pricing-field__options__divider"><?php esc_html_e('Or', 'directorist'); ?></span>
			<?php endif; ?>

			<div class="directorist-checkbox directorist_pricing_options">
				<input type="checkbox" id="price_range_selected" value="range" name="atbd_listing_pricing"<?php checked( $atbd_listing_pricing, 'range' ); ?>>
				<label for="price_range_selected" class="directorist-checkbox__label" data-option="price_range"><?php echo esc_html( $data['price_range_label'] );?></label>
			</div>
			<?php
		}
		?>
	</div>

	<?php
	if ( $data['pricing_type'] == 'both' || $data['pricing_type'] == 'price_unit' ) {
		$step = $allow_decimal ? 'any' : 1;
		?>
		<input type="<?php echo esc_attr( $data['price_unit_field_type'] ); ?>" step="<?php echo esc_attr( $step ); ?>" id="price" name="price" value="<?php echo esc_attr($price); ?>" class="directorist-form-element directory_field directory_pricing_field" placeholder="<?php echo esc_attr($price_placeholder); ?>"/>
		<?php
	}

	if ( $data['pricing_type'] == 'both' || $data['pricing_type'] == 'price_range' ) {
		?>
		<select class="directorist-form-element directory_field directory_pricing_field" id="price_range" name="price_range">
			<option value=""><?php echo esc_html($price_range_placeholder); ?></option>

			<option value="skimming"<?php selected($price_range, 'skimming'); ?>><?php printf( '%s (%s)', esc_html__('Ultra High', 'directorist'), esc_html( str_repeat($c_symbol, 4) ) );?></option>

			<option value="moderate" <?php selected($price_range, 'moderate'); ?>><?php printf( '%s (%s)', esc_html__('Expensive ', 'directorist'), esc_html( str_repeat($c_symbol, 3) ) );?></option>

			<option value="economy" <?php selected($price_range, 'economy'); ?>><?php printf( '%s (%s)', esc_html__('Moderate ', 'directorist'), esc_html( str_repeat($c_symbol, 2) ) );?></option>

			<option value="bellow_economy" <?php selected($price_range, 'bellow_economy'); ?>><?php printf( '%s (%s)', esc_html__('Cheap', 'directorist'), esc_html( str_repeat($c_symbol, 1) ) );?></option>
		</select>
		<?php
	}
	?>

</div>