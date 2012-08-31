<?php global $frm_settings; 
if (isset($message) && $message != ''){ 
    if(is_admin()){ 
        ?><div id="message" class="updated fade" style="padding:5px;"><?php echo $message ?></div><?php 
    }else{ 
        echo $message; 
    }
} 

if( isset($errors) && is_array($errors) && !empty($errors) ){
    global $frm_settings;
?>
<div class="<?php echo (is_admin()) ? 'error' : 'frm_error_style' ?>"> 
<?php 
if(!is_admin()){ 
    $img = apply_filters('frm_error_icon', '');
    if($img and !empty($img)){
    ?><img src="<?php echo $img ?>" alt="" />
<?php 
    }
} 
    
if(empty($frm_settings->invalid_msg)){
    $show_img = false;
    foreach( $errors as $error ){
        if($show_img and isset($img) and !empty($img)){ 
            ?><img src="<?php echo $img ?>" alt="" /><?php 
        }else{
            $show_img = true;
        }
        echo stripslashes($error) . '<br/>';
    }
}else{
    echo stripslashes($frm_settings->invalid_msg);

    $show_img = true;
    foreach( $errors as $err_key => $error ){
        if(!is_numeric($err_key) and ($err_key == 'cptch_number' or $err_key == 'form' or strpos($err_key, 'field') === 0 or strpos($err_key, 'captcha') === 0 ))
            continue;
          
        echo '<br/>'; 
        if($show_img and $img and !empty($img)){ 
            ?><img src="<?php echo $img ?>" alt="" /><?php 
        }else{
            $show_img = true;
        }
        echo stripslashes($error);
    }
} ?>
</div>
<?php } ?>