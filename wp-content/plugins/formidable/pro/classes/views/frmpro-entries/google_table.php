<?php if(!$frm_google_chart){ ?><script type="text/javascript" src="https://www.google.com/jsapi"></script><?php } ?>
<script type="text/javascript">
google.load('visualization', '1.0', {'packages':['table']});
google.setOnLoadCallback(get_frm_table_<?php echo $form->id ?>);
function get_frm_table_<?php echo $form->id ?>(){
var data=new google.visualization.DataTable();
<?php if(in_array('id', $fields)){ ?>   
data.addColumn('number','<?php _e("ID", "formidable") ?>');
<?php }
    foreach ($form_cols as $col){ 
        $type = ($col->type == "number") ? "number" : "string";
        if($col->type == 'checkbox' or $col->type == 'select'){
            $col->options = maybe_unserialize($col->options);
            $count = count($col->options);
            if($col->type == 'select' and reset($col->options) == '')
                $count--;
            if($count == 1)
                $type = 'boolean';
            unset($count);
        }
    ?>
data.addColumn('<?php echo $type ?>','<?php echo $col->name; ?>');    
<?php
    unset($col);
    unset($type);
    } 

if($edit_link){ ?>
data.addColumn('string','<?php echo addslashes($edit_link) ?>');
<?php    
}

if($entries){ ?>
data.addRows(<?php echo count($entries) ?>);
<?php
$i = 0;
foreach($entries as $entry){
    $c = 0;
    if(in_array('id', $fields)){ ?>   
data.setCell(<?php echo $i ?>,<?php echo $c ?>,<?php echo $entry->id ?>);
<?php 
        $c++;
    }
    foreach ($form_cols as $col){
        $type = ($col->type == "number") ? "number" : "string";
        if($col->type == 'checkbox' or $col->type == 'select'){
            $col->options = maybe_unserialize($col->options);
            $count = count($col->options);
            if($col->type == 'select' and reset($col->options) == '')
                $count--;
            if($count == 1)
                $type = 'boolean';
            unset($count);
        }
        
        $val = FrmProEntryMetaHelper::display_value((isset($entry->metas[$col->id]) ? $entry->metas[$col->id] : false), $col, array('type' => $col->type, 'post_id' => $entry->post_id, 'entry_id' => $entry->id));
        if($type == 'number'){ ?>
data.setCell(<?php echo $i ?>,<?php echo $c ?>,<?php echo empty($val) ? '0' : $val ?>);
<?php   }else if($type == 'boolean'){ ?>
data.setCell(<?php echo $i ?>,<?php echo $c ?>,<?php echo empty($val) ? 'false' : 'true' ?>);
<?php   }else{ ?>
data.setCell(<?php echo $i ?>,<?php echo $c ?>,"<?php echo str_replace(array("\r\n", "\n"), '\r', str_replace('&#039;', "'", esc_attr($val))); ?>");
<?php   }
        $c++;
        unset($val);
        unset($col);
        unset($type);
    }
    if($edit_link and FrmProEntry::user_can_edit($entry, $form)){ ?>
data.setCell(<?php echo $i ?>,<?php echo $c ?>,'<a href="<?php echo esc_url(add_query_arg(array('frm_action' => 'edit', 'entry' => $entry->id), $permalink) . $anchor)  ?>"><?php echo addslashes($edit_link) ?></a>');
<?php }
    $i++;
    unset($entry);
} 
}else{ ?>
data.addRows(1);
<?php 
$c = 0;
foreach ($form_cols as $col){ 
    $val = ($c) ? '' : $no_entries; ?>
data.setCell(0,<?php echo $c ?>,'<?php echo $val ?>');
<?php
    $c++;
    }
}
?>
    var chart=new google.visualization.Table(document.getElementById('frm_google_table_<?php echo $form->id ?>')); 
    chart.draw(data,<?php echo json_encode($options) ?>);
}
</script>

<div class="form_results<?php echo ($style)? ' with_frm_style': ''; ?>" id="form_results<?php echo $form->id ?>">
<div id="frm_google_table_<?php echo $form->id ?>"></div></div>