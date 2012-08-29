<script>
    function frm_insert_form(){
        var form_id=jQuery("#frm_add_form_id").val();
        if(form_id==""){alert("<?php _e('Please select a form', 'formidable') ?>");return;}
        var title_qs=jQuery("#frm_display_title").is(":checked") ? " title=true" : "";
        var description_qs=jQuery("#frm_display_description").is(":checked") ? " description=true" : "";
        var win = window.dialogArguments || opener || parent || top;
        win.send_to_editor("[formidable id="+form_id+title_qs+description_qs+"]");
    }
    
    function frm_insert_display(){
        var display_id = jQuery("#frm_add_display_id").val();
        if(display_id==""){alert("<?php _e('Please select a custom display', 'formidable') ?>");return;}
        var filter_qs=jQuery("#frm_filter_content").is(":checked") ? " filter=1" : "";
        var win = window.dialogArguments || opener || parent || top;
        win.send_to_editor("[display-frm-data id="+display_id+filter_qs+"]");
    }
    
    function frm_insert_search(){
        var win = window.dialogArguments || opener || parent || top;
        win.send_to_editor("[frm-search]");
    }
</script>

<div id="frm_insert_form" style="display:none;">
    <style>
    #frm_popup_content h3{
        color:#5A5A5A;
        font-family:Georgia,"Times New Roman",Times,serif;
        font-weight:normal;
        font-size:1.6em;
    }
    </style>
    <div class="wrap" id="frm_popup_content">
    <h3><?php _e("Select a form to insert", "formidable"); ?></h3>
    
    <p><?php FrmFormsHelper::forms_dropdown( 'frm_add_form_id' )?></p>

    <p><input type="checkbox" id="frm_display_title" /> <label for="frm_display_title"><?php _e("Display form title", "formidable"); ?></label> &nbsp; &nbsp;
        <input type="checkbox" id="frm_display_description" /> <label for="frm_display_description"><?php _e("Display form description", "formidable"); ?></label>
    </p>
    
    <p><input type="button" class="button-primary" value="Insert Form" onclick="frm_insert_form();" /></p>
        
<?php if(isset($displays) and !empty($displays)){ ?>
    <div style="border-bottom:1px solid #DFDFDF;display:block;margin:20px 0;"></div>
    <h3><?php _e('Select custom display to insert', 'formidable'); ?></h3>
    
    <p>
        <select name="frm_add_display_id" id="frm_add_display_id" class="frm-dropdown">
            <option value=""></option>
            <?php foreach ($displays as $display){ ?>
            <option value="<?php echo $display->id ?>"><?php echo $display->name ?></option>
            <?php } ?>
        </select>
    </p>
    
    <p><input type="checkbox" id="frm_filter_content" /> <label for="frm_filter_content"><?php _e("Filter shortcodes within the custom display content", "formidable"); ?></label>
        <span class="howto"><?php _e("Note: In some cases, this option can create an infinite loop", "formidable"); ?></span>
    </p>
    
    <p><input type="button" class="button-primary" value="Insert Display" onclick="frm_insert_display();" /></p>
    
    <div style="border-bottom:1px solid #DFDFDF;display:block;margin:20px 0;"></div>
    <h3><?php _e("Insert a search box", "formidable"); ?></h3>
    <p><input type="button" class="button-primary" value="Insert Search" onclick="frm_insert_search();" /></p>
<?php } ?>
    </div>
</div>