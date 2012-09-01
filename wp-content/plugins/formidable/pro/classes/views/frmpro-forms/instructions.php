<div class="widget">
	<div class="widget-top">
		<div class="widget-title-action"><a class="widget-action"></a></div>
		<div class="widget-title"><h4><?php _e('Dynamic Default Values', 'formidable') ?></h4></div>
	</div><!-- /theme group Content -->
	<div class="widget-inside">

		<div class="clearfix" id="frm_dynamic_values">
        	<?php _e('Use dynamic default values by entering the shortcodes below as the default text.', 'formidable') ?>
            <ul style="margin-bottom:0;">
                <?php foreach ($tags as $tag => $label){ ?>
                    <li><b><?php echo $label ?>:</b>
                    <?php if ($tag == 'get param=whatever'){ ?>
                        <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('A variable from the URL or value posted from previous page.', 'formidable') ?>" />
                    <?php } ?>
                    [<?php echo $tag ?>]
                    <?php if ($tag == 'get param=whatever'){ ?>
                        <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Replace \'whatever\' with the parameter name. In url.com?product=form, the variable is \'product\'. You would use [get param=product] in your field.', 'formidable') ?>" />
                    <?php } ?>
                    </li>
                <?php } ?>
            </ul>
		</div>
	</div><!-- /theme group content -->
</div><!-- /theme group -->

       