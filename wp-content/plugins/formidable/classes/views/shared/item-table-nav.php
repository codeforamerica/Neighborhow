<div class="tablenav">
<?php do_action('frm_before_table', $footer, $params['form']);  
    
// Only show the pager bar if there is more than 1 page
if($page_count > 1){ ?>
<div class="tablenav-pages"><span class="displaying-num"><?php printf(__('Displaying %1$s&#8211;%2$s of %3$s', 'formidable'), $page_first_record, $page_last_record, $record_count); ?></span>   
    <?php $page_param = 'paged'; require(FRM_VIEWS_PATH .'/shared/pagination.php'); ?>
</div>  
<?php } ?>

<br class="clear" />
</div>