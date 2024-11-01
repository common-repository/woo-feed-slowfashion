<?php
/**
 * Template renders a button with ajax actions
 * @param string $button_text
 * @param string $action
 * @param string $plugin_identifier
 */
?>

<input type="submit" class="button-primary" value="<?php print $button_text; ?>"
    name="<?php print $plugin_identifier; ?>[<?php print $action; ?>]" />

<?php if($manual_upload){ ?>
    <input type="checkbox" name="<?php print $plugin_identifier; ?>[<?php print $site; ?>_manual_upload]" value="true" id="chbx_<?php print $site; ?>"> Manual?<br>
    <div class="upload-wrapper" id="wrp_<?php print $site; ?>" style="display: none">
        <div class="file-upload button-primary">
            <span>Select file</span>
            <input type="file" name="<?php print $plugin_identifier; ?>[<?php print $site; ?>_file_upload]">
        </div>
        <div class="categories-url">
            <p>Download <a href="<?php print $categories_url; ?>">XML</a> file and upload it.</p>
        </div>
    </div>

<?php } ?>
