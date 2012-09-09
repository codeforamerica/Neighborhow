<?php

/*
 * My Shortcodes function library
 * (C) 2012 - David Cramer
 */

$Element = false;
$footerOutput = '';
$headerscripts = '';
$javascript = array();

function ce_process() {
    global $footerOutput, $headerscripts, $javascript;

    if(!empty($_FILES['import'])){
        if(!wp_verify_nonce($_POST['_wpnonce'],'cs-import-shortcode')){
            return;
        }
        $preData = file_get_contents($_FILES['import']['tmp_name']);
        unlink($_FILES['import']['tmp_name']);
        $Data = unserialize(base64_decode($preData));
        ce_saveElement(stripslashes_deep($Data));
        wp_safe_redirect('?page=my_shortcode');
        exit;

    }

    if (!empty($_GET['export'])) {
        ce_exportElement($_GET['export']);
        exit();
    }

    if (!empty($_POST['data'])) {
        if(!wp_verify_nonce($_POST['_wpnonce'],'cs-edit-shortcode')){
            return;
        }
        ce_saveElement(stripslashes_deep($_POST['data']));
        wp_safe_redirect('?page=my_shortcode');
        exit;
    }
    if(is_admin ()){
        return;
    }
    $url = url_to_postid($_SERVER['REQUEST_URI']);
    if(!empty($url)){
        $post = get_post($url);
    }else{
        $front = get_option('page_on_front');
        if(!empty($front)){
            $post = get_post($front);
        }
    }
    if(!empty($post)) {
        preg_match_all('/' . get_shortcode_regex() . '/s', $post->post_content, $used);
        $elements = get_option('CE_ELEMENTS');
        if(empty($elements)){
            return;
        }
        foreach ($elements as $element => $options) {
            if(!empty($options['shortcode'])){
                if (in_array($options['shortcode'], $used[2])) {
                    $shortcodes[] = $element;
                }
            }
        }
    }
    if(empty($elements)){
        $elements = get_option('CE_ELEMENTS');
        if(empty($elements)){
            return;
        }
    }
    /* Scan for active text widgets with shortcodes */
    $texts = get_option('widget_text');
    $sidebars = get_option('sidebars_widgets');
    unset($sidebars['wp_inactive_widgets']);
    foreach($sidebars as $sidebar=>$set){
        if(is_active_sidebar($sidebar)){
            foreach($set as $widget){
                if(substr($widget,0,5) == 'text-'){
                    $id = substr($widget,5);
                    if(!empty($texts[$id])){
                        preg_match_all('/' . get_shortcode_regex() . '/s', $texts[$id]['text'], $used);
                        foreach ($elements as $element => $options) {
                            if(!empty($options['shortcode'])){
                                if (in_array($options['shortcode'], $used[2])) {
                                    $shortcodes[] = $element;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    /* end widget scan */
    
    if(empty($shortcodes)){
        return;
    }
    foreach ($shortcodes as $ID) {
        $Element = get_option($ID);
        if (!empty($Element['_jsLib'])) {
            foreach ($Element['_jsLib'] as $handle => $src) {
                $in_footer = false;
                if ($Element['_jsLibLoc'][$handle] == 2) {
                    $in_footer = true;
                }
                wp_register_script($handle, $src, false, false, $in_footer);
                wp_enqueue_script($handle);
            }
        }
        if (!empty($Element['_cssLib'])) {
            foreach ($Element['_cssLib'] as $handle => $src) {
                wp_enqueue_style($handle, $src);
            }
        }

        if (!empty($Element['_cssCode'])) {

            if (!empty($Element['_variable'])) {
                foreach ($Element['_variable'] as $VarKey => $Variable) {
                    $VarVal = $Element['_variableDefault'][$VarKey];
                    if (!empty($atts[$Variable])) {
                        $VarVal = $atts[$Variable];
                    }
                    if (!empty($atts[$Variable . '_1'])) {
                        $startcounter = true;
                        $index = 1;
                        while ($startcounter == true) {
                            if (!empty($atts[$Variable . '_' . $index])) {
                                $varArray[trim($Variable)][] = $atts[$Variable . '_' . $index];
                            } else {
                                $startcounter = false;
                            }
                            $index++;
                        }
                    }


                    $Element['_cssCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $Element['_cssCode']);
                }
            }

            ob_start();
                eval(' ?>' . $Element['_cssCode'] . ' <?php ');
            $Css = ob_get_clean();
            $headerscripts .= "
            " . $Css . "
            ";
        }

        if (!empty($Element['_phpCode'])) {
            eval($Element['_phpCode']);
        }

    }




    foreach($texts as $text){
        //$widget_active = get_option('sidebars_widgets');
    }
}

function ce_button() {

    echo "<a onclick=\"return false;\" id=\"my-shortcodes\" title=\"My Shortcodes Builder\" class=\"thickbox\" href=\"".ELEMENTS_URL."shortcode.php?TB_iframe=1&width=640&height=307\">\n";
    echo "<img src=\"".ELEMENTS_URL."images/icon.png\" alt=\"Insert Shortcode\" width=\"15px\" height=\"15px\" />\n";
    echo "</a>\n";
}


function ce_start() {
    if(!is_admin()){
        ce_styles();
        ce_scripts();
    }
}

function ce_header() {
    global $headerscripts;

    if(!empty($headerscripts)){
        echo "<style>\n";
            echo $headerscripts;
        echo "</style>\n";
        $headerscripts = '';
    }
}
function ce_footer() {
    global $footerOutput;
    if(!empty($footerOutput)){
        echo "<script>\n";
        echo "jQuery(document).ready(function($) {\n";
        echo $footerOutput;
        echo "});\n";
        echo "</script>\n";
        $footerOutput = '';
    }
}

function ce_menus() {

    add_menu_page("Shortcode Builder", "My Shortcodes", 'activate_plugins', "my_shortcode", "ce_adminPage", ELEMENTS_URL."images/icon.png");
    $adminPage = add_submenu_page("my_shortcode", 'My Shortcodes', 'Shortcode Builder', 'activate_plugins', "my_shortcode", 'ce_adminPage', ELEMENTS_URL."images/icon.png");

    add_action('admin_print_styles-' . $adminPage, 'ce_styles');
    add_action('admin_print_scripts-' . $adminPage, 'ce_scripts');
}

function ce_styles() {

    wp_register_style('ce_adminStyle', ELEMENTS_URL . 'styles/core.css');
    wp_enqueue_style('ce_adminStyle');

    if (!empty($_GET['action'])) {
        wp_enqueue_style('codemirror', ELEMENTS_URL . 'codemirror/lib/codemirror.css', false);
        wp_enqueue_style('codemirror-theme-cobalt', ELEMENTS_URL . 'codemirror/theme/cobalt.css', false);
        wp_enqueue_style('codemirror-theme-eclipse', ELEMENTS_URL . 'codemirror/theme/eclipse.css', false);
        wp_enqueue_style('codemirror-theme-elegant', ELEMENTS_URL . 'codemirror/theme/elegant.css', false);
        wp_enqueue_style('codemirror-theme-monokai', ELEMENTS_URL . 'codemirror/theme/monokai.css', false);
        wp_enqueue_style('codemirror-theme-neat', ELEMENTS_URL . 'codemirror/theme/neat.css', false);
        wp_enqueue_style('codemirror-theme-night', ELEMENTS_URL . 'codemirror/theme/night.css', false);
        wp_enqueue_style('codemirror-theme-rubyblue', ELEMENTS_URL . 'codemirror/theme/rubyblue.css', false);
        wp_enqueue_style('codemirror-simple-hint', ELEMENTS_URL . 'codemirror/lib/util/simple-hint.css', false);
        wp_enqueue_style('codemirror-dialog-css', ELEMENTS_URL . 'codemirror/lib/util/dialog.css', false);
    }
}

function ce_scripts() {

    wp_enqueue_script("jquery");
    if (!empty($_GET['action'])) {
        wp_enqueue_script('codemirror', ELEMENTS_URL . 'codemirror/lib/codemirror.js', false);
        wp_enqueue_script('codemirror-mode-css', ELEMENTS_URL . 'codemirror/mode/css/css.js', false);
        wp_enqueue_script('codemirror-mode-js', ELEMENTS_URL . 'codemirror/mode/javascript/javascript.js', false);
        wp_enqueue_script('codemirror-mode-xml', ELEMENTS_URL . 'codemirror/mode/xml/xml.js', false);
        wp_enqueue_script('codemirror-mode-clike', ELEMENTS_URL . 'codemirror/mode/clike/clike.js', false);
        wp_enqueue_script('codemirror-mode-php', ELEMENTS_URL . 'codemirror/mode/php/php.js', false);
        wp_enqueue_script('codemirror-mode-htmlmixed', ELEMENTS_URL . 'codemirror/mode/htmlmixed/htmlmixed.js', false);
        wp_enqueue_script('codemirror-simple-hint-js', ELEMENTS_URL . 'codemirror/lib/util/simple-hint.js', false);
        wp_enqueue_script('codemirror-js-hint', ELEMENTS_URL . 'codemirror/lib/util/javascript-hint.js', false);
        wp_enqueue_script('codemirror-dialog-js', ELEMENTS_URL . 'codemirror/lib/util/dialog.js', false);
        wp_enqueue_script('codemirror-searchcursor-js', ELEMENTS_URL . 'codemirror/lib/util/searchcursor.js', false);
        wp_enqueue_script('codemirror-search-js', ELEMENTS_URL . 'codemirror/lib/util/search.js', false);
    }
}

function ce_adminPage() {
    if (!empty($_GET['action'])) {
        switch ($_GET['action']) {

            case 'edit':
                include ELEMENTS_PATH . 'edit.php';
                break;
            default:
                include ELEMENTS_PATH . 'admin.php';
                break;
        }
    } else {
        include ELEMENTS_PATH . 'admin.php';
    }
}

function ce_configOption($ID, $Name, $Type, $Title, $Config) {

    $Return = '';

    switch ($Type) {
        case 'hidden':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = $Config['_' . $Name];
            }
            $Return .= '<input type="hidden" name="data[_' . $Name . ']" id="' . $ID . '" value="' . $Val . '" />';
            break;
        case 'textfield':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = $Config['_' . $Name];
            }
            $Return .= $Title . ' <input type="textfield" name="data[_' . $Name . ']" id="' . $ID . '" value="' . $Val . '" />';
            break;
        case 'textarea':
            $Val = '';
            if (!empty($Config['_' . $Name])) {
                $Val = $Config['_' . $Name];
            }
            $Return .= $Title . ' <textarea name="data[_' . $Name . ']" id="' . $ID . '" cols="70" rows="25">'.htmlspecialchars($Val).'</textarea>';
            break;
        case 'radio':
            $parts = explode('|', $Title);
            $options = explode(',', $parts[1]);
            $Return .= $parts[0];
            $index = 1;
            foreach ($options as $option) {
                $sel = '';
                if (!empty($Config['_' . $Name])) {
                    if ($Config['_' . $Name] == $index) {
                        $sel = 'checked="checked"';
                    }
                }
                if (empty($Config)) {
                    if ($index === 1) {
                        $sel = 'checked="checked"';
                    }
                }

                $Return .= ' <input type="radio" name="data[_' . $Name . ']" id="' . $ID . '_' . $index . '" value="' . $index . '" ' . $sel . '/> <label for="' . $ID . '_' . $index . '">' . $option . '</label>';
                $index++;
            }
            break;
    }

    return '<div class="ce_configOption">' . $Return . '</div>';
}

function ce_saveElement($Data) {

    if (empty($Data['_ID'])) {
        $Data['_ID'] = strtoupper(uniqid('EL'));
    }
    if (empty($Data['_name'])) {
        $Data['_name'] = $Data['_ID'];
    }
    if (empty($Data['_category'])) {
        $Data['_category'] = 'Ungrouped';
    }

    $elements = get_option('CE_ELEMENTS');
    $elements[$Data['_ID']]['name'] = $Data['_name'];
    $elements[$Data['_ID']]['category'] = $Data['_category'];
    if (!empty($Data['_shortcode'])) {
        $Data['_shortcode'] = sanitize_key($Data['_shortcode']);
        $elements[$Data['_ID']]['shortcode'] = $Data['_shortcode'];
    }
    $elements[$Data['_ID']]['codeType'] = $Data['_shortcodeType'];
    if (!empty($Data['_variable'])) {
        foreach ($Data['_variable'] as $Key => $Varible) {
            $Data['_variable'][$Key] = sanitize_key($Varible);
        }
        $elements[$Data['_ID']]['variables']['names'] = $Data['_variable'];
        $elements[$Data['_ID']]['variables']['defaults'] = $Data['_variableDefault'];
        $elements[$Data['_ID']]['variables']['info'] = $Data['_variableInfo'];
    } else {
        unset($elements[$Data['_ID']]['variables']);
    }

    update_option($Data['_ID'], $Data);
    update_option('CE_ELEMENTS', $elements);

    return true;
}

function ce_doShortcode($atts, $content, $shortcode) {
    global $footerOutput, $javascript;

    $elements = get_option('CE_ELEMENTS');
    foreach ($elements as $id => $element) {
        if (!empty($element['shortcode'])) {
            if ($element['shortcode'] === $shortcode) {
                break;
            }
        }
    }
    if (empty($id)) {
        return;
    }
    $Element = get_option($id);

    $instanceID = uniqid($id);

    $Element['_mainCode'] = str_replace('{{content}}', $content, $Element['_mainCode']);
    $Element['_mainCode'] = str_replace('{{_id_}}',$instanceID, $Element['_mainCode']);

    $Element['_javascriptCode'] = str_replace('{{content}}', $content, $Element['_javascriptCode']);
    $Element['_javascriptCode'] = str_replace('{{_id_}}',$instanceID, $Element['_javascriptCode']);




    $pattern = '\[(\[?)(loop)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
    preg_match_all('/' . $pattern . '/s', $Element['_mainCode'], $loops);

    if (!empty($loops)) {
        foreach ($loops[0] as $loopKey => $loopcode) {
            if (!empty($loops[3][$loopKey])) {
                $LoopCodes[$loopKey] = $loops[5][$loopKey];
                $Element['_mainCode'] = str_replace($loopcode, '{{__loop_' . $loopKey . '_}}', $Element['_mainCode']);
                $Element['_javascriptCode'] = str_replace($loopcode, '{{__loop_' . $loopKey . '_}}', $Element['_javascriptCode']);
            }
        }
    }
    if (!empty($Element['_variable'])) {
        foreach ($Element['_variable'] as $VarKey => $Variable) {
            $VarVal = $Element['_variableDefault'][$VarKey];
            if (!empty($atts[$Variable])) {
                $VarVal = $atts[$Variable];
            }
            if (!empty($atts[$Variable . '_1'])) {
                $startcounter = true;
                $index = 1;
                while ($startcounter == true) {
                    if (!empty($atts[$Variable . '_' . $index])) {
                        $varArray[trim($Variable)][] = $atts[$Variable . '_' . $index];
                    } else {
                        $startcounter = false;
                    }
                    $index++;
                }
            }


            $Element['_mainCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $Element['_mainCode']);
            $Element['_javascriptCode'] = str_replace('{{' . $Variable . '}}', $VarVal, $Element['_javascriptCode']);
        }
        if (!empty($LoopCodes) && !empty($varArray)) {
            foreach ($LoopCodes as $loopKey => $loopCode) {
                $loopReplace = '';
                if (!empty($varArray[trim($loops[3][$loopKey])])) {
                    foreach ($varArray[trim($loops[3][$loopKey])] as $replaceKey => $replaceVar) {
                        $loopReplace .= $loopCode;
                        foreach ($varArray as $Variable => $VarableArray) {
                            if (!empty($varArray[$Variable][$replaceKey])) {
                                $loopReplace = str_replace('{{' . $Variable . '}}', $varArray[$Variable][$replaceKey], $loopReplace);
                            } else {
                                $loopReplace = str_replace('{{' . $Variable . '}}', '', $loopReplace);
                            }
                            $loopReplace = str_replace('[increment]', $replaceKey, $loopReplace);
                        }
                    }
                    $Element['_mainCode'] = str_replace('{{__loop_' . $loopKey . '_}}', $loopReplace, $Element['_mainCode']);
                    $Element['_javascriptCode'] = str_replace('{{__loop_' . $loopKey . '_}}', $loopReplace, $Element['_javascriptCode']);
                }
            }
        }
    }

    if (!empty($Element['_javascriptCode'])) {

        ob_start();
            eval(' ?>' . $Element['_javascriptCode'] . ' <?php ');
        $js = ob_get_clean();

        $footerOutput .= "
        " . $js . "

        ";
    }


    ob_start();
    eval(' ?>' . $Element['_mainCode'] . ' <?php ');
    $Output = ob_get_clean();
    $Output = str_replace("\r\n", "", $Output);
    $Output = str_replace("\r", "", $Output);
    $Output = str_replace("\n", "", $Output);

    return do_shortcode($Output);
}

if(is_admin ()){
    function ce_buildCategoriesDropdown(){

        $Elements = get_option('CE_ELEMENTS');
        $Cats = array();
        foreach($Elements as $ID=>$Config){
            $Cats[$Config['category']] = $Config['category'];
        }
        sort($Cats);
        echo "<select class=\"\" id=\"category\" onchange=\"ce_loadCategory();\">\n";
        echo "<option value=\"\"></option>";
        foreach($Cats as $Cat){
            echo "<option value=\"".$Cat."\">".$Cat."</option>\n";
        }
        echo "</select>";

    }
    function ce_loadElements(){

        $Category = $_POST['category'];
        $Elements = get_option('CE_ELEMENTS');
        $Items = array();
        foreach($Elements as $ID=>$Config){
            if($Config['category'] == $Category){
                $Items[$ID] = $Config['name'];
            }
        }

        echo "<select class=\"\" id=\"selectedelement\" onchange=\"ce_loadElement();\">\n";
        echo "<option value=\"\"></option>";
        foreach($Items as $ID=>$Element){
            echo "<option value=\"".$ID."\">".$Element."</option>\n";
        }
        echo "</select>";


        die;
    }

    function load_elementConfig(){
        $Element = get_option($_POST['element']);

        echo '<input type="hidden" id="shortcodekey" value="'.$Element['_shortcode'].'" />';
        echo '<input type="hidden" id="shortcodetype" value="'.$Element['_shortcodeType'].'" />';
        if(empty($Element['_variable'])){
            echo 'No attributes available for this Shortcode, You can just insert it now.';
        }else{
            foreach($Element['_variable'] as $key=>$var){

                echo '<div class="attr">';
                echo '<span class="label">'.$var.'</span>';
                echo '<input class="attrVal" type="text" id="'.$var.'" value="'.$Element['_variableDefault'][$key].'"/> <span class="description">'.$Element['_variableInfo'][$key].'</span>';
                echo '</div>';

            }
        }

        die;
    }

    function ce_deleteElement() {

            $EID = $_POST['EID'];

            $Elements = get_option('CE_ELEMENTS');
            delete_option($EID);
            unset($Elements[$EID]);
            update_option('CE_ELEMENTS', $Elements);
            echo true;
            die();
    }
    function ce_applyElement() {
            parse_str(stripslashes($_POST['formData']), $Data);
            $Data = stripslashes_deep($Data);
            if(!empty($Data['data']['_ID'])){
                ce_saveElement($Data['data']);
            }
            die();
    }

    function ce_ajax_javascript() {
    ?>
    <script type="text/javascript" >
        function ce_deleteElement(eid){
            //if(confirm('Are you sure?')){
                var data = {
                        action: 'delete_element',
                        EID: eid
                };
                jQuery.post(ajaxurl, data, function(response) {
                        if(response == 1){
                            jQuery('#element_'+eid).slideUp('fast', function(){
                                jQuery('#element_'+eid).remove();
                                jQuery('.current .cs-elementCount').html(parseFloat(jQuery('.current .cs-elementCount').html()-1));
                            });
                        }
                });
            //}else{
            //    jQuery('.buttons_'+eid).slideToggle();
            //}
        }
        function ce_applyElement(eid){
                jQuery('#saveIndicator').slideDown(100);
                var data = {
                        action: 'apply_element',
                        EID: eid,
                        formData: jQuery('#elementEditForm').serialize()
                };

                jQuery.post(ajaxurl, data, function(response) {
                    jQuery('#saveIndicator').slideUp(100);
                });

        }
    </script>
    <?php
    }



    function ce_exportElement($EID){
            $Element = get_option($EID);
            $Data = base64_encode(serialize($Element));
            $fileName = sanitize_file_name(strtolower($Element['_name'])).'.ce';
            header ("Expires: Mon, 21 Nov 1997 05:00:00 GMT");    // Date in the past
            header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
            header ("Pragma: no-cache");                          // HTTP/1.0
            header('Content-type: application/ce');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            print($Data);
            exit;

    }


}

?>