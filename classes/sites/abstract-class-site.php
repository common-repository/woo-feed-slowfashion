<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Abstract class which is a parent of all classes of price comparison sites
 */
abstract class Slowfashion_Site extends Slowfashion_Wp_Helpers {
    /**
     * Path to plugin file
     * @var string|null
     */
    protected $plugin_file = null;

    /**
     * Path to plugin directory
     * @var string|null
     */
    protected $plugin_dir = null;

    /**
     * Contains a list of cached products
     * @var WP_Query|null
     */
    private static $products_cache = null;

    /**
     * Stores the directory for uploading xml files
     * @var string|null
     */
    private $upload_dir = null;

    const UPLOAD_DIR =  'xml_slowfashion';

    /**
     * Inheritable constructor which cannot be overriden
     * @param string $plugin_file
     */
    final public function __construct( $plugin_file ) {

        $this->plugin_file = $plugin_file;
        $this->plugin_dir = plugin_dir_path( $this->plugin_file );
        $this->create_directory();
    }

    /** DEPRECATED - makes errors
     * Sorrounds the string with CDATA
     * @param $string
     * @return string
     */
    protected function add_cdata( $string ) {
        if ( strlen( $string ) > 0 ) {
            $string = '<![CDATA[' . $string . ']]>';
        }

        return $string;
    }

	protected function addCData($string, $exnode){
		$node=dom_import_simplexml($exnode);
		$no=$node->ownerDocument;
		$node->appendChild($no->createCDATASection($string));
	}

    /**
     * Method returns true in case when all product categories categories are excluded
     * @param int $product_id
     * @return boolean
     */
    private function all_categories_excluded( $product_id ) {

        $categories = $this->get_correct_categories( $product_id );

        return is_null( $categories ) ? false : ( sizeof( $categories ) === 0 );
    }

    /**
     * Create the XML upload directory
     */
    private function create_directory() {

        if ( !file_exists( $this->get_upload_dir() ) ) {
            wp_mkdir_p( $this->get_upload_dir() );
        }
    }

    /**
     * Creates the XML file in upload directory
     * @param \SimpleXMLElement $xml
     */
    protected function create_final_xml( SimpleXMLElement $xml ) {
        $xml->asXML( $this->get_xml_output_filename( true ) );
    }

    /**
     * Returns full (or not) filename of output xml file
     * @param bool $full
     * @return string
     */
    public function get_xml_output_filename( $full = false ) {

        if ( $full === true ) {
            $return = $this->get_upload_dir() . '/' . $this->xml_output;
        }
        else {
            $return = $this->xml_output;
        }

        return $return;
    }

    /**
     * Method fetches all products from database and puts the result into cache
     * @return WP_Query
     */
    private static function fetch_products() {

        if ( self::$products_cache === null ) {
            self::$products_cache = new WP_Query( array(
                'post_type' => 'product',
                'post_status' => 'publish',
				'posts_per_page' => -1
            ) );
        }

        return self::$products_cache;
    }

    /**
     * Use this method to generate the XML file in cron
     */
    abstract public function generate_xml();

    /**
     * Returns the identifier of exclusion flag for current site
     */
    abstract protected function get_exclusion_identifier();

    /**
     * Returns all of the products which can be included in final XML file
     * @global WP_Product $product
     * @return array
     */
    protected function get_all_products() {

        $all_products = array();
		    $loop = self::fetch_products();

        while( $loop->have_posts() ) {

            $loop->the_post();
            global $product;

            // omit excluded products
            $product_meta = get_post_meta( $product->get_id(), $this->get_exclusion_identifier(), true );
            if ( 'yes' === $product_meta ) {
                continue;
            }

            // omit the products which all categories are excluded
            if ( $this->all_categories_excluded( $product->get_id() ) ) {
                continue;
            }
            
            // Add alternative description
            $alternative_description = get_post_meta( $product->get_id(), 'slowfashion_alternative_description', true );
            if ( !empty($alternative_description) ) {
                $product->set_description($alternative_description);
            }

            $all_products[] = $product;
        }

        return $all_products;
    }

    /**
     * Returns the identifier of site category name
     * @return string
     */
    abstract protected function get_category_identifier();

    /**
     * Method returns an array of categories which are not excluded from xml export
     * @param int $product_id
     * @return array|null
     */
    protected function get_correct_categories( $product_id ) {

        $categories = get_the_terms( $product_id, 'product_cat' );

        $correct_categories = array();

        if ( !is_array( $categories ) ) {
            return null;
        }

        foreach ( $categories as $cat ) {
            $exclude_cat = get_woocommerce_term_meta( $cat->term_id, $this->get_exclusion_identifier() );

            if ( $exclude_cat == 0 ) {
                $correct_categories[] = $cat;
            }
        }

        return $correct_categories;
    }

