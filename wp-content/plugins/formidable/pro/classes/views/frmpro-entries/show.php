<div class="wrap">
    <div id="icon-edit-pages" class="icon32"><br/></div>
    <h2><?php _e('View Entry', 'formidable') ?></h2>
    
    <div class="form-wrap">
        <div class="frm_forms">

        <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
        <?php FrmAppController::get_form_nav($entry->form_id, true); 
        
        if(version_compare( $GLOBALS['wp_version'], '3.3.3', '<')){ ?>
        <div id="poststuff" class="metabox-holder has-right-sidebar">
        <?php   
            require(FRMPRO_VIEWS_PATH .'/frmpro-entries/sidebar-show.php'); 
        }else{ ?>
        <div id="poststuff">
        <?php } ?>
            
            <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="postbox">
                    <h3 class="hndle"><span><?php _e('Entry', 'formidable') ?></span></h3>
                    <div class="inside">
                        <table class="form-table"><tbody>
                        <?php foreach($fields as $field){ 
                            if ($field->type == 'break' or $field->type == 'divider'){ ?>
                        </tbody></table>
                        <br/><h4><?php echo stripslashes($field->name) ?></h4>
                        <table class="form-table"><tbody>
                        <?php }else{?>
                        <tr valign="top">
                            <th scope="row"><?php echo stripslashes($field->name) ?>:</th>
                            <td>
                            <?php 
                            $field_value = isset($entry->metas[$field->id]) ? $entry->metas[$field->id] : false; 
                            $field->field_options = maybe_unserialize($field->field_options);

                            if(!$field_value and $field->type == 'data' and $field->field_options['data_type'] == 'data' and isset($field->field_options['hide_field'])){
                                $field_value = array();
                                foreach((array)$field->field_options['hide_field'] as $hfield ){
                                    if(isset($entry->metas[$hfield]))
                                        $field_value[] = maybe_unserialize($entry->metas[$hfield]);
                                }
                            }
                            
                            echo $display_value = FrmProEntryMetaHelper::display_value($field_value, $field, array('type' => $field->type, 'post_id' => $entry->post_id, 'show_filename' => true, 'show_icon' => true, 'entry_id' => $entry->id));

                            if(is_email($display_value) and !in_array($display_value, $to_emails))
                                $to_emails[] = $display_value
                            ?>
                            </td>
                        </tr>
                        <?php }
                        }  
                        
                        ?>
                        <?php if($entry->post_id){ ?>
                        <tr><th><?php _e('Post', 'formidable') ?>: 
                            <a href="<?php echo get_permalink($entry->post_id) ?>" class="button-secondary"><?php _e('View Post', 'formidable') ?></a>
                            </th>
                            <td><a href="<?php echo admin_url('post.php') ?>?post=<?php echo $entry->post_id ?>&amp;action=edit"><?php echo admin_url('post.php') ?>?post=<?php echo $entry->post_id ?>&amp;action=edit</a>      
                            </td>
                        </tr>
                        <?php } ?>
                        <tr><th><?php _e('Created at', 'formidable') ?>:</th><td><?php echo FrmProAppHelper::get_formatted_time($entry->created_at, $date_format, $time_format); ?>
                        <?php 
                            if($entry->user_id){
                                _e('by', 'formidable');
                                echo ' <a href="'.  admin_url('user-edit.php') .'?user_id='. $entry->user_id .'">'. FrmProFieldsHelper::get_display_name($entry->user_id) .'</a>';
                            }
                        ?>
                        </td></tr>
                        <?php if(strtotime($entry->updated_at) > 0){ ?>
                        <tr><th><?php _e('Last Updated', 'formidable') ?>:</th><td><?php echo FrmProAppHelper::get_formatted_time($entry->updated_at, $date_format, $time_format); ?>
                        <?php if($entry->updated_by)
                                echo __('by', 'formidable') .' '. FrmProFieldsHelper::get_display_name($entry->updated_by, 'display_name', array('link' => true));
                        ?>
                        </td></tr>
                        <?php } ?>
                        <tr><th><?php _e('Entry ID', 'formidable') ?>:</th><td><?php echo $entry->id ?></td></tr>
                        <tr><th><?php _e('Entry Key', 'formidable') ?>:</th><td><?php echo $entry->item_key ?></td></tr>
                        </tbody></table>
                        <?php do_action('frm_show_entry', $entry); ?>
                    </div>
                </div>
            
                <div class="postbox">
                    <h3 class="hndle"><span><?php _e('User Information', 'formidable') ?></span></h3>
                    <div class="inside">
        
                        <table class="form-table"><tbody> 
                            <tr valign="top">
                                <th scope="row"><?php _e('IP Address', 'formidable') ?>:</th>
                                <td><?php echo $entry->ip; ?></td>
                            </tr>
                            <?php if(isset($data['browser'])){ ?>
                            <tr valign="top">
                                <th scope="row"><?php _e('User-Agent (Browser/OS)', 'formidable') ?>:</th>
                                <td><?php echo $data['browser']; ?></td>
                            </tr>
                            <?php }
                            if(isset($data['referrer'])){ ?>
                            <tr valign="top">
                                <th scope="row"><?php _e('Referrer', 'formidable') ?>:</th>
                                <td><?php echo str_replace("\r\n", '<br/>', $data['referrer']); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody></table> 
                    </div>
                </div>
                
                <?php if($show_comments){ ?>
                <div class="postbox" id="frm_comment_list">
                    <h3 class="hndle"><span><?php _e('Comments/Notes', 'formidable') ?></span></h3>
                    <div class="inside">
                        <table class="form-table"><tbody> 
                        <?php foreach($comments as $comment){
                            $meta = maybe_unserialize($comment->meta_value);
                            if(!isset($meta['comment']))
                                continue;
                        ?>
                            <tr valign="top" class="frm_comment_block">
                                <th scope="row"><p><strong><?php echo FrmProFieldsHelper::get_display_name($meta['user_id'], 'display_name', array('link' => true)) ?></strong><br/>
                                    <?php echo FrmProAppHelper::get_formatted_time($comment->created_at, $date_format, $time_format)  ?></p>
                                </th>
                                <td><div class="frm_comment"><?php echo wpautop(strip_tags($meta['comment'])) ?></div></td>
                            </tr>
                        <?php } ?>
                        </table>
                        <a onclick="jQuery('#frm_comment_form').toggle('slow');" class="button-secondary alignright">+ <?php _e('Add Note/Comment', 'formidable') ?></a>
                        <div class="clear"></div>
                        
                        <form name="frm_comment_form" id="frm_comment_form" method="post" style="display:none;">
                            <input type="hidden" name="frm_action" value="show" />
                            <input type="hidden" name="field_id" value="0" />
                            <input type="hidden" name="item_id" value="<?php echo $entry->id ?>" />
                            <?php wp_nonce_field('add-option'); ?>
                            
                            <table class="form-table"><tbody> 
                                <tr valign="top">
                                    <th scope="row"><?php _e('Comment/Note', 'formidable') ?>:</th>
                                    <td><textarea name="frm_comment" id="frm_comment" cols="50" rows="5" style="width:98%"> </textarea>
                                    <!--
                                    </td>
                                </tr>
                                
                                <tr valign="top">
                                    <th scope="row"><?php _e('Send Emails to', 'formidable') ?>:</th>
                                    <td>
                                        <input type="text" name="frm_send_to[]" value="" class="frm_long_input"/><br/>
                                        <?php foreach($to_emails as $to_email){ ?>
                                        <input type="checkbox" name="frm_send_to[]" value="<?php echo esc_attr($to_email) ?>"/>  <?php echo $to_email ?><br/>
                                        <?php } ?>
                                        -->
                                        <p class="submit">
                                        <input class="button-primary" type="submit" value="<?php _e('Submit', 'formidable') ?>" />
                                        </p>
                                    </td>
                                </tr>
                                
                            </tbody></table> 
                        </form>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php
                if(version_compare( $GLOBALS['wp_version'], '3.3.2', '>'))
                    require(FRMPRO_VIEWS_PATH .'/frmpro-entries/sidebar-show.php'); 
            ?>
            </div>
        </div>
    </div> 
</div>
</div>
<br/>

<?php if(isset($_POST) and isset($_POST['frm_comment'])){ ?>
<script type="text/javascript">
window.onload = function(){var frm_pos=jQuery('#frm_comment_list').offset();
window.scrollTo(frm_pos.left,frm_pos.top);
}
</script>
<?php } ?>