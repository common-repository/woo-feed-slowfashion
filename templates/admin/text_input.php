<?php
/**
 * Renders a text input
 * @param string $plugin_identifier
 * @param string $action
 */
?>
<input value="<?php  echo esc_attr( get_option($action) ); ?>" name="<?php print $plugin_identifier; ?>[<?php print $action; ?>]" type="text">
