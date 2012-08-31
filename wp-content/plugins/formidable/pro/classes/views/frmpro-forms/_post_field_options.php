<?php
if(!empty($values['fields'])){
foreach($values['fields'] as $fo_key => $fo){
    if((isset($post_field) and !in_array($fo['type'], $post_field)) or in_array($fo['type'], array('divider', 'break', 'html', 'captcha'))) continue;

    if($fo['post_field'] == $post_key)
        $values[$post_key] = $fo['id'];
    ?>
    <option value="<?php echo $fo['id'] ?>" <?php selected($values[$post_key], $fo['id']) ?>><?php echo FrmAppHelper::truncate($fo['name'], 80);
    unset($fo); 
    ?></option>
    <?php 
    }
}