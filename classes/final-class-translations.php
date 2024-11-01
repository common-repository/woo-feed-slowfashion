<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Slowfashion_Translations {

    private $translations = null;

    public function __construct() {

        $this->add( 'price_comparison', __( 'Woocommerce Feed Slowfashion', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_product_basket', __('Add to "Kup na Slowfashion"', 'woocommerce-feed-slowfashion'));
        $this->add( 'slowfashion_product_basket_description', __('Add this product to "Kup na Slowfashion"', 'woocommerce-feed-slowfashion'));
        $this->add( 'slowfashion_treat_as_single', __('Treat this variable product as simple product', 'woocommerce-feed-slowfashion'));
        $this->add( 'slowfashion_treat_as_single_description', __("Check this field if you don't want this product to be expanded to it's variations"));
	    $this->add( 'slowfashion_product_exclude', __( 'Slowfashion exclusion', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'settings_saved', __( 'Ustawienia zostały zapisane.', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'settings_category_failure', __( 'Failed to get remote categories, however other plugin settings are saved.', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'update_category_desc', __( 'Aktualizuj listę kategorii', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'update_button_text', __( 'Aktualizuj', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'use_wc_category', __( 'Użyj kategorii zastępczej dla WooCommerce', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'property_type', __( 'Property type', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'property_description', __( 'Choose the type of properties to display text inputs', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'pick_the_type', __( '- choose type -', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'map_attribute', __( 'Map attribute', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'no_mapping', __( 'No mapping', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'save_changes', __( 'Zapisz zmiany', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'file_not_generated', __( 'Nie wygenerowano jeszcze pliku', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'site_name', __( 'Nazwa', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'site_url', __( 'Adres url do feeda', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'update_time', __( 'Ostatnia aktualizacja', 'woocommerce-feed-slowfashion' ) );
    	$this->add( 'update_label', __( 'Aktualizuj Feed', 'woocommerce-feed-slowfashion' ) );
    	$this->add( 'update_xml', __( 'Wykonaj', 'woocommerce-feed-slowfashion' ) );
    	$this->add( 'xml_update_success_slowfashion', __( 'Slowfashion.pl XML file successfully updated.', 'woocommerce-feed-slowfashion' ) );
    	$this->add( 'xml_update_fail', __( 'Error occurred while updating XML file.', 'woocommerce-feed-slowfashion' ) );
    	$this->add( 'xml_update_unknown', __( 'Unknown XML update status.', 'woocommerce-feed-slowfashion' ) );
    	$this->add('sku_mapping', __('SKU mapping') );

        // translations for slowfashion properties
        $this->add( 'slowfashion_cat_books', __( 'Books', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_author', __( 'Author full name', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_isbn', __( 'Book ISBN code', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_pages', __( 'Number of pages', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_publishing', __( 'Publishing house', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_pub_date', __( 'Publishing date', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_lum', __( 'Type of luminaire eg, soft, hard', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_book_format', __( 'Book fromat ie. B5, A5, 172x245cm, 15.5x22.5cm', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_book_url', __( 'URL to Table of Contents', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_book_portion', __( 'Link to a portion of the book', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_ebooks', __( 'E-books', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_ebook_format', __( 'E-book format ie. PDF, ePub, AZW, MOBI ', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_audiobooks', __( 'Audiobooks', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_audiobook_format', __( 'Audiobook format ie. MP3, WMV, Płyta CD', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_grocery', __( 'Grocery', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_manufacturer', __( 'The manufacturer of the product', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_ean', __( 'The barcode appearing on the products, packaging', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_quantity', __( 'Quantity in package example. 12szt., 2kg', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_tires', __( 'Tires', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_manufacturer_code', __( 'Manufacturer code', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_tire_type', __( 'Tire type', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_tire_width', __( 'The width of the tire in millimeters', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_aspect_ratio', __( 'Aspect ratio', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_rim_size', __( 'Rim size', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_speed_rating', __( 'Speed rating', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_load_index', __( 'Load index', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_season', __( 'Seasonality ie. Zimowe, Letnie, Całoroczne', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_rims', __( 'Rims', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_size_of_rim', __( 'The width and the outer diameter in inches for example, 6.5 x15', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_rim_spec', __( 'Number and diameter of the mounting screws of the circle on which there are openings e.g. 5x110', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_et', __( 'The distance between the mounting plane of the rim, and the center of symmetry (ET)', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_perfumes', __( 'Perfumes', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_line', __( 'Line of smell - a series such as Miss Pucci, Orange Celebration of Happiness', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_perfume_type', __( 'Type of product ie. Woda perfumowana, Woda toaletowa, Woda kolońska, Dezodorant roll on, Dezodorant sztyft', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_capacity', __( 'Capacity given in mililiters ie. 50 ml, 100 ml', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_music', __( 'Music', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_artist', __( 'Artist', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_media', __( 'Media', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_record_label', __( 'Record label', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_genre', __( 'Genre', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_games', __( 'Games', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_platform', __( 'The platform on which the game is meant as PC, PS2, Xbox360', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_movies', __( 'Movies', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_director', __( 'Full name of movie director', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_film_company', __( 'Name the film company', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_actors', __( 'The actors playing in the film', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_original_title', __( 'The original title of the film', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_medicines', __( 'Medicines', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_bloz12', __( 'ID of the drug - it is necessary to provide a minimum of one of the codes for drugs and pharmaceutical products (it is recommended to enter both codes for each product)', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_bloz7', __( 'For articles that do not have the Bloz12 code, enter the Bloz7 code', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_med_qty', __( 'Number of tablets, such as bottle capacity 12szt., 250ml', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_clothes', __( 'Clothes', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_cloth_type', __( 'Type', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_color', __( 'Dominant Color. If the product is present in several color variants, there should be a separate offer for each color', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_cloth_size', __( 'Size. If the product is available in different sizes individual values​should be separated by a semicolon, eg "S;L;XL"', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_cloth_season', __( 'Season eg. "wiosna/lato"', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_fason', __( 'Understood as a fashion single value eg. rurki, dzwony, szerokie', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_set_id', __( 'Set identifier', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_other', __( 'Other', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_name_append', __( 'Append to name', 'woocommerce-feed-slowfashion' ) );
		$this->add( 'shipping_class', __( 'Shipping Class', 'woocommerce-feed-slowfashion' ) );
		$this->add( 'one_day', __( 'One day', 'woocommerce-feed-slowfashion' ) );
		$this->add( 'three_days', __( '3 days', 'woocommerce-feed-slowfashion' ) );
		$this->add( 'seven_days', __( '7 days', 'woocommerce-feed-slowfashion' ) );
		$this->add( 'more_days', __( 'More than week', 'woocommerce-feed-slowfashion' ) );

        // translations for slowfashion proporties
        $this->add( 'slowfashion_settings', __( 'Woocommerce Slowfashion Feed', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_description_exclude', __( 'Wyklucz tę kategorię z pliku XML przygotowanego dla Woocommerce Slowfashion Feed', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_categories_list', __( 'Woocommerce Slowfashion Feed kategorie', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_product_description_exclude', __( 'Wyklucz ten produkt z pliku XML przygotowanego dla Twojaslowfashion.com', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_list_desc', __( 'Zaleca się zamapowanie kategorii WooCommerce na kategorie Woocommerce Slowfashion Feed. W przypadku, gdy powyższa lista jest pusta, przejdź do ustawień "WooCommerce -> Twoja Giełda" i zaktualizuj listę.', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_producer', __( 'Producer', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_producter_desc', __( 'Skrócona nazwa producenta, tj. "Sony"', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_cat_man_code', __( 'Kod producenta', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_size_attribute', __( 'Atrybut rozmiaru - slug', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'slowfashion_color_attribute', __( 'Atrybut producenta - slug', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'settings_and_categories_saved_slowfashion', __( 'Zaktualizowano listę kategorii dla Woocommerce Slowfashion Feed. Ustawienia zostały zapisane.', 'woocommerce-feed-slowfashion' ) );
        $this->add( 'xml_update_success_Slowfashion_Feed', __( 'Lista produktów dla Slowfashion.pl została utworzona', 'woocommerce-feed-slowfashion' ) );

        // general options
        $this->add( 'alternative_description', __('Alternative description', 'woocommerce-slowfashion-nokau-integration') );
      }

    /**
     * Method adds the translation entry into an array
     * @param string $identifier
     * @param string $translation - this should be a value returned by __() function
     * @throws Exception
     */
    private function add( $identifier, $translation ) {

        if ( isset( $this->translations[$identifier] ) ) {
            throw new Exception( 'Translation identifier "' . $identifier . '" already exists' );
        }

        $this->translations[$identifier] = $translation;
    }

    /**
     * Makes it possible to get the translation from a main set using translation index/key
     * @param string $key
     * @return string
     * @throws Exception
     */
    public function get_translation( $key ) {

        if ( !isset( $this->translations[$key] ) ) {
            throw new Exception( 'There\'s no translation for given key: ' . $key );
        }

        return $this->translations[$key];
    }
}
