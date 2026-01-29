<?php
/**
 * Hide specific WooCommerce product categories from shop and category pages
 *
 * @package ForgaTest
 * @subpackage ForgaCommerce
 * @since ForgaCommerce 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

//-----------------------------------------
### TASK 4
//-----------------------------------------

/**
 * Get hidden WooCommerce product category IDs (with caching)
 *
 * @return array Hidden category term IDs
 */
if ( ! function_exists( 'ft_get_hidden_category_ids' ) ) {
    function ft_get_hidden_category_ids(): array {
        $hidden_ids = get_transient( 'ft_hidden_category_ids' );
        if ( false === $hidden_ids ) {
            global $wpdb;
            $hidden_ids = $wpdb->get_col( $wpdb->prepare( "
                SELECT term_id FROM {$wpdb->termmeta}
                WHERE meta_key = %s AND meta_value = %s
            ", 'is_hidden_category', 'yes' ) );
            set_transient( 'ft_hidden_category_ids', $hidden_ids, DAY_IN_SECONDS / 2 );
        }
        return is_array( $hidden_ids ) ? $hidden_ids : [];
    }
}

// Exclude specific product categories from shop and category pages
if ( ! function_exists( 'ft_exclude_hidden_product_categories' ) ) {
    function ft_exclude_hidden_product_categories( string $exclusions, array $args ): string {
        if ( is_admin() || ( isset( $args['taxonomy'] ) && ! in_array( 'product_cat', (array) $args['taxonomy'] ) ) ) {
            return $exclusions;
        }
        $hidden_ids = ft_get_hidden_category_ids();
        if ( ! empty( $hidden_ids ) ) {
            foreach ( $hidden_ids as $id ) {
                $exclusions .= " AND t.term_id <> " . intval( $id );
            }
        }
        return $exclusions;
    }

    add_filter( 'list_terms_exclusions', 'ft_exclude_hidden_product_categories', 10, 2 );
}

if ( ! function_exists( 'ft_clear_hidden_category_cache' ) ) {
    function ft_clear_hidden_category_cache(): void {
        delete_transient( 'ft_hidden_category_ids' );
    }

    add_action( 'saved_product_cat', 'ft_clear_hidden_category_cache' );
}

// Exclude products from hidden categories in product queries
if ( ! function_exists( 'ft_hide_products_from_hidden_categories' ) ) {
    function ft_hide_products_from_hidden_categories( $q ): void {
        if ( is_admin() || ! $q->is_main_query() ) {
            return;
        }
        $hidden_ids = ft_get_hidden_category_ids();
        if ( ! empty( $hidden_ids ) ) {
            $tax_query = (array) $q->get( 'tax_query' );
            $tax_query[] = [
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $hidden_ids,
                'operator' => 'NOT IN',
            ];
            $q->set( 'tax_query', $tax_query );
        }
    }


    add_action( 'woocommerce_product_query', 'ft_hide_products_from_hidden_categories' );
}

//-----------------------------------------
// Custom term meta field for "Hide Category"
//-----------------------------------------

/**
 * "Hide Category" field to Product Category Create screen
 */
if ( ! function_exists( 'ft_add_category_hidden_field' ) ) {
    function ft_add_category_hidden_field(): void {
        ?>
        <div class="form-field">
            <label for="is_hidden_category">
                <input type="checkbox" name="is_hidden_category" id="is_hidden_category" value="yes">
                <?php _e( 'Hide from listings & menus', 'forgatest' ); ?>
            </label>
            <p class="description"><?php _e( 'The category remains accessible via direct URL.', 'forgatest' ); ?></p>
        </div>
        <?php
    }

    add_action( 'product_cat_add_form_fields', 'ft_add_category_hidden_field', 10 );
}

/**
 * "Hide Category" field to Product Category Edit screen
 */
if ( ! function_exists( 'ft_edit_category_hidden_field' ) ) {
    function ft_edit_category_hidden_field( $term, $taxonomy ): void {
        $is_hidden = get_term_meta( $term->term_id, 'is_hidden_category', true );
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e( 'Hide from listings', 'forgatest' ); ?></label></th>
            <td>
                <label for="is_hidden_category">
                    <input type="checkbox" name="is_hidden_category" id="is_hidden_category"
                           value="yes" <?php checked( $is_hidden, 'yes' ); ?>>
                    <?php _e( 'Hide this category from menus and catalog listings', 'forgatest' ); ?>
                </label>
            </td>
        </tr>
        <?php
    }

    add_action( 'product_cat_edit_form_fields', 'ft_edit_category_hidden_field', 10, 2 );
}

/**
 * Save the custom meta data
 */
if ( ! function_exists( 'ft_save_category_hidden_field' ) ) {
    function ft_save_category_hidden_field( $term_id ): void {
        $is_hidden = isset( $_POST['is_hidden_category'] ) ? 'yes' : 'no';
        update_term_meta( $term_id, 'is_hidden_category', $is_hidden );
    }

    add_action( 'saved_product_cat', 'ft_save_category_hidden_field', 10, 1 );
}