    /**
     * Method returns a list of product attributes correct for current site.
     * Returned array can be either single or double dimensional.
     * @return array
     */
    public function get_properties() {

        return $this->properties;
    }
    
    /**
     * Automatic translation of site attributes. Works recursive in case of multiple
     * dimensional arrays.
     * @param array $attributes
     * @param array $translated
     * @return array
     */
    protected function translate_attributes( array $attributes, array $translated = array() ) {

        foreach ( $attributes as $id => $attribute ) {
            if ( is_array( $attribute ) ) {
                $translated[$id] = $this->translate_attributes( $attribute );
            }
            else {
                $translated[$id] = $this->get_translation( $attribute );
            }
        }

        return $translated;
    }

    /**
     * Method returns an URL to XML file with categories. It requires to define
     * a url for categories xml (as protected property categories_url)
     */
    public function get_categories_url() {

        return $this->categories_url;
    }

    /**
     * Fetched and caches a list of remote categories
     * @return mixed
     */
    protected function get_categories_list() {

        if ( sizeof( $this->categories_list ) === 0 ) {
            $this->categories_list = Slowfashion_Common::get_setting_option( $this->get_categories_list_identifier(), array() );
        }

        return $this->categories_list;
    }

    /**
     * Returns an identifier of WP option containing categories list for current site
     * @return string
     */
    abstract protected function get_categories_list_identifier();

    /**
     * Contains a logic of picking correct category name. There are two priorities: most important is to return the
     * remote category name. In no remote category is defined, method will return first met category name.
     * @param int $product_id
     * @return string
     */
    protected function get_product_cat( $product_id ) {

        $category_name = '';
        $categories = $this->get_correct_categories( $product_id );
        $remote_categories = $this->get_categories_list();

        if ( !is_array( $categories ) ) {
            return '';
        }

        foreach( $categories as $cat ) {

            // store the name of first category, so it can be returned in case when no remote category will be found
            if ( strlen( $category_name ) === 0 ) {
                $category_name = $cat->name;
            }
            $remote_identifier = get_woocommerce_term_meta( $cat->term_id, $this->get_category_identifier() );

            // if remote category is set, return it directly
            if ( strlen( $remote_identifier ) > 0 && $remote_identifier != 0 && isset( $remote_categories[$remote_identifier] ) ) {
                return $remote_categories[$remote_identifier];
            }
        }

        return $category_name;
    }
    
    protected function get_description( WC_Product $product ) {
        $description = strip_shortcodes($product->get_description());
        return preg_replace("/\[\/?vc_[^\]]*\]/", '', $description); // Remove visual composer tags
    }

    /**
     * Method fetches the remote XML file containing a list of categories
     */
    abstract public function get_remote_categories();

    /**
     * Returns the path for uploading xml files
     * @return string
     */
    protected function get_upload_dir() {

        if ( is_null( $this->upload_dir ) ) {
            $upload_dir = wp_upload_dir();
            $this->upload_dir = $upload_dir['basedir'] . '/' . self::UPLOAD_DIR;
        }

        return $this->upload_dir;
    }

    /**
     * Returns the URL of generated xml file
     * @return string
     */
    public function get_xml_url() {

        $upload_dir = wp_upload_dir();
        if ( !isset( $upload_dir['baseurl'] ) ) {
            return '';
        }

        return $upload_dir['baseurl'] . '/' . self::UPLOAD_DIR . '/' . $this->get_xml_output_filename();
    }

    /**
     * Returns the name of current site
     * @return string
     */
    public function get_site_name() {

        return $this->site_name;
    }

    /**
     * Returns false if attribute is not mapped, or mapped value when opposite
     * @param string $attr_id
     * @return boolean|string
     */
    protected function is_attribute_mapped( $attr_id ) {

        $map = $this->get_attribute_option( $attr_id );

        return ( $map === Woocommerce_Slowfashion_Admin_Settings_Page::DEFAULT_ATTRIBUTE_MAP ) ? false : $map;
    }

    /**
     * Returns a value for mapped attribute
     *
     * @param string $attr_id
     * @return bool|mixed
     */
    protected function get_attribute_option( $attr_id ) {

        $attr_parts = explode( 'pa_', $attr_id );
        if ( !isset( $attr_parts[1] ) ) {
            return false;
        }

        $option_name = 'attribute_' . $this->get_site_name() . '_' . $attr_parts[1];
        return Slowfashion_Common::get_setting_option( $option_name, Woocommerce_Slowfashion_Admin_Settings_Page::DEFAULT_ATTRIBUTE_MAP );
    }

    /**
     * Returns the post ID for variable products or normal product ID for other products
     * @param \WC_Product $product
     * @return int
     */
    protected function get_main_id( WC_Product $product ) {
        return $product->get_type() === 'variation' ? $product->get_parent_id() : $product->get_id();
    }

