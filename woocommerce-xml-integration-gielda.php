<?php
/*
Plugin Name: WooCommerce Feed Slowfashion
Description: Wtyczka generuje pliki XML, umożliwiający integracje sklepu z Slowfashion.pl.
Version: 1.0.1
Author: Slowfashion.pl
Author URI: https://jestemslow.pl
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'init', function() {
    if ( class_exists( 'woocommerce' ) ) {
        new Woocommerce_Slowfashion();
    }
} );

/**
 * Plugin-starter class
 */
class Woocommerce_Slowfashion {

    /**
     * A path to plugin file
     * @var string|null
     */
    private static $plugin_file = null;

    public function __construct() {
        $settings_page = new Woocommerce_Slowfashion_Admin_Settings_Page( self::get_plugin_file() );
	    $settings_page->_run();
    }

    /**
     * Retuns the path to plugin file
     * @return string
     */
    public static function get_plugin_file() {

        if ( is_null( self::$plugin_file ) ) {
            self::$plugin_file = __FILE__;
        }

        return self::$plugin_file;
    }

    /**
     * class loader method
     */
    public static function load_classes() {
        // vendor classes
        require_once('classes/vendor/abstract-class-wp-helpers.php');
        require_once('classes/vendor/woocommerce-extended-categories/class-woocommerce-extended-categories.php');
        require_once('classes/vendor/class-wp-templater.php');

        // plugin classes
        require_once('classes/abstract-class-common.php');
        require_once('classes/final-class-translations.php');
        require_once('classes/class-admin-settings-page.php');

        // generator classes
        require_once('classes/sites/abstract-class-site.php');
	    require_once( 'classes/sites/class-slowfashion.php' );
	    require_once('classes/class-generator.php');
    }
}

Woocommerce_Slowfashion::load_classes();

add_filter( 'cron_schedules', function( $schedules ) {
    $schedules['price_comparison'] = array(
        'interval' => 60*60*3, // each three hours
        'display' => __( 'Price comparison sites interval' )
    );
    return $schedules;
} );

/**
 * While plugin is activated we need to set up a hook which will be used as
 * a cron-job.
 */
register_activation_hook( __FILE__, function() {
    Woocommerce_Slowfashion::load_classes();
    wp_schedule_event( time(), 'price_comparison', 'generate_xml_file_integration_slowfashion_data' );
});


/**
 * Deactivate cron-job hook when deactivating the plugin
 */
register_deactivation_hook( __FILE__, function() {
	wp_clear_scheduled_hook( 'generate_xml_file_integration_slowfashion_data' );
});
