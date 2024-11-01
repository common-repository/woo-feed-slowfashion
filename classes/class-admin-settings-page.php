<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * performs actions on admin panel settings page
 */
class Woocommerce_Slowfashion_Admin_Settings_Page extends Slowfashion_Common {

	// status codes for category updates
    const STATUS_SUCCESS = 10;
    const STATUS_NOT_FOUND = 11;
    const STATUS_FAILED = 12;

	// status codes for xml file updates
	const STATUS_XML_UPDATE_SUCCESS = 13;
	const STATUS_XML_UPDATE_FAIL = 14;
	const STATUS_XML_NOT_FOUND = 15;

    // default value of attribute mapping
    const DEFAULT_ATTRIBUTE_MAP = '0';

    /**
     * @var array
     */
    private static $update_buttons = array();
	private static $xml_update_buttons = array();

    /**
     * Default constructor
     * @param string $file - plugin file
     */
    public function __construct( $file ) {

        parent::__construct( $file );
    }

    /**
     * Method sets up the hooks
     */
    public function _run() {
        require_once('woocommerce-attribute-settings.php');


        // hooks for settings
        add_action( 'admin_menu', array( $this, 'admin_menu_item' ) );
        add_action( 'admin_init', array( $this, 'register_plugin_options' ) );
    }

    /**
     * Adding the submenu to WooCommerce menu
     */
    public function admin_menu_item() {

        add_submenu_page(
            'woocommerce',
            $this->get_translation( 'price_comparison' ),
            $this->get_translation( 'price_comparison' ),
            'manage_options',
            self::PLUGIN_IDENTIFIER,
            array( $this, 'display_admin_settings' )
        );
    }

    /**
     * Render the template with settings
     */
    public function display_admin_settings() {

        $sites = array(
            new Slowfashion_Feed( $this->plugin_file ),
        );
        $sites_data = array();

        foreach( $sites as $site ) {
			if(file_exists($site->get_xml_output_filename(true))){ $timestamp = filemtime( $site->get_xml_output_filename( true ) ); }else{ $timestamp=false; }
            $last_update = $this->get_translation( 'file_not_generated' );
            if ( $timestamp ) {
                $last_update = date_i18n( get_option( 'links_updated_date_format' ), $timestamp );
            }

            $sites_data[$site->get_site_name()] = array(
                'url' => $site->get_xml_url(),
                'last_update' => $last_update
            );
        }

        $this->templater->render( 'admin/settings', array(
            'plugin_identifier' => self::PLUGIN_IDENTIFIER,
            'files' => $sites_data,
            'header_label' => $this->get_translation( 'price_comparison' ),
            'save_label' => $this->get_translation( 'save_changes' ),
            'table_labels' => array(
                'site_name' => $this->get_translation( 'site_name' ),
                'site_url' => $this->get_translation( 'site_url' ),
                'update_time' => $this->get_translation( 'update_time' )
            )
        ) );
    }

    /**
     * Category status message getter
     * @param string $info 'type' or 'message'
     * @param array $status
     * @return string
     */
    private function get_category_status_data( $status, $info = 'type' ) {

        $result = '';
        $status_code = $status['code'];

        switch( $status_code ) {
            case self::STATUS_SUCCESS:
                $result = ( $info === 'type' ) ? 'updated' :
                          $this->get_translation( 'settings_and_categories_saved_' . $status['site_name'] );
                break;

            case self::STATUS_NOT_FOUND:
                $result = ( $info === 'type' ) ? 'updated' : $this->get_translation( 'settings_saved' );
                break;

            case self::STATUS_FAILED:
                $result = ( $info === 'type' ) ? 'error' :  $this->get_translation( 'settings_category_failure' );
                break;
        }

        return $result;
    }

	private function get_xml_status_data($status, $info = 'type'){
		$result = '';
		$status_code = $status['code'];

		switch( $status_code ) {
            case self::STATUS_XML_UPDATE_SUCCESS:
                $result = ( $info === 'type' ) ? 'updated' :
                          $this->get_translation( 'xml_update_success_' . $status['site_name'] );
                break;

            case self::STATUS_XML_NOT_FOUND:
                $result = ( $info === 'type' ) ? 'updated' : $this->get_translation( 'xml_update_unknown' );
                break;

            case self::STATUS_XML_UPDATE_FAIL:
                $result = ( $info === 'type' ) ? 'error' :  $this->get_translation( 'xml_update_fail' );
                break;
        }

		return $result;
	}

