<div class="wrap poststuff" id="ce_container">

    <input type="hidden" id="_FormLayout" rows="10" cols="50" name="Data[Content][_FormLayout]">
    <div id="header">
        <div class="title">
            <h2>My Shortcodes</h2>
        </div>
        <div class="clear"></div>
    </div>
    <div class="save_bar_tools">
        <span class="fbutton"><a href="?page=my_shortcode&action=edit"><div id="addNewInterface" class="button"><span class="icon-plus-sign" style="margin-top:-1px;"></span> New Shortcode</div></a></span>
        <span class="fbutton"><a href="#importer_screen" id="importer"><div class="button"><span class="icon-file" style="margin-top:-1px;"></span> Import</div></a></span>
        <span class="fbutton"><a href="#explain_screen" id="explain"><div class="button"><span class="icon-question-sign" style="margin-top:-1px;"></span> How it works</div></a></span>
    </div>
    <div id="main">
        <div id="ce-nav">

            <ul>
                <?php

                $Elements = get_option('CE_ELEMENTS');
                $pages = array();
                if(empty($Elements)){
                    $Elements = array();
                }
                foreach($Elements as $element=>$options){
                    $pages[$options['category']][] = $element;
                }
                ksort($pages);
                $elementindex = 1;
                if(empty($pages)){
                    echo '<li class="current">';
                        echo '<a title="Elements" href="#Elements">Shortcodes</a>';
                    echo '</li>';
                }
                foreach($pages as $page=>$items){
                    $class = '';
                    if($elementindex === 1){
                        $class = 'class="current"';
                    }
                    $tabid = sanitize_key($page);

                ?>
                <li <?php echo $class; ?>>
                    <span class="cs-elementCount"><?php echo count($items); ?></span><a title="<?php echo $page; ?>" href="#<?php echo $tabid; ?>"><?php echo $page; ?></a>
                </li>
                <?php
                    $elementindex++;
                }
                ?>

            </ul>

        </div>

        <div id="content">
            <div style="display: none;" class="group" id="importer_screen">
                <h2>Import Shortcode Element</h2>
                <form method="post" enctype="multipart/form-data" >
                    <?php
                        echo wp_nonce_field('cs-import-shortcode');
                    ?>
                    File <input type="file" name="import" /><input class="button" type="submit" value="Import" />
                </form>
            </div>
            <div style="display: none;" class="group" id="explain_screen">
                <h2>How it works; Page rendering process.</h2>
                <div id="explainHeader">
                    <img src="<?php echo ELEMENTS_URL; ?>screenshot-4.png" />
                </div>
            </div>
        <?php
        $index = 1;
        if(empty($pages)){
            echo '<div class="group" id="Elements"><h2>Shortcodes</h2>Once you start creating shortcodes, they will be listed here and within their categories to the left.</div>';
        }
        foreach($pages as $page=>$items){
        $tabid = sanitize_key($page);
            $Show = 'none';
            if($index === 1){
                $Show = 'block';
            }

        ?>
            <div style="display: <?php echo $Show; ?>;" class="group" id="<?php echo $tabid; ?>">
                <span class="fbutton exporter" style="float:right; padding: 6px 3px 3px 3px;">
                    <a href="#">
                        <div class="button" id="showPanel">
                            <span style="margin-top:-1px;" class="icon-eye-open"></span> Show Export Config</div>
                    </a>
                </span>
                <span class="fbutton manager" style="float:right; padding: 6px 3px 3px 3px; display: none;">
                    <a href="#">
                        <div class="button" id="hidePanel">
                            <span style="margin-top:-1px;" class="icon-eye-close"></span> Hide Export Config</div>
                    </a>
                </span>
                <h2><?php echo $page; ?></h2>
                <div class="catexport" style="display:none;">
                    <p class="description">Exporting only available in the Pro Version</p>
                    <p>Export your shortcodes as Standalone Plugins. Set your shortcodes as Widgets, Post Types and standard Shortcodes.</p>
                    <p>Find out more about the Pro Version on the <a href="http://myshortcodes.cramer.co.za/pro-version/" taget="_blank">My Shortcodes Pro website</a></p>
                </div>
                <div class="catbody">
                <?php

                if(!empty($Elements)){

                foreach($items as $Element){

                    $Options = $Elements[$Element];
                    $ShortCode = 'celement id='.$Element;
                    if(!empty($Options['shortcode'])){
                        $ShortCode = $Options['shortcode'];
                    }

                ?>
                <div id="element_<?php echo $Element; ?>">
                <div class="cs-elementItem">
                    <div class="cs-elementIcon"></div>
                    <div class="cs-elementInfoPanel">
                        <?php echo $Options['name']; ?>
                        <div class="cs-elementInfoPanel description"><?php echo $Element; ?></div>
                    </div>
                    <div class="cs-elementInfoPanel mid">Shortcode <?php if(!empty($Options['variables'])){ ?><span class="infoTrigger" rel="<?php echo $Element; ?>">Options</span><?php } ?>
                        <div class="cs-elementInfoPanel description">[<?php echo $ShortCode; ?>]</div>
                    </div>
                    <div id="" class="cs-elementInfoPanel last buttonbar buttons_<?php echo $Element; ?>" style="display:block;">

                        <span class="fbutton"><a href="?page=my_shortcode&action=edit&element=<?php echo $Element; ?>"><div class="button"><span class="icon-edit"></span></div></a></span>
                        <span class="fbutton"><a href="?page=my_shortcode&export=<?php echo $Element; ?>"><div class="button"><span class="icon-download-alt"></span></div></a></span>
                        <span class="fbutton"><a href="#" class="confirm" rel="<?php echo $Element; ?>" onclick="return false;"><div class="button"><span class="icon-remove-sign"></span></div></a></span>
                    </div>
                    <div id="confirm_<?php echo $Element; ?>" class="cs-elementInfoPanel last buttons_<?php echo $Element; ?>" style="display:none;">
                        <div class="infoDelete">Delete Element</div>
                        <span class="fbutton"><a href="#" onclick="ce_deleteElement('<?php echo $Element; ?>'); return false;"><div class="button"><span class="icon-ok"></span></div></a></span>
                        <span class="fbutton"><a href="#" onclick="return false;" class="confirm" rel="<?php echo $Element; ?>"><div class="button"><span class="icon-share-alt"></span> Cancel</div></a></span>

                    </div>
                </div>
                <?php
                    if(!empty($Options['variables'])){

                    $example = '['.$ShortCode.' ';

                ?>
                    <div class="cs-infopanel cs-elementItem" id="options_<?php echo $Element; ?>">
                        <h2>Options</h2>
                        <table width="100%" class="widefat">
                            <thead>
                                <tr>
                                    <th width="125">Option</th>
                                    <th width="125">Default</th>
                                    <th width="250">Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach($Options['variables']['names'] as $Key=>$Variable){

                                    $example .= $Variable.'="'.$Options['variables']['defaults'][$Key].'" ';

                                ?>
                                <tr>
                                    <td width="125"><?php echo $Variable; ?></td>
                                    <td width="125"><?php echo $Options['variables']['defaults'][$Key]; ?></td>
                                    <td width="250"><?php echo $Options['variables']['info'][$Key]; ?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php

                            echo '<h2>Default Usage</h2>';
                            echo '['.$ShortCode.']';
                            if($Options['codeType'] == 2){
                                echo ' content [/'.$ShortCode.']';
                            }
                            echo '<h2>Full usage with defaults</h2>';
                            echo $example.']';
                            if($Options['codeType'] == 2){
                                echo ' content [/'.$ShortCode.']';
                            }

                        ?>
                    </div>
                <?php
                    }
                ?>
                    </div>
                <?php
                }}else{
                    echo 'You have no elements';
                }
                ?>

            </div>
        </div>

            <?php
            $index++;
        }
            ?>



        </div>
        <div class="clear"></div>

    </div>
    <div class="save_bar_top" style="padding:10px; height: 15px;">

    </div>

    <div style="clear:both;"></div>
</div>

<script type="text/javascript">

    jQuery(document).ready(function(){

        jQuery('.confirm').click(function(){
            var ele = jQuery(this).attr('rel');
            jQuery('.buttons_'+ele).slideToggle();
        });
        jQuery('.infoTrigger').click(function(){
            jQuery('#options_'+jQuery(this).attr('rel')).slideToggle('fast');
        });
        jQuery('#ce-nav li a').click(function(){
            jQuery('#ce-nav li').removeClass('current');
            jQuery('.group').hide();
            jQuery(''+jQuery(this).attr('href')+'').show();
            jQuery(this).parent().addClass('current');
            return false;
        });
        jQuery('#importer').click(function(){
            jQuery('#ce-nav li').removeClass('current');
            jQuery('.group').hide();
            jQuery(''+jQuery(this).attr('href')+'').show();
            jQuery(this).parent().addClass('current');
            return false;
        });
        jQuery('#explain').click(function(){
            jQuery('#ce-nav li').removeClass('current');
            jQuery('.group').hide();
            jQuery(''+jQuery(this).attr('href')+'').show();
            jQuery(this).parent().addClass('current');
            return false;
        });
        jQuery('.exporter').click(function(){
            jQuery(this).hide();
            jQuery(this).parent().find('.manager').show();
            jQuery(this).parent().find('.catbody').slideToggle();
            jQuery(this).parent().find('.catexport').slideToggle();
        })
        jQuery('.manager').click(function(){
            jQuery(this).hide();
            jQuery(this).parent().find('.exporter').show();
            jQuery(this).parent().find('.catbody').slideToggle();
            jQuery(this).parent().find('.catexport').slideToggle();
        })

    });
</script>