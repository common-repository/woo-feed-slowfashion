<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * class contains a set of methods which can be used for performing actions
 * on slowfashion.pl API
 */
class Slowfashion_Feed extends Slowfashion_Site {
    /**
     * String identifier of the site
     * @var string
     */
    protected $site_name = 'slowfashion';

    /**
     * URL to the XML file containing a list of categories
     * @var string
     */
    protected $categories_url = 'templates/xml/slowfashion.xml';


    /**
     * A path to xml file which is a correct template for slowfashion.pl
     * @var string
     */
    private $xml_template = 'templates/xml/slowfashion.xml';

    /**
     * Filename to final XML document
     * @var string
     */
    protected $xml_output = 'slowfashion.xml';

    /**
     * Identifier of default property type
     */
    const DEFAULT_PROPERTY_TYPE = 'slowfashion_cat_other';

    /**
     * Prefix for all properties
     */
    const PROPERTY_PREFIX = 'slowfashion_cat_';

    /**
     * List of remote categories
     * @var array
     */
    protected $categories_list = array();
    
    /**
     * Contains the properties listed in slowfashion documentation
     * @var array
     */
    protected $properties = array(
        self::DEFAULT_PROPERTY_TYPE => array(
            'Producent' => 'slowfashion_cat_manufacturer',
            'Kod_producenta' => 'slowfashion_cat_man_code',
            'EAN' => 'slowfashion_cat_ean',
            'Append_to_name' => 'slowfashion_cat_name_append'
        ),
        'slowfashion_cat_books' => array(
            'Autor' => 'slowfashion_cat_author',
            'ISBN' => 'slowfashion_cat_isbn',
            'Ilosc_stron' => 'slowfashion_cat_pages',
            'Wydawnictwo' => 'slowfashion_cat_publishing',
            'Rok_wydania' => 'slowfashion_cat_pub_date',
            'Oprawa' => 'slowfashion_cat_lum',
            'Format' => 'slowfashion_cat_book_format',
            'Spis_tresci' => 'slowfashion_cat_book_url',
            'Fragment' => 'slowfashion_cat_book_portion'
        ),
        'slowfashion_cat_ebooks' => array(
            'Autor' => 'slowfashion_cat_author',
            'ISBN' => 'slowfashion_cat_isbn',
            'Ilosc_stron' => 'slowfashion_cat_pages',
            'Wydawnictwo' => 'slowfashion_cat_publishing',
            'Rok_wydania' => 'slowfashion_cat_pub_date',
            'Format' => 'slowfashion_cat_ebook_format',
            'Spis_tresci' => 'slowfashion_cat_book_url',
            'Fragment' => 'slowfashion_cat_book_portion'
        ),
        'slowfashion_cat_audiobooks' => array(
            'Autor' => 'slowfashion_cat_author',
            'ISBN' => 'slowfashion_cat_isbn',
            'Ilosc_stron' => 'slowfashion_cat_pages',
            'Wydawnictwo' => 'slowfashion_cat_publishing',
            'Rok_wydania' => 'slowfashion_cat_pub_date',
            'Format' => 'slowfashion_cat_audiobook_format',
            'Spis_tresci' => 'slowfashion_cat_book_url',
            'Fragment' => 'slowfashion_cat_book_portion'
        ),
        'slowfashion_cat_grocery' => array(
            'Producent' => 'slowfashion_cat_manufacturer',
            'EAN' => 'slowfashion_cat_ean',
            'Ilosc' => 'slowfashion_cat_quantity'
        ),
        'slowfashion_cat_tires' => array(
            'Producent' => 'slowfashion_cat_manufacturer',
            'SAP' => 'slowfashion_cat_manufacturer_code',
            'EAN' => 'slowfashion_cat_ean',
            'Model' => 'slowfashion_cat_tire_type',
            'Szerokosc_opony' => 'slowfashion_cat_tire_width',
            'Profil' => 'slowfashion_cat_aspect_ratio',
            'Srednica_kola' => 'slowfashion_cat_rim_size',
            'Indeks_predkosc' => 'slowfashion_cat_speed_rating',
            'Indeks_nosnosc' => 'slowfashion_cat_load_index',
            'Sezon' => 'slowfashion_cat_season'
        ),
        'slowfashion_cat_rims' => array(
            'Producent' => 'slowfashion_cat_manufacturer',
            'Kod_producenta' => 'slowfashion_cat_man_code',
            'EAN' => 'slowfashion_cat_ean',
            'Rozmiar' => 'slowfashion_cat_size_of_rim',
            'Rozstaw_srub' => 'slowfashion_cat_rim_spec',
            'Odsadzenie' => 'slowfashion_cat_et'
        ),
        'slowfashion_cat_perfumes' => array(
            'Producent' => 'slowfashion_cat_manufacturer',
            'Kod_producenta' => 'slowfashion_cat_man_code',
            'EAN' => 'slowfashion_cat_ean',
            'Linia' => 'slowfashion_cat_line',
            'Rodzaj' => 'slowfashion_cat_perfume_type',
            'Pojemnosc' => 'slowfashion_cat_capacity'
        ),
        'slowfashion_cat_music' => array(
            'Wykonawca' => 'slowfashion_cat_artist',
            'EAN' => 'slowfashion_cat_ean',
            'Nosnik' => 'slowfashion_cat_media',
            'Wytwornia' => 'slowfashion_cat_record_label',
            'Gatunek' => 'slowfashion_cat_genre'
        ),
        'slowfashion_cat_games' => array(
            'Producent' => 'slowfashion_cat_manufacturer',
            'Kod_producenta' => 'slowfashion_cat_man_code',
            'EAN' => 'slowfashion_cat_ean',
            'Platforma' => 'slowfashion_cat_platform',
            'Gatunek' => 'slowfashion_cat_genre'
        ),
        'slowfashion_cat_movies' => array(
            'Rezyser' => 'slowfashion_cat_director',
            'EAN' => 'slowfashion_cat_ean',
            'Nosnik' => 'slowfashion_cat_media',
            'Wytwornia' => 'slowfashion_cat_film_company',
            'Obsada' => 'slowfashion_cat_actors',
            'Tytul_oryginalny' => 'slowfashion_cat_original_title'
        ),
        'slowfashion_cat_medicines' => array(
            'Producent' => 'slowfashion_cat_manufacturer',
            'BLOZ_12' => 'slowfashion_cat_bloz12',
            'BLOZ_7' => 'slowfashion_cat_bloz7',
            'Ilosc' => 'slowfashion_cat_med_qty'
        ),
        'slowfashion_cat_clothes' => array(
            'Producent' => 'slowfashion_cat_manufacturer',
            'Model' => 'slowfashion_cat_cloth_type',
            'EAN' => 'slowfashion_cat_ean',
            'Kolor' => 'slowfashion_cat_color',
            'Rozmiar' => 'slowfashion_cat_cloth_size',
            'Kod_produktu' => 'slowfashion_cat_man_code',
            'Sezon' => 'slowfashion_cat_cloth_season',
            'Fason' => 'slowfashion_cat_fason',
            'ProductSetId' => 'slowfashion_cat_set_id'
        ),
    );
    
