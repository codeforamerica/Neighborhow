<div class="wrap">
    <div id="icon-edit-pages" class="icon32"><br/></div>
    <h2><?php _e('Add New Entry', 'formidable') ?></h2>
    
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>

    <div id="menu-management" class="clear nav-menus-php" style="margin-top:30px;">
        <div class="menu-edit" style="width:300px;">
        <div id="nav-menu-header"><div class="major-publishing-actions" style="padding:8px 0;">
            <div style="font-size:15px;background:transparent;" class="search"><?php _e('Add New Entry', 'formidable') ?></div>
        </div></div>

        <form method="get">
            <div id="post-body">
            <p><?php _e('Select a form for your new entry.', 'formidable'); ?></p>
            <input type="hidden" name="frm_action" value="new" />
            <input type="hidden" name="page" value="formidable-entries" />
            <?php FrmFormsHelper::forms_dropdown('form', '', false); ?><br/>
            </div>
            <div id="nav-menu-footer">
            <div class="major-publishing-actions"><input type="submit" class="button-primary" value="<?php _e('Go', 'formidable') ?>" /></div>

            <div class="clear"></div>
            </div>
        </form>
        </div>

    </div>
</div>