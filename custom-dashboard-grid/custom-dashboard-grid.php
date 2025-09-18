<?php
/**
 * Plugin Name: Custom Dashboard Grid
 * Description: Ajoute un tableau de bord administrateur personnalisé basé sur Gridstack.
 * Version: 1.0.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Custom_Dashboard_Grid {

    /**
     * Initialisation du plugin.
     */
    public static function init() {
        self::define_constants();
        self::include_files();
        add_action( 'admin_menu', array( __CLASS__, 'register_dashboard_page' ) );
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_assets' ) );
    }

    /**
     * Définit les constantes utiles.
     */
    private static function define_constants() {
        if ( ! defined( 'CDG_PLUGIN_DIR' ) ) {
            define( 'CDG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }

        if ( ! defined( 'CDG_PLUGIN_URL' ) ) {
            define( 'CDG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        }
    }

    /**
     * Inclut les fichiers nécessaires.
     */
    private static function include_files() {
        require_once CDG_PLUGIN_DIR . 'includes/ajax-save-layout.php';
        require_once CDG_PLUGIN_DIR . 'includes/dashboard-structure.php';
        require_once CDG_PLUGIN_DIR . 'includes/widgets/widget-tresorerie.php';
        require_once CDG_PLUGIN_DIR . 'includes/widgets/widget-depenses.php';
    }

    /**
     * Ajoute la page du tableau de bord personnalisé.
     */
    public static function register_dashboard_page() {
        add_menu_page(
            __( 'Tableau de bord personnalisé', 'custom-dashboard-grid' ),
            __( 'Tableau personnalisé', 'custom-dashboard-grid' ),
            'manage_options',
            'custom-dashboard-grid',
            array( __CLASS__, 'render_dashboard_page' ),
            'dashicons-screenoptions',
            3
        );
    }

    /**
     * Affiche la page du tableau de bord.
     */
    public static function render_dashboard_page() {
        $user_id       = get_current_user_id();
        $saved_layout  = get_user_meta( $user_id, 'custom_dashboard_grid_layout', true );
        $layout        = is_array( $saved_layout ) ? $saved_layout : array();

        echo '<div class="wrap custom-dashboard-grid">';
        echo '<h1>' . esc_html__( 'Tableau de bord personnalisé', 'custom-dashboard-grid' ) . '</h1>';
        cdg_render_dashboard_structure( $layout );
        echo '</div>';
    }

    /**
     * Charge les scripts et styles pour la page du dashboard.
     *
     * @param string $hook Hook courant.
     */
    public static function enqueue_assets( $hook ) {
        if ( 'toplevel_page_custom-dashboard-grid' !== $hook ) {
            return;
        }

        wp_enqueue_style(
            'gridstack-css',
            'https://cdn.jsdelivr.net/npm/gridstack@8.4.2/dist/gridstack.min.css',
            array(),
            '8.4.2'
        );

        wp_enqueue_style(
            'custom-dashboard-grid',
            CDG_PLUGIN_URL . 'assets/css/dashboard.css',
            array( 'gridstack-css' ),
            '1.0.0'
        );

        wp_enqueue_script( 'jquery-ui-draggable' );
        wp_enqueue_script( 'jquery-ui-resizable' );

        wp_enqueue_script(
            'gridstack-js',
            'https://cdn.jsdelivr.net/npm/gridstack@8.4.2/dist/gridstack-h5.js',
            array( 'jquery' ),
            '8.4.2',
            true
        );

        wp_enqueue_script(
            'custom-dashboard-grid',
            CDG_PLUGIN_URL . 'assets/js/dashboard.js',
            array( 'jquery', 'gridstack-js' ),
            '1.0.0',
            true
        );

        $user_id      = get_current_user_id();
        $saved_layout = get_user_meta( $user_id, 'custom_dashboard_grid_layout', true );

        wp_localize_script(
            'custom-dashboard-grid',
            'CustomDashboardGrid',
            array(
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'nonce'   => wp_create_nonce( 'custom_dashboard_grid_nonce' ),
                'layout'  => is_array( $saved_layout ) ? array_values( $saved_layout ) : array(),
            )
        );
    }
}

Custom_Dashboard_Grid::init();
