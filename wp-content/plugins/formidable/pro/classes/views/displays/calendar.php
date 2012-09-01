<?php

for ($i=0; $i<($maxday+$startday); $i++){
    $end_tr = false;
    if(($i % 7) == 0 ) echo "<tr>\n";
    
    $day = $i - $startday + 1;
    
    //add classes for the day
    $day_class = '';
    
    //check for today
    if(isset($today) and $day == $today)
        $day_class .= ' frmcal-today';
        
    if((($i % 7) == 0) or (($i % 7) == 6))
        $day_class .= ' frmcal-week-end';
    
?>   
<td<?php echo (!empty($day_class)) ? ' class="'. $day_class .'"' : ''; ?>><div class="frmcal_date"><?php 
echo (isset($day_names[$i]) ? $day_names[$i] .' ' : '');
unset($day_class);
  
    if(($i < $startday)){
        echo '</div>';
    }else{
       echo $day; ?></div> <div class="frmcal-content">
<?php
        if(isset($daily_entries) and isset($daily_entres[$i]) and !empty($daily_entres[$i])){
            foreach($daily_entres[$i] as $entry){
                if(isset($used_entries) and isset($used_entries[$entry->id])){
                    echo '<div class="frm_cal_multi_'. $entry->id .'">'. $used_entries[$entry->id] .'</div>';
                }else{
                    echo $this_content = apply_filters('frm_display_entry_content', $new_content, $entry, $shortcodes, $display, $show);
                
                    if(isset($used_entries))
                        $used_entries[$entry->id] = $this_content;
                    unset($this_content);
                }
            }
        } 
    }
    ?></div>
</td>
<?php
    if(($i % 7) == 6 ){
        $end_tr = true;
        echo "</tr>\n";
    }
}

while($extrarows != 0) {
    echo "<td></td>\n";
    $extrarows--;
}

if(!$end_tr)
    echo '</tr>';
