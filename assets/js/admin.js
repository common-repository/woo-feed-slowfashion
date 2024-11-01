/**
 * JS scripts processed in admin panel
 */

( function( $ ){
    $( document ).ready( function(){

        $( '#woocommerce_xml_integration_slowfashion' ).on( 'change', '#ceneo_property_types', function(){

            var select_option = $( this ).val();
            if ( select_option === 'none' ) {
                $( '.pcsi-section' ).hide();
            }
            else {
                var current_section = $( '#' + select_option );
                current_section.siblings( '.pcsi-section' ).hide();
                current_section.show( 200 );
            }
        });

        $( '#ceneo_property_types' ).change();

        if($.select2) {
            $("#tag-slowfashion_categories").select2();
        }
    });
})( jQuery );