    /**
     * Properties for variations
     * @var array
     */
    public static $variation_properties = array(
        'EAN' => 'slowfashion_cat_ean'
    );
    
    private $avail; // To be written in xml
    private $attributes_exclusion = array();
    private $attributes_mapping = array();

    /**
     * In this method we generate the xml document
     */
    public function generate_xml() {
        
        // Common attribute for all attributes
        $avail = get_option(Slowfashion_Common::PLUGIN_IDENTIFIER);
        $avail = $avail['attribute_slowfashion_shipping_class'];
    	switch($avail){
    		case $this->get_translation('one_day'): $avail=1; break;
    		case $this->get_translation('three_days'): $avail=3; break;
    		case $this->get_translation('seven_days'): $avail=7; break;
    		case $this->get_translation('more_days'): $avail=14; break;
    		default: $avail=99;
    	}
    	$this->avail = $avail;
    	
    	// Attribute mapping
    	$attributes_settings = get_option('slowfashion_attributes_settings', array());
    	foreach($attributes_settings as $id => $options) {
    	    $name = wc_attribute_taxonomy_name_by_id($id);
    	    $this->attributes_exclusion[$name] = $options['exclusion'];
    	    $this->attributes_mapping[$name] = $options['mapping'];
    	}
    	

        $xml = simplexml_load_file( $this->plugin_dir . $this->xml_template );
        $products = $this->get_all_products();
        foreach($products as $product) {
            if ( $product instanceof WC_Product_Variable ) {
                $treat_as_single =  get_post_meta($product->get_id(), 'slowfashion_treat_as_single');
                // if( $treat_as_single ) {
                //     wp_die(print_r($treat_as_single,1));
                // }

                if( $treat_as_single && $treat_as_single[0] == 'yes') {
                    $this->process_product( $product, $xml );
                } else {
                    $this->process_variable( $product, $xml );
                }
            }
            else {
                $this->process_product( $product, $xml );
            }
        }

        $this->create_final_xml( $xml );
    }
    
