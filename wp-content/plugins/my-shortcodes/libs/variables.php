<p class="description">Extend attribute flexibility in the Pro Version with different Attribute types and settings. Find out more on the <a href="http://myshortcodes.cramer.co.za/pro-version/" taget="_blank">My Shortcodes Pro website</a></p>
<div style="padding: 3px 0 10px;">
    <input type="button" value="Add Attribute" onclick="ce_addVariable();" class="button" />
</div>
<div id="variablePane">
<?php
    if(!empty($Element['_variable'])){
        foreach($Element['_variable'] as $key=>$var){
            $default = '';
            if(!empty($Element['_variableDefault'][$key])){
                $default = $Element['_variableDefault'][$key];
            }
            $info = '';
            if(!empty($Element['_variableInfo'][$key])){
                $info = $Element['_variableInfo'][$key];
            }

            echo '<div id="'.$key.'" style="padding:3px 0;">';
            echo 'Name: <input type="textfield" value="'.$var.'" id="name" name="data[_variable]['.$key.']"> ';
            echo 'Default: <input type="textfield" value="'.$default.'" id="name" name="data[_variableDefault]['.$key.']"> ';
            echo 'Info: <input type="textfield" value="'.$info.'" id="name" name="data[_variableInfo]['.$key.']">';
            echo '[<a href="#" onclick="jQuery(\'#'.$key.'\').remove();">Remove</a>]</div>';
        }
    }
?>
</div>


<script type="text/javascript">

    function ce_addVariable(){

        var rowID = randomUUID();
        jQuery('#variablePane').append('\
            <div id="'+rowID+'" style="padding:3px 0;">\n\
                Name: <input type="textfield" value="" id="name" name="data[_variable]['+rowID+']"> \n\
                Default: <input type="textfield" value="" id="name" name="data[_variableDefault]['+rowID+']"> \n\
                Info: <input type="textfield" value="" id="name" name="data[_variableInfo]['+rowID+']"> \n\
                [<a href="#" onclick="jQuery(\'#'+rowID+'\').remove();">Remove</a>]</div>')

    }


</script>