<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// add generator class into previously registered cronjob hook
add_action( 'generate_xml_file_integration_slowfashion_data', array( new Woocommerce_Slowfashion_Generator(), '_run' ) );

/**
 * Main class used for generating XML files for all price comparison sites.
 * It runs in a cron.
 */
class Woocommerce_Slowfashion_Generator {
    /**
     * Contains a list of sites objects
     * @var array
     */
    private $sites = array();

    public function __construct() {
    	$plugin_file = Woocommerce_Slowfashion::get_plugin_file();
	    $this->sites['Slowfashion_Feed'] = new Slowfashion_Feed($plugin_file);
    }

    /**
     * Method checks whether files can be generated and then performs actions
     * @return bool
     */
    public function _run() {
	    foreach ( $this->sites as $site ) {
		    $site->generate_xml();
	    }
	    return true;
    }

	/**
	* Method tries to generate site specified xml file if possible.
	* @param string $site_id (ex. $site_id='slowfashion')
	* @return bool
	*/
	public function _run_single($site_id) {
		if(array_key_exists($site_id,$this->sites)){
			$this->sites[$site_id]->generate_xml();
			return true;
		}

		return false;
	}

    /**
     * @param string $className
     *
     * @return Slowfashion_Site
     * @throws \Exception
     */
    public function get_site_instance( $className ) {

	    if(array_key_exists($className, $this->sites)){
		    return $this->sites[$className];
	    }
	    throw new \Exception( 'There\'s no site class with given name: ' . $className );
    }
}