    private function attribute_name($slug) {
        if( empty($this->attributes_mapping[$slug]) )
            $this->attributes_mapping[$slug] = wc_attribute_label($slug);
            
        return $this->attributes_mapping[$slug];
    }
    
    private function include_attribute($slug) {
        return !isset($this->attributes_exclusion[$slug]) || !$this->attributes_exclusion[$slug];
    }

    /**
     * Returns the identifier of slowfashion category name
     * @return string
     */
    protected function get_category_identifier() {

        return Slowfashion_Common::CENEO_CATEGORIES;
    }

    /**
     * Returns an identifier of WP option containing categories list for slowfashion
     * @return string
     */
    protected function get_categories_list_identifier() {

        return Slowfashion_Common::SETTING_CENEO_UPDATE_CATEGORIES;
    }

    /**
     * Returns the identifier of exclusion flag for slowfashion sitre
     * @return string
     */
    protected function get_exclusion_identifier() {

        return Slowfashion_Common::CENEO_EXCLUSION;
    }

    /**
     * Method returns a key => value array containing translated property types
     * @return array
     */
    public function get_property_types() {

        $types = array();
        foreach( $this->properties as $id => $properties ) {
            $types[$id] = $id;
        }

        return $this->translate_attributes( $types );
    }

    /**
     * Returns a list on remote categories.
     * @return array
     */
    public function get_remote_categories($file_name = NULL) {
        $file_dir = ($file_name !== NULL) ? $file_name : $this->get_categories_url();

        $xml = simplexml_load_file( $file_dir );
        $categories = array();

        if ( $xml instanceof SimpleXMLElement ) {

            $categories = $this->processSubcategory( $xml );
        }

        return $categories;
    }

    /**
     * Recursive method. Process all subcategories. Returns final categories list.
     * @param \SimpleXMLElement $subcategory
     * @param string $name
     * @param array $categories
     * @return array
     */
    private function processSubcategory( $subcategory, &$name = '', &$categories = array() ) {

        foreach( $subcategory->children() as $category ) {

            $new_name = '';
            if ( strlen( $name ) > 0 ) {
                $new_name .= $name . '/';
            }
            $new_name .= $category->Name;

            if( count( $category->Subcategories->children() ) > 0 ) {
                $this->processSubcategory( $category->Subcategories, $new_name, $categories );
            }
            else {
                $categories[(int)$category->Id] = $new_name;
            }
        }

        return $categories;
    }

    /**
     * Method adds the offer into group.
     *
     * @param \WC_Product $product
     * @param \SimpleXmlElement $group_node - this is an object, so passed by reference
     * @param string $name_suffix - used for variations
     * @param array $mapped_values - used for variations, contains a pairs: slowfashion_attr_id => wc_attr_value
     */
    private function process_product( WC_Product $product, SimpleXMLElement $xml, WC_Product_Variable $parent = null ) {

        // Only product that can be purchased can be added to xml
        if( !$product->is_purchasable() || ($product->managing_stock() && $product->get_stock_quantity() <= 0) ) return;

        $offer = $xml->addChild( 'o' );
        
        // Id
        $offer->addAttribute( 'id', $product->get_id() );
        
        // Url
        $offer->addAttribute( 'url', $product->get_permalink() );
        
        // Price
        $offer->addAttribute( 'price', $product->get_price() );
        
        // Availability
        $offer->addAttribute( 'avail', $this->avail );
        
        // Stock
        $stock = $product->get_stock_quantity();
        if( $stock && $stock > 0 ) $offer->addAttribute( 'stock', $stock );
        
        // Weight
        if ( $product->has_weight() ) {
        	$offer->addAttribute( 'weight', $product->get_weight() );
        }

        // Basket
        $main_id = $product instanceof WC_Product_Variation ? $parent->get_id() : $product->get_id();
        if( get_post_meta($main_id, 'slowfashion_basket', true) ) {
            $offer->addAttribute('basket', 1);
        } else {
            $offer->addAttribute('basket', 0);
        }
        
        // Category
        $offer->cat = null;
        $this->addCData( $this->get_product_cat( $main_id ), $offer->cat );
        
        // Name
        $offer->name=null;
        $name =  $product->get_name();
        
        if( $parent ) {
            $name .= '-' . $product->get_id();
        }
    	$this->addCData( $product->get_name(), $offer->name );
    	
    	// Images
    	$imgs = $offer->addChild('imgs');
    	$main_image = $product->get_image_id();
    	if($main_image) {
    	    $main_image_node = $imgs->addChild('main');
    	    $main_image_node->addAttribute('url', wp_get_attachment_url($main_image) );
    	}
    	$gallery = $product->get_gallery_image_ids();
    	if( $parent ) {
    	    $gallery = array_merge( $gallery, $parent->get_gallery_image_ids() );
    	}
    	foreach($gallery as $img) {
    	    if($img == $main_image) continue;
    	    
    	    $i_node = $imgs->addChild('i');
    	    $i_node->addAttribute('url', wp_get_attachment_url($img));
    	}
    	
    	// Description
    	$description = $this->get_description($product);
    	if( !$description ) {
    	    $description = get_post_meta( $main_id, 'slowfashion_alternative_description', true );
    	    if( !$description && $parent ) {
    	        $description = $this->get_description($parent);
    	    }
    	}
    	$desc = $offer->addChild('desc');
        $this->addCData( $description, $desc);
        
        // Attributes
        $attrs = $offer->addChild('attrs');
        $properties = $this->get_product_properties($product, $parent);
        foreach( $properties as $name => $value ) {
            $a = $attrs->addChild('a');
            $a->addAttribute('name', $name);
            $this->addCData($value, $a);
        }
    }
    
