<div class="wrap">
    <div id="icon-edit-pages" class="icon32"><br/></div>
    <h2><?php _e('Import Entries', 'formidable') ?></h2>
    
    <?php include(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    <br/>
    
    <?php if($step == 'import'){ ?>
        
        <div class="with_frm_style" id="frm_import_message" style="margin:15px 0;line-height:2.5em;"><span class="frm_message" style="padding:7px;"><?php printf(__('The next 250 of the remaining %1$s entries are importing.', 'formidable'), $left) ?> <a id="frm_import_link" class="button-secondary" href="javascript:frmImportCsv()"><?php _e('Import Now', 'formidable') ?></a></span></div>
<script type="text/javascript">
//<![CDATA[
setTimeout( "frmImportCsv()", 250 );
function frmImportCsv(){ 
    jQuery('#frm_import_link').replaceWith('<img src="<?php echo FRM_IMAGES_URL; ?>/wpspin_light.gif" alt="<?php _e('Loading...', 'formidable'); ?>" />');
    jQuery.ajax({type:"POST",url:"<?php echo $frm_ajax_url ?>",data:"action=frm_import_csv&frm_skip_cookie=1<?php echo $url_vars ?>",
    success:function(count){
        if(parseInt(count) > 0){ jQuery("#frm_import_message .frm_message").html('The next 250 of the remaining '+count+' entries are importing.<br/> If your browser doesn&#8217;t start loading the next set automatically, click this button: <a id="frm_import_link"  class="button-secondary" href="javascript:frmImportCsv()">Import Now</a>');
            location.href = "?page=<?php echo $_GET['page'] ?>&frm_action=import&step=import<?php echo $url_vars ?>";
        }else{ 
            jQuery("#frm_import_message").fadeOut("slow");
            location.href = "?page=formidable-entries&frm_action=list&form=<?php echo $form_id ?>";
        }
    }
    });
};
//]]>
    </script>
        
    <?php }else{ ?>
    <form enctype="multipart/form-data" method="post">
        <input type="hidden" name="frm_action" value="import" />
        <?php wp_nonce_field('import-csv'); ?>
        
        
        <div id="poststuff" class="metabox-holder">
            <div id="post-body">
            <div id="post-body-content">
                <div class="postbox ">
                <div class="handlediv"><br/></div><h3 class="hndle"><span><?php echo __('Step', 'formidable') . ' '. $step; ?></span></h3>
                <div class="inside">
                    
                <?php if($step == 'One'){ ?>
                <input type="hidden" name="step" value="Two" />
                <table class="form-table">
                    <tr class="form-field">
                        <th valign="top" scope="row"><?php _e('Select CSV', 'formidable'); ?></th>
                        <td>
                            <input type="file" name="csv" id="csv" value="" />
                            <?php if($csvs){ ?>
                                <span style="padding:0 20px"><?php _e('or', 'formidable'); ?></span>
                                <select name="csv">
                                    <option value="">- <?php _e('Select previously uploaded CSV', 'formidable') ?> -</option>
                                <?php foreach($csvs as $c){ ?>
                                    <option value="<?php echo $c->ID ?>"><?php echo $c->post_title ?></option>
                                <?php } ?>
                                </select>
                            <?php } ?>
                        </td>
                    </tr>
                    
                    <tr class="form-field">
                        <th valign="top" scope="row"><?php _e('CSV Delimiter', 'formidable'); ?></th>
                        <td>
                            <input type="text" name="csv_del" value="<?php echo esc_attr($csv_del) ?>" />
                        </td>
                    </tr>

                    <tr class="form-field">
                        <th valign="top" scope="row"><?php _e('Import Into Form', 'formidable'); ?></th>
                        <td><?php FrmFormsHelper::forms_dropdown( 'form_id', $form_id, true, false); ?></td>
                    </tr>
                    
                </table>
                <?php }else if($step == 'Two'){ ?>
                    <input type="hidden" name="step" value="import" />
                    <input type="hidden" name="csv" value="<?php echo $media_id ?>" />
                    <input type="hidden" name="row" value="<?php echo $row ?>" />
                    <input type="hidden" name="form_id" value="<?php echo $form_id ?>" />
                    <input type="hidden" name="csv_del" value="<?php echo esc_attr($csv_del) ?>" />
                    <table class="form-table">
                        <thead>
                        <tr class="form-field">
                            <th><b><?php _e('CSV header' ,'formidable') ?></b></th>
                            <th><b><?php _e('Sample data' ,'formidable') ?></b></th>
                            <th><b><?php _e('Corresponding Field' ,'formidable') ?></b></th>
                        </tr>
                        </thead>
                        <?php foreach($headers as $i => $header){ ?>
                        <tr class="form-field">
                            <td><?php echo htmlspecialchars($header) ?></td>
                            <td><span class="howto"><?php echo htmlspecialchars($example[$i]) ?></span></td>
                            <td>
                                <select name="data_array[<?php echo $i ?>]" id="mapping_<?php echo $i ?>">
                                    <option value=""></option>
                                    <?php foreach ($fields as $field){ ?>
                                        <option value="<?php echo $field->id ?>" <?php selected(strip_tags($field->name), htmlspecialchars($header)) ?>><?php echo FrmAppHelper::truncate($field->name, 50) ?></option>
                                    <?php
                                        unset($field);
                                    }
                                    ?>
                                    <option value="post_id"><?php _e('Post ID', 'formidable') ?></option>
                                    <option value="created_at"><?php _e('Created at', 'formidable') ?></option>
                                    <option value="user_id"><?php _e('Created by', 'formidable') ?></option>
                                    <option value="updated_at"><?php _e('Updated at', 'formidable') ?></option>
                                    <option value="updated_by"><?php _e('Updated by', 'formidable') ?></option>
                                    <option value="ip"><?php _e('IP Address', 'formidable') ?></option>
                                    <option value="item_key"><?php _e('Entry Key', 'formidable') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php } ?>
                      </table>
                <?php } ?>
                <p class="submit"><input type="submit" value="<?php echo $next_step ?>" class="button-primary" /></p>
                </div>
                </div>
    
            </div>
            </div>
        </div>
    </form>
    <?php } ?>

</div>