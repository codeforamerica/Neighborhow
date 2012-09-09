<div style="padding: 3px 0 10px;">
    <input type="button" value="Add JavaScript Library" onclick="ce_addJSLibrary();" class="button" />
    <input type="button" value="Add CSS Library" onclick="ce_addCSSLibrary();" class="button" />
</div>
<div id="jslibraryPane">
<?php
    if(!empty($Element['_jsLib'])){
        foreach($Element['_jsLib'] as $key=>$var){

            $foot = '';
            $head = 'checked="checked"';
            if(!empty($Element['_jsLibLoc'][$key])){
                if($Element['_jsLibLoc'][$key] == 2){
                    $head = '';
                    $foot = 'checked="checked"';
                }
            }

            echo '<div id="'.$key.'" style="padding:3px 0;">';
            echo 'JS URL: <input type="textfield" value="'.$var.'" id="name" name="data[_jsLib]['.$key.']" style="width: 400px;" />';
            echo ' Location: <input type="radio" value="1" id="header_radio_'.$key.'" name="data[_jsLibLoc]['.$key.']" '.$head.' /> <label for="header_radio_'.$key.'"> Header</label>';
            echo '<input type="radio" value="2" id="footer_radio_'.$key.'" name="data[_jsLibLoc]['.$key.']" '.$foot.' /> <label for="footer_radio_'.$key.'"> Footer</label>';
            echo ' [<a href="#" onclick="jQuery(\'#'.$key.'\').remove();">Remove</a>]</div>';
        }
    }
?>
</div>
<div id="csslibraryPane">
<?php
//vardump($Element);
    if(!empty($Element['_cssLib'])){
        foreach($Element['_cssLib'] as $key=>$var){
            echo '<div id="'.$key.'" style="padding:3px 0;">';
            echo 'CSS URL: <input type="textfield" value="'.$var.'" id="name" name="data[_cssLib]['.$key.']" style="width: 389px;" />';
            echo ' [<a href="#" onclick="jQuery(\'#'.$key.'\').remove();">Remove</a>]</div>';
        }
    }
?>
</div>


<script type="text/javascript">

    function ce_addJSLibrary(){

        var rowID = randomUUID();
        jQuery('#jslibraryPane').append('\
            <div id="'+rowID+'" style="padding:3px 0;">\n\
                JS URL: <input type="textfield" value="" id="name" name="data[_jsLib]['+rowID+']" style="width: 400px;" /> \n\
                Location: <input type="radio" value="1" id="header_radio_'+rowID+'" name="data[_jsLibLoc]['+rowID+']" /> <label for="header_radio_'+rowID+'"> Header</label>\n\
                <input type="radio" value="2" id="footer_radio_'+rowID+'" name="data[_jsLibLoc]['+rowID+']" checked="checked" /> <label for="footer_radio_'+rowID+'"> Footer</label>\n\
                [<a href="#" onclick="jQuery(\'#'+rowID+'\').remove();">Remove</a>]</div>')

    }
    function ce_addCSSLibrary(){

        var rowID = randomUUID();
        jQuery('#csslibraryPane').append('\
            <div id="'+rowID+'" style="padding:3px 0;">\n\
                CSS URL: <input type="textfield" value="" id="name" name="data[_cssLib]['+rowID+']" style="width: 389px;" /> \n\
                [<a href="#" onclick="jQuery(\'#'+rowID+'\').remove();">Remove</a>]</div>')

    }


</script>