    /**
     * Offer id contains additional hash code for variation products
     * @param \WC_Product $product
     * @param string $prod_name
     * @return string
     */
    protected function get_offer_id( WC_Product $product, $prod_name ) {

        $offer_id = $product->get_id();
        if ( $product instanceof WC_Product_Variable ) {
            $offer_id .= '-'.md5( $prod_name );
        }

        return $offer_id;
    }

    /**
     * Returns a list of the variations which can be used in the xml
     *
     * @param \WC_Product_Variable $product
     * @param array $attributes
     * @param array $mapped_attributes
     * @return array
     */
    protected function get_product_variations( WC_Product_Variable $product, array $attributes, array $mapped_attributes ) {

        $variations = $product->get_available_variations();
        $prod_variations = array();

        foreach ( $variations as $variation ) {

            $variation_data = array();

            if ( !isset( $variation['attributes'] ) || !is_array( $variation['attributes'] ) ) {
                continue;
            }

            foreach( $variation['attributes'] as $var_attr_id => $var_attr_val ) {

                $parts = explode( 'pa_', $var_attr_id );
                if ( !isset( $parts[1] ) ) {
                    continue;
                }

                // if ( !array_key_exists( $parts[1], $mapped_attributes ) ) {
                //     continue;
                // }

                $variation_data[$parts[1]] = array();

                if ( strlen( $var_attr_val ) > 0 ) {

                    $term = get_term_by( 'slug', $var_attr_val, wc_attribute_taxonomy_name( $parts[1] ) );
                    $variation_data[$parts[1]][] = $term->name;
                }
                else {

                    foreach( $attributes as $attr_id => $attribute ) {

                        $attr_parts = explode( 'pa_', $attr_id );
                        if ( !isset( $attr_parts[1] ) || $parts[1] !== $attr_parts[1] ) {
                            continue;
                        }

                        foreach( $attribute as $value ) {

                            $term = get_term_by( 'slug', $value, wc_attribute_taxonomy_name( $attr_parts[1] ) );
                            $variation_data[$parts[1]][] = $term->name;
                        }
                    }
                }
            }

            if ( sizeof( $variation_data ) > 0 ) {

                $results = $this->array_cartesian( $variation_data );
                foreach( $results as $result ) {

                    $serialized = serialize( $result );
                    $md5 = md5( $serialized );

                    if ( !isset( $prod_variations[$md5] ) ) {

                        $prod_variations[$md5] = array(
                            'id' => $variation['variation_id'],
                            'attributes' => $result
                        );
                    }
                }
            }
        }

        return $prod_variations;
    }

    /**
     * Method is copied from original WooCommerce function
     * @param array $input
     * @return array
     */
    protected function array_cartesian( array $input ) {

        $result = array();

        while ( list( $key, $values ) = each( $input ) ) {
            // If a sub-array is empty, it doesn't affect the cartesian product
            if ( empty( $values ) ) {
                continue;
            }

            // Special case: seeding the product array with the values from the first sub-array
            if ( empty( $result ) ) {
                foreach ( $values as $value ) {
                    $result[] = array( $key => $value );
                }
            }
            else {
                // Second and subsequent input sub-arrays work like this:
                //   1. In each existing array inside $product, add an item with
                //      key == $key and value == first item in input sub-array
                //   2. Then, for each remaining item in current input sub-array,
                //      add a copy of each existing array inside $product with
                //      key == $key and value == first item in current input sub-array

                // Store all items to be added to $product here; adding them on the spot
                // inside the foreach will result in an infinite loop
                $append = array();
                foreach( $result as &$product ) {
                    // Do step 1 above. array_shift is not the most efficient, but it
                    // allows us to iterate over the rest of the items with a simple
                    // foreach, making the code short and familiar.
                    $product[ $key ] = array_shift( $values );

                    // $product is by reference (that's why the key we added above
                    // will appear in the end result), so make a copy of it here
                    $copy = $product;

                    // Do step 2 above.
                    foreach( $values as $item ) {
                        $copy[ $key ] = $item;
                        $append[] = $copy;
                    }

                    // Undo the side effects of array_shift
                    array_unshift( $values, $product[ $key ] );
                }

                // Out of the foreach, we can add to $results now
                $result = array_merge( $result, $append );
            }
        }

        return $result;
    }

    /**
     * Returns a list of pairs: wc_attr_id => site_attr_id
     * @param array $attributes
     * @return array
     */
    protected function get_mapped_attributes( $attributes ) {
        $mapped_attributes = array();

        // generate a list of mapped attributes
        foreach( $attributes as $attr_id => $attr_var ) {

            $map = $this->is_attribute_mapped( $attr_id );
            if( $map !== false ) {

                $parts = explode( 'pa_', $attr_id );
                if ( !isset( $parts[1] ) ) {
                    continue;
                }
                $mapped_attributes[$parts[1]] = $map;
            }
        }

        return $mapped_attributes;
    }
}
