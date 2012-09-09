<?php
/* 
 * element settings
 * 
 */
if(!empty($Element['_ID'])){
    echo ce_configOption('ID', 'ID', 'hidden', 'element ID', $Element);
}
echo ce_configOption('name', 'name', 'textfield', 'Element Name', $Element);
echo ce_configOption('category', 'category', 'textfield', 'Category', $Element);
echo ce_configOption('shortcode', 'shortcode', 'textfield', 'Short Code', $Element);
echo ce_configOption('shortcodeType', 'shortcodeType', 'radio', 'Type|[shortcode],[shortcode]content[/shortcode]', $Element);



?>