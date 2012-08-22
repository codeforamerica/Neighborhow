jQuery(document).ready(function($) {
              
    // Upload image         
    $('#origin_favicon_upload_button, #origin_logo_upload_button').click(function() {
        formfield = $(this).prev().attr('id');
        tbframe_interval = setInterval(function() {
            $('#TB_iframeContent').contents().find('.savesend input[type="submit"]').val(js_text.insert_into_post);
        }, 500);        
        tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=true');
        return false;
    });

    // Insert the image url into the input field
    window.send_to_editor = function(html) {       
        fileurl = $('img', html).attr('src');  
        $('#' + formfield).val(fileurl);     
        tb_remove();
    } 

    // Colorpicker
    jQuery('#colorpicker_link_color').farbtastic('#origin_theme_settings-origin_link_color');
    
    jQuery('#origin_theme_settings-origin_link_color').blur( function() {
            jQuery('#colorpicker_link_color').hide();
    });
    
    jQuery('#origin_theme_settings-origin_link_color').focus( function() {
            jQuery('#colorpicker_link_color').show();
    });
             
});