    private function attribute_options_to_string(WC_Product_Attribute $attribute) {
        if( $attribute->is_taxonomy() ) {
            $mapped = array();
            foreach($attribute->get_options() as $id) {
                $mapped[] = get_term_by('id', $id, $attribute->get_name())->name;
            }
            return implode(";", $mapped);
        } else {
            return implode(";", $attribute->get_options() );
        }
    }
    
    private function get_product_properties( $product, $parent = null ) {
        $props = array();
        
        // SKU mapping - may be overriden in the future
        if( ($sku = $product->get_sku()) && ($sku_mapping = Slowfashion_Common::get_setting_option("{$this->site_name}_SKU_mapping", '')) ) {
            $props[$sku_mapping] = $sku;
        }
        
        // Manually filled properties (backward compatibility)
        $main_id = $product instanceof WC_Product_Variation ? $product->get_parent_id() : $product->get_id();
        $properties = get_post_meta( $main_id, 'slowfashion_properties', true );
        if( is_array($properties) ) {
            foreach($properties as $fields) {
                if( is_array($fields) ) {
                    foreach($fields as $key => $value) {
                        if($value) $props[$key] = $value;
                    }
                }
            }
        }
        
        // Product attributes
        if( $parent ) {
            // Variable product
            $attributes = $parent->get_attributes();
            foreach( $attributes as $attribute ) {
                if( !$this->include_attribute($attribute->get_name()) ) continue;
                
                $label = $this->attribute_name($attribute->get_name());
                if( $attribute->get_variation() ) {
                    // Attribute for variations
                    $val = $product->get_attribute($attribute->get_name());
                } else {
                    // Simple attribute
                    $val = $this->attribute_options_to_string($attribute);
                }
                $props[$label] = $val;
            }
        } else {
            $attributes = $product->get_attributes();
            // Simple product
            foreach( $attributes as $attribute ) {
                if( !$this->include_attribute($attribute->get_name()) ) continue;
                
                $label = $this->attribute_name($attribute->get_name());
                $val = $this->attribute_options_to_string($attribute);
                $props[$label] = $val;
            }
        }
        
        // EAN for variation
        if( $product instanceof WC_Product_Variation ) {
            $slowfashion_properties = (array) get_post_meta($product->get_id(), 'slowfashion_properties', true);
            foreach( self::$variation_properties as $name => $key ) {
                if( !empty($slowfashion_properties[$key]) ) {
                    // Now we have to replace all the attributes from parent product
                    foreach($properties as $category => &$attributes) {
                        $props[$name] = $slowfashion_properties[$key];
                    }
                }
            }
        }
        
        return $props;
    }


    /**
     * Processing the variation by adding each possible variable value as separate node
     *
     * @param \WC_Product_Variable $product
     * @param \SimpleXMLElement $group_node
     */
    private function process_variable( WC_Product_Variable $product, SimpleXMLElement $xml ) {
        $prod_variations = $product->get_children();


        foreach( $prod_variations as $variation_id ) {
            $variation = wc_get_product($variation_id);
            $variation_attrs = $variation->get_variation_attributes();
            foreach( $variation_attrs as $key => $val ) {
                if( !$val ) continue 2; // Only fully filled variations
            }
            
            $this->process_product( $variation, $xml, $product );
        }
    }

    private function get_unique_attributes( WC_Product $product ){
        $attributes = $product->get_attributes();

        $u_attrs = array();
        foreach($attributes as $key => $attr){
            if(!isset($attr["is_variation"]) || !$attr["is_variation"]){
                $u_attrs[$key] = $attr;
            }
        }
        $mapped_attributes = $this->get_mapped_attributes( $u_attrs );

        return $mapped_attributes;
    }

}
