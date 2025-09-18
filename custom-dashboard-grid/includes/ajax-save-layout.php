<?php
/**
 * Gestion de la sauvegarde du layout via Ajax.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cdg_handle_save_dashboard_layout' ) ) {
    /**
     * Sauvegarde la disposition du dashboard pour l'utilisateur courant.
     */
    function cdg_handle_save_dashboard_layout() {
        check_ajax_referer( 'custom_dashboard_grid_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => __( 'Action non autorisée.', 'custom-dashboard-grid' ) ), 403 );
        }

        $user_id = get_current_user_id();

        if ( ! $user_id ) {
            wp_send_json_error( array( 'message' => __( 'Utilisateur invalide.', 'custom-dashboard-grid' ) ), 400 );
        }

        $layout_json = isset( $_POST['layout'] ) ? wp_unslash( $_POST['layout'] ) : '';
        $layout_data = json_decode( $layout_json, true );

        if ( ! is_array( $layout_data ) ) {
            wp_send_json_error( array( 'message' => __( 'Format de données invalide.', 'custom-dashboard-grid' ) ), 400 );
        }

        $sanitized_layout = array();

        foreach ( $layout_data as $item ) {
            if ( empty( $item['id'] ) ) {
                continue;
            }

            $widget_id = sanitize_key( $item['id'] );

            $sanitized_layout[ $widget_id ] = array(
                'id'     => $widget_id,
                'x'      => isset( $item['x'] ) ? intval( $item['x'] ) : 0,
                'y'      => isset( $item['y'] ) ? intval( $item['y'] ) : 0,
                'width'  => isset( $item['width'] ) ? max( 1, intval( $item['width'] ) ) : 1,
                'height' => isset( $item['height'] ) ? max( 1, intval( $item['height'] ) ) : 1,
            );
        }

        update_user_meta( $user_id, 'custom_dashboard_grid_layout', $sanitized_layout );

        wp_send_json_success( array( 'layout' => array_values( $sanitized_layout ) ) );
    }
}

add_action( 'wp_ajax_save_dashboard_layout', 'cdg_handle_save_dashboard_layout' );
