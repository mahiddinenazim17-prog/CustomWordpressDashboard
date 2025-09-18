<?php
/**
 * Widget Dépenses.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'cdg_widget_depenses_default_layout' ) ) {
    /**
     * Configuration par défaut du widget Dépenses.
     *
     * @return array
     */
    function cdg_widget_depenses_default_layout() {
        return array(
            'x'      => 2,
            'y'      => 0,
            'width'  => 2,
            'height' => 1,
        );
    }
}

if ( ! function_exists( 'cdg_render_widget_depenses' ) ) {
    /**
     * Affiche le contenu du widget Dépenses.
     */
    function cdg_render_widget_depenses() {
        $expenses = array(
            array(
                'label' => __( 'Marketing', 'custom-dashboard-grid' ),
                'amount' => 980.50,
            ),
            array(
                'label' => __( 'Frais généraux', 'custom-dashboard-grid' ),
                'amount' => 1320.20,
            ),
            array(
                'label' => __( 'Prestations externes', 'custom-dashboard-grid' ),
                'amount' => 760.00,
            ),
        );
        ?>
        <div class="cdg-widget-content cdg-widget-depenses">
            <table class="cdg-table">
                <thead>
                    <tr>
                        <th scope="col"><?php esc_html_e( 'Catégorie', 'custom-dashboard-grid' ); ?></th>
                        <th scope="col" class="cdg-align-right"><?php esc_html_e( 'Montant', 'custom-dashboard-grid' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $expenses as $expense ) : ?>
                    <tr>
                        <td><?php echo esc_html( $expense['label'] ); ?></td>
                        <td class="cdg-align-right"><?php echo esc_html( number_format_i18n( $expense['amount'], 2 ) ); ?> €</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
