<?php
/**
 * Widget Trésorerie.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cdg_widget_tresorerie_default_layout' ) ) {
    /**
     * Configuration par défaut du widget Trésorerie.
     *
     * @return array
     */
    function cdg_widget_tresorerie_default_layout() {
        return array(
            'x'      => 0,
            'y'      => 0,
            'width'  => 2,
            'height' => 1,
        );
    }
}

if ( ! function_exists( 'cdg_render_widget_tresorerie' ) ) {
    /**
     * Affiche le contenu du widget Trésorerie.
     */
    function cdg_render_widget_tresorerie() {
        $cash_balance   = 12500.75;
        $incoming       = 4890.00;
        $outgoing       = 3200.45;
        $formatted_cash = number_format_i18n( $cash_balance, 2 );
        $formatted_in   = number_format_i18n( $incoming, 2 );
        $formatted_out  = number_format_i18n( $outgoing, 2 );
        ?>
        <div class="cdg-widget-content cdg-widget-tresorerie">
            <p class="cdg-balance"><?php esc_html_e( 'Solde actuel', 'custom-dashboard-grid' ); ?> : <strong><?php echo esc_html( $formatted_cash ); ?> €</strong></p>
            <ul>
                <li><?php esc_html_e( 'Entrées prévues', 'custom-dashboard-grid' ); ?> : <span class="cdg-positive"><?php echo esc_html( $formatted_in ); ?> €</span></li>
                <li><?php esc_html_e( 'Sorties prévues', 'custom-dashboard-grid' ); ?> : <span class="cdg-negative"><?php echo esc_html( $formatted_out ); ?> €</span></li>
            </ul>
        </div>
        <?php
    }
}
