<?php
/**
 * Renders a double or single dimensional select list
 * @param string $plugin_identifier
 * @param array $options
 * @param string $action
 * @param array $groups_translation
 * @param array $default
 * @param string|int $selected
 */
?>

<select name="<?php print $plugin_identifier; ?>[<?php print $action; ?>]">

    <option value="<?php print $default['value'] ?>"><?php print $default['label'] ?></option>

    <?php if ( is_array( current( $options ) ) ) : ?>
        <?php foreach( $options as $group_id => $group ): ?>
            <optgroup label="<?php print $groups_translation[$group_id] ?>">

                <?php foreach( $group as $opt_id => $option ): ?>
                <option value="<?php print $group_id ?>_<?php print $opt_id; ?>" 
                        <?php selected( $selected, $group_id . '_' . $opt_id ); ?>>
                        <?php print $opt_id; ?>
                    </option>
                <?php endforeach; ?>

            </optgroup>
        <?php endforeach; ?>
            
    <?php else: ?>
        <?php foreach( $options as $opt_id => $option ): ?>
            <option value="<?php print $opt_id; ?>" <?php selected( $selected, $opt_id ); ?>>
                <?php print $opt_id; ?>
            </option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>