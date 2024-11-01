<?php
/* *
 * Template renders a group of attributes (site properties) using WooCommerce
 * built it function woocommerce_wp_text_input
 * @param array $attributes
 * @param string $identifier
 * @param array $meta_values
 * @param string|null $section
 */

foreach ( $attributes as $id => $translation ) {
    woocommerce_wp_text_input( array(
        'id' => $identifier . '_' . $id,
        'label' => $id,
        'name' => $identifier . ( !isset( $section ) ? '' : '[' . $section . ']' ) . '[' . $id . ']',
        'description' => $translation,
        'value' => isset( $meta_values[$id] ) ? $meta_values[$id] : ''
    ) );
}