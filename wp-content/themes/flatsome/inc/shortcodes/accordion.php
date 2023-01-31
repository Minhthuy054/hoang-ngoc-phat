<?php
/**
 * Accordion Shortcode
 *
 * Accordion and Accordion Item Shortcode builder.
 *
 * @author UX Themes
 * @package Flatsome/Shortcodes/Accordion
 * @version 3.9.0
 */

$flatsome_accordion_state = array();

/**
 * Output the accordion shortcode.
 *
 * @param array  $atts Shortcode attributes.
 * @param string $content Accordion content.
 *
 * @return string.
 */
function ux_accordion( $atts, $content = null ) {
	global $flatsome_accordion_state;

	extract(shortcode_atts(array(
		'auto_open' => '',
		'open'      => '',
		'title'     => '',
		'class'     => '',
	), $atts));

	if ($auto_open) $open = 1;

	array_push( $flatsome_accordion_state, array(
		'open'    => (int) $open,
		'current' => 1,
	) );

	$classes                 = array( 'accordion' );
	if ( $class ) $classes[] = $class;

	if ($title) $title = '<h3 class="accordion_title">' . $title . '</h3>';

	$result = $title . '<div class="' . implode( ' ', $classes ) . '">' . do_shortcode( $content ) . '</div>';

	array_pop( $flatsome_accordion_state );

	return $result;
}
add_shortcode( 'accordion', 'ux_accordion' );


/**
 * Output the accordion-item shortcode.
 *
 * @param array  $atts Shortcode attributes.
 * @param string $content Accordion content.
 *
 * @return string.
 */
function ux_accordion_item( $atts, $content = null ) {
	global $flatsome_accordion_state;

	$current  = count( $flatsome_accordion_state ) - 1;
	$state    = isset( $flatsome_accordion_state[ $current ] )
		? $flatsome_accordion_state[ $current ]
		: null;

	extract(shortcode_atts(array(
		'id'    => 'accordion-' . wp_rand(),
		'title' => 'Accordion Panel',
		'class' => '',
	), $atts));

	$is_open       = false;
	$classes       = array( 'accordion-item' );
	$title_classes = array( 'accordion-title', 'plain' );

	if ( is_array( $state ) && $state['current'] === $state['open'] ) {
		$is_open         = true;
		$title_classes[] = 'active';
	}

	if ( $class ) $classes[] = $class;

	if ( isset( $flatsome_accordion_state[ $current ]['current'] ) ) {
		$flatsome_accordion_state[ $current ]['current']++;
	}

	return '<div id="' . esc_attr( $id ) . '" class="' . implode( ' ', $classes ) . '"><a id="' . esc_attr( $id ) . '-label" href="#" class="' . implode( ' ', $title_classes ) . '" aria-expanded="' . ( $is_open ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $id ) . '-content"><button class="toggle" aria-label="' . esc_attr__( 'Toggle', 'flatsome' ) . '"><i class="icon-angle-down"></i></button><span>' . $title . '</span></a><div id="' . esc_attr( $id ) . '-content" class="accordion-inner"' . ( $is_open ? ' style="display: block;"' : '' ) . ' aria-labelledby="' . esc_attr( $id ) . '-label">' . do_shortcode( $content ) . '</div></div>';
}
add_shortcode( 'accordion-item', 'ux_accordion_item' );
