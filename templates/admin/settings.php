<?php
/**
 * Displaying the admin settings
 * @param string $credentials
 * @param string $header_label
 * @param string $save_label
 * @param string $plugin_identifier
 * @param array $files
 * @param array $table_labels
 */
?>

<div class="wrap">
    <div class="icon32 icon32-jigoshop-category-pages" id="icon-jigoshop"><br/></div>
    <h2><?php print $header_label; ?></h2>

    <?php settings_errors(); ?>
    <?php print $credentials; ?>

    <table class="wp-list-table widefat fixed pages">
        <thead>
            <tr>
                <th><?php print $table_labels['site_url']; ?></th>
                <th><?php print $table_labels['update_time']; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $files as $site_name => $file ) : ?>
                <tr>
                    <td><a href="<?php echo $file['url']; ?>"><?php echo $file['url']; ?></a></td>
                    <td><?php print $file['last_update']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <form action="options.php" method="post" enctype="multipart/form-data">
        <?php settings_fields( $plugin_identifier ); ?>
        <?php do_settings_sections( $plugin_identifier ); ?>
    </form>
</div>