    /**
     * Method caches and returns a list of update buttons identifiers
     * @return array
     */
    private function get_update_buttons() {

        if ( count( self::$update_buttons ) === 0 ) {
            self::$update_buttons = array(
                'Slowfashion_Feed' => self::SETTING_SLOWFASHION_UPDATE_CATEGORIES,
            );
        }

        return self::$update_buttons;
    }

    /**
     * Method caches and returns a list of xml update buttons identifiers
     * @return array
     */
	private function get_xml_update_buttons() {
        if ( count( self::$xml_update_buttons ) === 0 ) {
            self::$xml_update_buttons = array(
	            self::SETTING_SLOWFASHION_UPDATE_FEED => self::SETTING_SLOWFASHION_UPDATE_FEED,
            );
        }

		return self::$xml_update_buttons;
	}


    /**
     * Registering the settings, sections and field for plugin settings page
     */
    public function register_plugin_options() {

        $xml_update_buttons = $this->get_xml_update_buttons();

        register_setting(
            self::PLUGIN_IDENTIFIER,
            self::PLUGIN_IDENTIFIER,
            array( $this, 'validate_settings' )
        );

        add_settings_section(
            self::PLUGIN_IDENTIFIER . '_slowfashion',
            null,
            function(){},
            self::PLUGIN_IDENTIFIER
        );

        add_settings_field(
            $xml_update_buttons[self::SETTING_SLOWFASHION_UPDATE_FEED],
            $this->get_translation( 'update_label' ),
            array( $this, 'render_button' ),
            self::PLUGIN_IDENTIFIER,
            self::PLUGIN_IDENTIFIER . '_slowfashion',
            array(
                'action' => $xml_update_buttons[self::SETTING_SLOWFASHION_UPDATE_FEED],
                'button_text' => $this->get_translation( 'update_xml' ),
                'plugin_identifier' => self::PLUGIN_IDENTIFIER
            )
        );
    }

    /**
     * Method is used when adding new settings field. In this particular case it renders ajax button.
     * @param array $params
     */
    public function render_button( $params ) {

        $this->templater->render( 'admin/button', array(
            'button_text' => $params['button_text'],
            'action' => $params['action'],
            'plugin_identifier' => $params['plugin_identifier'],
            'manual_upload' => isset($params['manual_upload']) ? $params['manual_upload'] : false,
            'site' => isset($params['site']) ? $params['site'] : 'none',
            'categories_url' => isset($params['categories_url']) ? $params['categories_url'] : 'none'
        ) );
    }

    /**
     * Method is used when adding new settings field. In this particular case it renders input.
     * @param array $params
     */
    public function render_text_input( $params ) {

        $this->templater->render( 'admin/text_input', array(
            'action' => $params['action'],
            'plugin_identifier' => $params['plugin_identifier']
        ) );
    }

    /**
     * Method renders a select list
     * @param array $params
     */
    public function render_select_list( $params ) {

        $this->templater->render( 'admin/select-list', array(
            'options' => $params['options'],
            'plugin_identifier' => $params['plugin_identifier'],
            'action' => $params['action'],
            'groups_translation' => isset( $params['groups_translation'] ) ? $params['groups_translation'] : array(),
            'default' => $params['default'],
            'selected' => $this->get_setting_option( $params['action'] , $params['default']['value'] )
        ) );
    }

