<div class="tablenav">
<?php if($page_count > 1){ // Only show the pager bar if there is more than 1 page ?>
    <div class="tablenav-pages">
        <?php $page_param = 'frm-page'; include(FRM_VIEWS_PATH.'/shared/pagination.php'); ?>
    </div>  
<?php } ?>
<br class="clear" />
</div>