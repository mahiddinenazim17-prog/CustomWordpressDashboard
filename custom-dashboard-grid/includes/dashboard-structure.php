<?php
/**
 * Structure du tableau de bord et inclusion des widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cdg_get_dashboard_widgets' ) ) {
    /**
     * Récupère la liste des widgets disponibles.
     *
     * @return array
     */
    function cdg_get_dashboard_widgets() {
        return array(
            'tresorerie' => array(
                'title'    => __( 'Trésorerie', 'custom-dashboard-grid' ),
                'callback' => 'cdg_render_widget_tresorerie',
                'default'  => cdg_widget_tresorerie_default_layout(),
            ),
            'depenses'   => array(
                'title'    => __( 'Dépenses', 'custom-dashboard-grid' ),
                'callback' => 'cdg_render_widget_depenses',
                'default'  => cdg_widget_depenses_default_layout(),
            ),
        );
    }
}

if ( ! function_exists( 'cdg_render_dashboard_structure' ) ) {
    /**
     * Affiche la grille Gridstack et insère les widgets.
     *
     * @param array $saved_layout Données de layout sauvegardées.
     */
    function cdg_render_dashboard_structure( $saved_layout = array() ) {
        $widgets = cdg_get_dashboard_widgets();
        $layout  = is_array( $saved_layout ) ? $saved_layout : array();

        echo '<div class="cdg-dashboard-wrapper">';
        echo '<div class="grid-stack" data-gs-column="4" gs-column="4">';

        foreach ( $widgets as $widget_id => $widget ) {
            $settings = isset( $layout[ $widget_id ] ) ? wp_parse_args( $layout[ $widget_id ], $widget['default'] ) : $widget['default'];

            $x = isset( $settings['x'] ) ? (int) $settings['x'] : 0;
            $y = isset( $settings['y'] ) ? (int) $settings['y'] : 0;
            $w = isset( $settings['width'] ) ? (int) $settings['width'] : 1;
            $h = isset( $settings['height'] ) ? (int) $settings['height'] : 1;

            echo '<div class="grid-stack-item"';
            echo ' gs-id="' . esc_attr( $widget_id ) . '"';
            echo ' gs-x="' . esc_attr( $x ) . '"';
            echo ' gs-y="' . esc_attr( $y ) . '"';
            echo ' gs-w="' . esc_attr( $w ) . '"';
            echo ' gs-h="' . esc_attr( $h ) . '"';
            echo ' data-gs-id="' . esc_attr( $widget_id ) . '"';
            echo ' data-gs-x="' . esc_attr( $x ) . '"';
            echo ' data-gs-y="' . esc_attr( $y ) . '"';
            echo ' data-gs-width="' . esc_attr( $w ) . '"';
            echo ' data-gs-height="' . esc_attr( $h ) . '"';
            echo '>';

            echo '<div class="grid-stack-item-content">';
            echo '<h2>' . esc_html( $widget['title'] ) . '</h2>';

            if ( is_callable( $widget['callback'] ) ) {
                call_user_func( $widget['callback'] );
            }

            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';
    }
}