    /**
     * Update the categories for pushed button. Method checks whether received data is
     * a correct and not empty array. Only in this case category list is updated.
     * @param array $post_input
     * @return array
     */
    private function update_categories( &$post_input ) {

        update_option( 'color_attribute_name', $post_input );

        $status = self::STATUS_NOT_FOUND;
        $site_name = '';
        $post_input = is_array( $post_input ) ? $post_input : array();

        foreach( $this->get_update_buttons() as $site_id => $post_id ) {

            if ( !array_key_exists( $post_id, $post_input) ) {
                // load category data from database
                $post_input[$post_id] = self::get_setting_option( $post_id, array() );
                continue;
            }

            $generator = new Woocommerce_Slowfashion_Generator();
            if( array_key_exists($site_id.'_manual_upload', $post_input)){
                $file_name = $this->parse_file($_FILES, $site_id);
                $remote_categories = $generator->get_site_instance($site_id)->get_remote_categories($file_name);
            }
            else {
                $remote_categories = $generator->get_site_instance($site_id)->get_remote_categories();
            }

            if ( is_array( $remote_categories ) && count( $remote_categories ) > 0 ) {

                $post_input[$post_id] = $remote_categories;
                $status = self::STATUS_SUCCESS;
                $site_name = $site_id;

                break;
            }else{
				$post_input[$post_id] = self::get_setting_option( $post_id, array() );
			}

            $status = self::STATUS_FAILED;
        }

        return array(
            'site_name' => $site_name,
            'code' => $status
        );
    }

    private function parse_file( $data, $site_id ){
        if(array_key_exists(self::PLUGIN_IDENTIFIER, $data)){
            if(is_array($data[self::PLUGIN_IDENTIFIER]['tmp_name'])){
                return $data[self::PLUGIN_IDENTIFIER]['tmp_name'][$site_id.'_file_upload'];
            }
            elseif(isset($data[self::PLUGIN_IDENTIFIER]['tmp_name'])){
                return $data[self::PLUGIN_IDENTIFIER]['tmp_name'];
            }
            else{
                return false;
            }
        }
    }

	private function update_xml( &$post_input ) {

		$status['code'] = self::STATUS_XML_NOT_FOUND;
        $post_input = is_array( $post_input ) ? $post_input : array();

		$generator = new Woocommerce_Slowfashion_Generator();

		foreach( $this->get_xml_update_buttons() as $site_id => $post_id ) {
			if(array_key_exists($post_id,$post_input)){
				if($generator->_run_single($site_id)){
					$status['code'] = self::STATUS_XML_UPDATE_SUCCESS;
				} else{
					var_dump($site_id, $post_id, $post_input);
					$status['code'] = self::STATUS_XML_UPDATE_FAIL;
					exit;
				}
				$status['site_name'] = $site_id;
				continue;
			}
		}

        add_settings_error(
            self::PLUGIN_IDENTIFIER,
            self::PLUGIN_IDENTIFIER,
            $this->get_xml_status_data( $status, 'message' ),
            $this->get_xml_status_data( $status, 'type' )
        );

		return true;
	}

  private function update_settings( $post_input ) {
	    if( isset( $post_input['slowfashion_color_attribute_name'] ) ) {
	        update_option( 'slowfashion_color_attribute_name', sanitize_title($post_input['slowfashion_color_attribute_name']) );
	    }
	    if( isset( $post_input['slowfashion_size_attribute_name'] ) ) {
	        update_option( 'slowfashion_size_attribute_name', sanitize_title($post_input['slowfashion_size_attribute_name']) );
	    }

        if( isset($post_input['slowfashion_SKU_mapping']) ) {
            $val = sanitize_text_field($post_input['slowfashion_SKU_mapping']);
            update_option('slowfashion_SKU_mapping', $val);
        }
  }

    /**
     * Validate (and return stored value) the user (editor) input. As for now
     * there's not much to validate, so $input is just returned.
     * @param array $post_input
     * @return array
     */
    public function validate_settings( $post_input ) {

  		if(is_array($post_input)){
  			foreach($this->get_xml_update_buttons() as $key){
  				if(array_key_exists($key,$post_input)){
  					$this->update_xml($post_input);
  					continue;
  				};
  			};
  		}

      $this->update_settings( $post_input );

  		$status = $this->update_categories( $post_input );

        add_settings_error(
            self::PLUGIN_IDENTIFIER,
            self::PLUGIN_IDENTIFIER,
            $this->get_category_status_data( $status, 'message' ),
            $this->get_category_status_data( $status, 'type' )
        );

		return $post_input;
    }
}
