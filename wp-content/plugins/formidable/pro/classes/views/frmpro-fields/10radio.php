<?php 
if (is_array($field['options'])){
    if (!isset($field['value']))
        $field['value'] = maybe_unserialize($field['default_value']);
        
    foreach($field['options'] as $opt_key => $opt){
        $checked = ($field['value'] == $opt)?'checked="true"':'';
        $last =  (end($field['options']) == $opt) ? ' frm_last' : '';
        
        
        ?>
<div class="alignleft frm_10radio <?php echo $last ?>">
<input type="radio" name="<?php echo $field_name ?>" id="field_<?php echo $field['id']?>-<?php echo $opt_key ?>" value="<?php echo esc_attr($opt) ?>" <?php echo $checked ?> <?php do_action('frm_field_input_html', $field) ?> />
<?php if(!isset($field['star']) or !$field['star']){ ?><br/><label for="field_<?php echo $field['id']?>-<?php echo $opt_key ?>"><?php echo $opt ?></label>
<?php } ?>
</div>   
<?php 
} 
} ?>   
<div style="clear:both;"></div>