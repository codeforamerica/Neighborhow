<?php
if ( ! isset( $_GET['inline'] ) )
	define( 'IFRAME_REQUEST' , true );

/** Load WordPress Administration Bootstrap */
require_once('../../../wp-admin/admin.php');
?><!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
        <title>Shortcode Builder</title>
        <link media="all" type="text/css" href="<?php echo get_admin_url(); ?>load-styles.php?c=1&dir=ltr&load=wp-admin,media&ver=3.4-RC1" rel="stylesheet">
        <link id="colors-css" media="all" type="text/css" href="<?php echo get_admin_url(); ?>css/colors-fresh.css" rel="stylesheet">
        <link media="all" type="text/css" href="<?php echo ELEMENTS_URL; ?>styles/core.css" rel="stylesheet">
        <link media="all" type="text/css" href="<?php echo ELEMENTS_URL; ?>styles/panel.css" rel="stylesheet">
        <script type='text/javascript' src='<?php echo get_admin_url(); ?>load-scripts.php?c=1&amp;load=jquery,utils'></script>
    </head>
    <body>
        <div class="toolbar">
            Category: <?php echo ce_buildCategoriesDropdown(); ?>&nbsp;
            Element: <span id="element">Select a Category</span>
            <div class="fbutton" style="float:right;">
                <div class="button" onclick="ce_sendCode();">
                    <i class="icon-plus" style="margin-top:-1px;"></i> Insert Shortcode
                </div>
            </div>
        </div>
        <div class="content" id="content">

        </div>
        <div class="footer">

        </div>

        <script>
            function ce_sendCode(){
                if(jQuery('#selectedelement').length > 0){
                    if(jQuery('#selectedelement').val() == ''){
                        return;
                    }
                    var shortcode = jQuery('#shortcodekey').val();
                    var output = '['+shortcode+' ';
                    var ctype = '';
                    if(jQuery('#shortcodetype').val() == '2'){
                        var ctype = ' -content goes here- [/'+shortcode+']';
                    }
                    jQuery('.attrVal').each(function(){
                        output += this.id+'="'+this.value+'" ';
                    });
                    var win = window.dialogArguments || opener || parent || top;
                    win.send_to_editor(output+']'+ctype);
                }
            }
            function ce_loadCategory(){
                    var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
                    var cat = jQuery('#category').val();
                    var data = {
                            action: 'load_elements',
                            category: cat
                    };
                    jQuery('#element').html('Loading...');
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#element').html(response);
                    });

            }
            function ce_loadElement(){
                    var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
                    var element = jQuery('#selectedelement').val();
                    var data = {
                            action: 'load_elementConfig',
                            element: element
                    };
                    jQuery('#content').html('Loading Config...');
                    jQuery.post(ajaxurl, data, function(response) {
                        jQuery('#content').html(response);
                    });

            }



        </script>
    </body>
</html>