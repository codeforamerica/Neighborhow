<div class="frm_form_fields">
<?php
global $frm_star_loaded;
if(!is_array($frm_star_loaded))
    $frm_star_loaded = array();

$rand = FrmProAppHelper::get_rand(3);
$name = $field->id . $rand;
if(in_array($name, $frm_star_loaded)){
    $rand = FrmProAppHelper::get_rand(3);
    $name = $field->id . $rand;
}
$frm_star_loaded[] = $name;   

$field->options = maybe_unserialize($field->options);
$max = max($field->options);
$class = '';

if($stat != floor($stat)){
    if(!in_array('split', $frm_star_loaded))
        $frm_star_loaded[] = 'split';
    $factor = 4;
    $class = " {split:$factor}";
    $max = $max * $factor;
    $stat = round($stat * $factor);
}

for($i=1; $i<=$max; $i++){
    $checked = (round($stat) == $i) ? 'checked="checked"' : '';
    ?>
<input type="radio" name="item_meta[<?php echo $name ?>]" value="<?php echo isset($factor) ? ($i/$factor) : $i; ?>" <?php echo $checked ?> class="star<?php echo $class ?>" disabled="disabled" />
<?php } ?>
</div>