<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Parent class slowfashion_for admin and frontpage classes
 */
abstract class Slowfashion_Common extends Slowfashion_Wp_Helpers {

    // plugin identifier
    const PLUGIN_IDENTIFIER = 'woocommerce_xml_integration_slowfashion';

    // category and product settings identifiers
    const ALTERNATIVE_DESCRIPTION = 'slowfashion_alternative_description';

    const CENEO_EXCLUSION = 'slowfashion_exclusion';
    const CENEO_BASKET = 'slowfashion_basket';
    const CENEO_CATEGORIES = 'slowfashion_categories';
    const CENEO_PROPERTIES = 'slowfashion_properties';
    const CENEO_PROPERTY_TYPES = 'slowfashion_property_types';
    const CENEO_TREAT_AS_SINGLE = 'slowfashion_treat_as_single';

    const SLOWFASHION_EXCLUSION = 'slowfashion_exclusion';
    const SLOWFASHION_CATEGORIES = 'slowfashion_categories';
    const SLOWFASHION_PROPERTIES = 'slowfashion_properties';
    const SLOWFASHION_PRODUCER = 'slowfashion_producer';

    // prefix used for settings options
    const SETTINGS_PREFIX = 'pcsi';

    // plugin settings identifiers
    const SETTING_CENEO_UPDATE_CATEGORIES = 'slowfashion_update_categories';
    const SETTING_NOKAUT_UPDATE_CATEGORIES = 'nokaut_update_categories';
    const SETTING_SLOWFASHION_UPDATE_CATEGORIES = 'slowfashion_update_categories';
    const SETTING_CENEO_ATTRIBUTES = 'slowfashion_attributes';
    const SETTING_NOKAUT_ATTRIBUTES = 'nokaut_attributes';
    const SETTING_slowfashion_ATTRIBUTES = 'slowfashion_attributes';
  	const SETTING_CENEO_UPDATE_XML = 'slowfashion_update_xml';
  	const SETTING_NOKAUT_UPDATE_XML = 'nokaut_update_xml';
    const SETTING_SLOWFASHION_UPDATE_FEED = 'Slowfashion_Feed';

    // wp_templater object
    protected $templater = null;
    // plugin file
    protected $plugin_file = null;

    /**
     * Children classes are required to contain a method which is responsible
     * for implementing WordPress hooks
     */
    public abstract function _run();

    /**
     * Constructor which is inherited by class slowfashion_childrens
     * @param string $file - plugin file
     */
    public function __construct( $file ) {

        $this->plugin_file = $file;
        $this->templater = Slowfashion_Wp_Templater::get_instance( plugin_dir_path( $this->plugin_file ) );
    }

    /**
     * Method returns a value of given option name (set on plugin settings page).
     * In case value is not set, it returns a default type value.
     * @param string $name
     * @param mixed $default_value
     * @return mixed
     */
    public static function get_setting_option( $name, $default_value ) {

        $options = get_option( self::PLUGIN_IDENTIFIER );

        if ( isset( $options[$name] ) && gettype( $default_value ) === gettype( $options[$name] ) ) {
            return $options[$name];
        }

        return $default_value;
    }
}
