<div id="button_bar">
<ul class="subsubsub">
<?php   
$current_page = (isset($_GET['page'])) ? $_GET['page'] : 'None'; 
$nav_items = apply_filters('frm_nav_array', array()); 
$nav_count = count($nav_items);
$i = 1;
    
foreach ($nav_items as $nav_link => $nav_label){ ?>
    <li><a href="?page=<?php echo $nav_link ?>"<?php if($current_page == $nav_link) echo ' class="current"'; ?>><?php echo $nav_label ?></a> <?php if($i != $nav_count) echo '|'; ?> </li>
<?php $i++; 
} 
do_action('frm_nav_items'); ?>
</ul>
</div>

<div style="clear:both;"></div>
<div id="frm_tooltip" class="frm_tooltip">&nbsp;</div>