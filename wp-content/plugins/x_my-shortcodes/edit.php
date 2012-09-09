<style type="text/css">
.CodeMirror {border: 1px solid #aaa;}
.CodeMirror-scroll {height: 600px; overflow: auto; margin-right: 0 !important;}
.CodeMirror-gutter {width: 45px; !important;}
.CodeMirror-gutter-text {width: 38px; !important;}
#template div {margin-right: 0px;}
.searched {background: yellow;}
.fullscreen {height: 89%; right: 0; position: fixed; top: 80px; width: 100%; z-index: 100;}
#cfc-toolbar {margin-bottom: 2px;}
.cfc-toolbar-full {background-color: #ffffff; min-height: 85px; position: fixed; top: 50px; z-index: 100;}
.cfc-save {float: right;}
.cm-s-cobalt {background: #002240;}
.cm-s-default {background: #ffffff;}
.cm-s-eclipse {background: #ffffff;}
.cm-s-elegant {background: #ffffff;}
.cm-s-monokai {background: #272822;}
.cm-s-neat {background: #ffffff;}
.cm-s-night {background: #0a001f;}
.cm-s-rubyblue {background: #112435;}
</style>
<?php
    $Title = 'New Shortcode';
    $Element = array();
    if(!empty($_GET['element'])){
        $Element = get_option($_GET['element']);
        $Title = 'Editing: '.$Element['_name'];
    }


?>
<form action="?page=my_shortcode" method="post" id="elementEditForm">
    <?php
    wp_nonce_field('cs-edit-shortcode');
    ?>
    <div class="wrap poststuff wide" id="ce_container">
        <div id="header">            
            <div class="title">
                <h2><?php echo $Title; ?></h2>
            </div>
            <div class="clear"></div>
        </div>
        <div id="main">
            <div id="ce-nav">

                <ul>
                    <?php

                    $items = array(
                        'Shortcode Settings'=>'settings.php',
                        'Attributes'=>'variables.php',
                        'Template'=>'template.php',
                        'Javascript'=>'javascript.php',
                        'PHP'=>'php.php',
                        'CSS'=>'css.php',
                        'External Libraries'=>'libraries.php',
                        'Assets' => 'assets.php'
                    );
                    $Descriptions = array(
                        'Shortcode Settings'=>'',
                        'Options'=>'Shortcode attributes are settable attributes in the shortcode to alter your element.<br />e.g. [myelement background="#467238"] here, background is an options being set.<br />Attributes are be used in your template code by wrapping the option name in a double curly bracer {{optionname}}.',
                        'Template'=>'This is the code that renders to the screen. It can contain HTML, JavaScript, CSS and PHP all wrapped in the approprite tags.<br />Attributes set in the Attributes tab are accessable by {{attributename}}. In a wrapped shortcode, the content between the tags, will be available by using {{content}}.',
                        'Javascript'=>'The Javascript is rendered in the footer, wrapped in document ready.',
                        'PHP'=>'Here you may define your own custom functions to be used by your element. You can call functions defined here in your HTML tab.',
                        'CSS'=>'CSS defined here is rendered in the header.',
                        'External Libraries'=>'You can add external JavaScript and CSS field from a CDN to be loaded in either the header of footer.',
                    );


                    $index = 1;
                    foreach($items as $Name=>$File){
                        $class = '';
                        if($index == 1){
                            $class = 'current';
                        }
                        if(!empty($_GET['ctb'])){
                            if($_GET['ctb'] == $index){
                                $class = 'current';
                            }
                        }

                    ?>
                    <li class="<?php echo $class; ?>">
                        <a title="<?php echo $Name; ?>" href="#ctb_<?php echo $index; ?>"><?php echo $Name; ?></a>
                    </li>
                    <?php
                    $index++;
                    }
                    ?>
                </ul>

            </div>

            <div id="content">
                <?php
                    $index = 1;
                    foreach($items as $Name=>$File){
                        $display = 'block';
                        if($index == 1){
                            $display = 'block';
                        }
                        if(!empty($_GET['ctb'])){
                            if($_GET['ctb'] == $index){
                                $display = 'block';
                            }
                        }

               ?>


                <div style="display: <?php echo $display; ?>;" class="group" id="ctb_<?php echo $index; ?>">
                    <h2><?php echo $Name; ?></h2>
                    <?php
                    if(!empty($Descriptions[$Name])){
                        echo '<div style="padding: 2px 0 8px;"><span class="description">'.$Descriptions[$Name].'</span></div>';
                    }

                    include ELEMENTS_PATH . 'libs/'.$File;
                    ?>
                </div>

                <?php
                    $index++;
                    }
                ?>

            </div>
            <div class="clear"></div>

        </div>
        <div class="save_bar_top">
            <input type="submit" class="button" value="Save" /><?php
            if(!empty($_GET['element'])){
            ?>&nbsp;
            <span class="submit-footer-reset">
                <input type="button" class="button-primary" value="Apply" onclick="ce_applyElement('<?php echo $_GET['element']; ?>');">
            </span>
            <?php
            }
            ?>
        </div>
<span id="saveIndicator">Saving</span>
        <div style="clear:both;"></div>
    </div>
</form>
<script type="text/javascript">
    function randomUUID() {
      var s = [], itoh = '0123456789ABCDEF';
      for (var i = 0; i <6; i++) s[i] = Math.floor(Math.random()*0x10);
      return s.join('');
    }
    
    jQuery(document).ready(function(){
            
        jQuery('.group').hide();
        jQuery('#ctb_1').show();
        jQuery('#ce-nav li a').click(function(){
            jQuery('#ce-nav li').removeClass('current');
            jQuery('.group').hide();
            jQuery(''+jQuery(this).attr('href')+'').show();
            jQuery(this).parent().addClass('current');
            return false;
        });

    });
</script>