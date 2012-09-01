<?php if (isset($message) && $message != ''){ if(is_admin()){ ?><div id="message" class="updated fade" style="padding:5px;"><?php } echo $message; if(is_admin()){ ?></div><?php } } ?>

<?php if( isset($errors) && is_array($errors) && count($errors) > 0 ){ ?>
    <div class="error">
        <ul id="frm_errors">
            <?php foreach( $errors as $error )
                echo '<li>' . stripslashes($error) . '</li>';
            ?>
        </ul>
    </div>
<?php } ?>