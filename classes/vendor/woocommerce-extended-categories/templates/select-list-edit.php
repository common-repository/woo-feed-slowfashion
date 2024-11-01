<?php
/**
 * Template renders an 'edit' view for the category select list setting
 * @param string $identifier
 * @param string $label
 * @param string $description
 * @param string $selected
 * @param array $first_option
 */
 if($identifier=='slowfashion_categories'){
?>
<script>

jQuery.fn.filterByText = function(textbox) {
  return this.each(function() {
    var select = this;
    var options = [];
    $(select).find($('.sear')).each(function() {
      options.push({
        value: $(this).val(),
        text: $(this).text()
      });
    });
    $(select).data('option2', options);

    $(textbox).bind('change keyup', function() {
      var options = $(select).empty().data('option2');
      var search = $.trim($(this).val());
      var regex = new RegExp(search, "gi");

      $.each(options, function(i) {
        var option = options[i];
        if (option.text.match(regex) !== null) {
          $(select).append(
            $('<option class="sear">').text(option.text).val(option.value)
          );
        }
      });
    });
  });
};

// You could use it like this:

$(function() {
  $('select').filterByText($('.filter'));
});

</script>

<tr class="form-field wc-extended-categories">
    <th><label for="tag-<?php print $identifier ?>"><?php print $label; ?></label></th>
    <td>
        <select name="<?php print $identifier; ?>" id="tag-<?php print $identifier; ?>" class="postform">
            <?php if( sizeof( $first_option ) > 0 ): ?>
                <option value="<?php print $first_option[0]; ?>"><?php print $first_option[1]; ?></option>
            <?php endif; ?>
            <?php foreach( $options as $value => $name ) : ?>
                <option class="sear" value="<?php print $value; ?>" <?php selected( $selected, $value ); ?>>
                    <?php print $name; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p>Dynamiczne szukanie: <input class="filter" style=" float:right; width:100%; max-width:400px" type="text"></p>
        <p class="description"><?php print $description; ?></p>
    </td>
</tr>
<?php
 }
 ?>