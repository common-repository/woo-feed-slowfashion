<?php

add_action('woocommerce_after_add_attribute_fields', function() {
   ?>
   <div class="form-field">
	    <label for="slowfashion_attribute_exclusion">
	        <input type="checkbox" name="slowfashion_attribute_exclusion">
	        <?php _e( 'Ceneo exclusion', 'woocommerce-feed-slowfashion' ); ?>
	    </label>
		<p class="description">
		    <?php _e( "Select this checkbox if you don't want this attribute to be added to XML", 'woocommerce-feed-slowfashion' ); ?>
		</p>
	</div>
	<div class="form-field">
	    <label for="slowfashion_name_mapping"><?php _e( 'Ceneo name mapping', 'woocommerce-feed-slowfashion' ); ?></label>
		<input type="text" name="slowfashion_name_mapping">
		<p class="description">
		    <?php _e( "Fill if you want to change the name visible in XML. Leave empty if you don't want to change it.", 'woocommerce-feed-slowfashion' ); ?>
		</p>
	</div>
	<?php
});

add_action('woocommerce_after_edit_attribute_fields', function() {
    $editing = $_GET['edit'];
    $options = get_option('slowfashion_attributes_settings', array());
    
    
    $exclusion = isset($options[$editing]['exclusion']) ? $options[$editing]['exclusion'] : false;
    $checked = $exclusion ? 'checked' : '';
    
    $mapping =  isset($options[$editing]['mapping']) ? $options[$editing]['mapping'] : '';
    ?>
    <tr class="form-field">
		<th scope="row" valign="top">
		    <label for="slowfashion_attribute_exclusion"><?php _e( 'Ceneo exclusion', 'woocommerce-feed-slowfashion' ); ?></label>
		</th>
		<td>
	        <input type="checkbox" <?= $checked ?> name="slowfashion_attribute_exclusion">
    		<p class="description">
    		    <?php _e( "Select this checkbox if you don't want this attribute to be added to XML", 'woocommerce-feed-slowfashion' ); ?>
    		</p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top">
		    <label for="slowfashion_name_mapping"><?php _e( 'Ceneo name mapping', 'woocommerce-feed-slowfashion' ); ?></label>
		</th>
		<td>
		    <input type="text" value="<?= $mapping ?>" name="slowfashion_name_mapping">
    		<p class="description">
		        <?php _e( "Fill if you want to change the name visible in XML. Leave empty if you don't want to change it.", 'woocommerce-feed-slowfashion' ); ?>
    		</p>
		</td>
	</tr>
    <?php
});

add_action('woocommerce_attribute_added', 'Slowfashion_save_attribute_settings');
add_action('woocommerce_attribute_updated', 'Slowfashion_save_attribute_settings');

function Slowfashion_save_attribute_settings($id) {
    if( !isset($_POST['slowfashion_name_mapping']) ) return;
    
    $mapping = sanitize_text_field($_POST['slowfashion_name_mapping']);
    $exclusion = isset($_POST['slowfashion_attribute_exclusion']);
    
    $options = get_option('slowfashion_attributes_settings', array());
    
    $options[$id] = array(
        'mapping' => $mapping,
        'exclusion' => $exclusion
    );
    
    update_option('slowfashion_attributes_settings', $options);
}