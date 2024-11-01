<?php
/**
 * Template displays a write panel options which are available under "External source" tab
 * @param string $tab_id
 * @param array groups
 */
?>

<div id="<?php print $tab_id; ?>" class="panel woocommerce_options_panel">
    <?php foreach( $groups as $group ): ?>
            <?php 
			if($group[0]['label']=='Twoja Giełda ustawienia'){
			foreach( $group as $item ): ?>
                <?php  
				
 switch( $item['type'] ) {

         // just a header of current group
                    case 'header': 
					?>
                    
                        <h3 style="margin-left: 10px;"><?php print $item['label']; ?></h3><?php
                        break;

                    // render regular checkbox
                    case 'checkbox':
                        woocommerce_wp_checkbox( $item );
                        break;

                    // render regular select list
                    case 'select':
                        woocommerce_wp_select( $item );
                        break;
                        
                    // render textarea
                    case 'textarea':
                        woocommerce_wp_textarea_input( $item );
                        break;

					                        
                    case 'info':
                        echo '<p>';
                        echo $item['content'];
                        echo '</p>';
                        break;
                } 
				?>
            <?php endforeach; ?>
   <?php }
	endforeach; ?>
        </div>
