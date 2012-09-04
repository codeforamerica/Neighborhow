// Delete a row
delete_row = function(field_id,row){
	jQuery(function(){
		if (jQuery('#frm-table-' + field_id + ' tr').length == 2){ // header row and only one data row
			alert('Sorry, you must leave at least one row in this table.');
		}
		else{
			var data_exists = false;
			jQuery('#frm-table-' + field_id + ' tr.row-' + row).find('.table-cell').each(function(){
				if (jQuery(this).val() != "") data_exists = true;
			});
			if (!data_exists || confirm('Are you sure you wish to permanently delete this row?  This cannot be undone.')){
				jQuery('#frm-table-' + field_id + ' tr.row-' + row).remove();
				adjust_row_numbers(field_id);
				post_delete_row(field_id); // Deprecated.  Use the event below
				jQuery('#frm-table-' + field_id).trigger('delete_row',[field_id]);
			}
		}
	});
}

// Adjusts the class names to reflect new row numbering after adding or deleting rows
adjust_row_numbers = function(field_id){
	var row_num;
	jQuery('#frm-table-' + field_id + ' tr').each(function(){
		if (row_num == null){
			// skip the first row (column headers)
			row_num = 0;
		}
		else{
			// This searches for inputs and readjusts their name to match the new row numbering scenario
			jQuery(this).find('.table-cell').each(function(){  //input[name^=item_meta]
				var name = jQuery(this).attr('name');
				name = name.replace(/\[[0-9]+\]\[\]/,'[' + row_num + '][]');
				jQuery(this).attr('name',name);

				var id = jQuery(this).attr('id');
				id = id.replace(/_[0-9]+(_[0-9]+)$/,'_' + row_num + '$1');
				jQuery(this).attr('id',id);				
			});
// NEIGHBORHOW MOD
// - attach img upload fields to add/delete rows			
			jQuery(this).find('.nh-step-image INPUT').each(function(){ 
				jQuery(this).attr('name','step-image-' + row_num);				
			});			
// END NEIGHBORHOW MOD			
			// Now replace the javascript (for delete_row)
			jQuery(this).find('a').each(function(){
				var href = jQuery(this).attr('href');
				href = href.replace(/(delete_row\([0-9]+,)[0-9]+/,'$1'+row_num);
				jQuery(this).attr('href',href);
			});
			
			// Finally, need to reset the class for the row
			jQuery(this).get(0).className = jQuery(this).get(0).className.replace(/\brow-[0-9]+?\b/g, '');
			jQuery(this).addClass("row-" + row_num);

			row_num++;
		}
	});
}

var active_requests = new Array(); // = false;
add_row = function(field_id){
	if (active_requests[field_id] == undefined){
		// queue requests
		active_requests[field_id] = 0;
	}
	jQuery('#frmplus-add-row-'+field_id).addClass('loading');
    jQuery.ajax({
        type:"POST",
        url:ajaxurl,
        data:"action=frm_add_table_row&field_id="+field_id+"&row_num="+(jQuery('#frm-table-' + field_id + ' tr').length-1+active_requests[field_id]++),
        success:function(msg){
			active_requests[field_id]--;
			if (active_requests[field_id] == 0){
				jQuery('#frmplus-add-row-'+field_id).removeClass('loading');
			}
			jQuery('#frm-table-' + field_id + ' tr:last').after(msg);
			post_add_row(field_id,jQuery('#frm-table-' + field_id + ' tr:last')); // deprecated.  Use the event below
			jQuery('#frm-table-' + field_id).trigger('add_row',[field_id,jQuery('#frm-table-' + field_id + ' tr:last')]);
		}
    });
}

// post_add_row and post_delete row are deprecated.  Use the events 'add_row' and 'delete_row', triggered on the table.  
post_add_row = function(field_id,new_row){
	// Just a stub that can be overridden by another script
}
post_delete_row = function(field_id){
	// Just a stub that can be overridden by another script
}

// Make arrow keys work for navigation through a table
// Also, make it so a popup showing column/row headers appears on focus
jQuery(document).ready(function(){
	jQuery('.frm-table.use-arrow-keys').find('input').live('keydown',function(e){
		if (e.which >= 37 && e.which <= 40){
			var matches;
			var pattern = /_([0-9]+)_([0-9]+)$/;
			if (matches = this.id.match(pattern)){
				var row = parseInt(matches[1]); 
				var col = parseInt(matches[2]);
				var table = jQuery('#' + this.id).parents('.frm-table');
				var max_row = jQuery('#' + this.id).parents('.frm-table').find('tr').length - 2; // the -2 comes from 1 for the header row and 1 for the fact that we're 0-based
				var max_col = jQuery('#' + this.id).parents('tr').find('td input').length -1; // the -1 is for the fact that we're 0-based
				switch (e.which){
				case 37: // left arrow
					if (col > 0){
						col--;
					}
					else if(row > 0){
						col = max_col;
						row--;
					}
					break;
				case 38: // up arrow
					if (row > 0){
						row--;
					}
					else if (col > 0){
						col--;
						row = max_row;
					}
					e.preventDefault(); // prevent list of previously entered values showing up and confusing
					break;
				case 39: // right arrow
					if (col < max_col){
						col++;
					}
					else if (row < max_row){
						row++;
						col = 0;
					}
					break;
				case 40: // down arrow
					if (row < max_row){
						row++;
					}
					else if (col < max_col){
						col++;
						row = 0;
					}
					break;
				}
				if (row != parseInt(matches[1]) || col != parseInt(matches[2])){
					// Trigger the change event for the cell we just left
					jQuery(this).change();
					// need to reset the focus
					jQuery('#' + this.id.replace(pattern,'_'+row+'_'+col)).focus();
				}
			}
		}
	});
	
	jQuery('.frm-table.use-tooltips').find('input').live('focus',function(e){
		// first, make sure the tooltip element exists
		var id = jQuery(this).attr('id');
		if (jQuery('#table_header_tip.tip_'+id).length){
			// Already exists (for some reason this function gets hit many times, but we only want to execute once)
			return;
		}
		else{
			jQuery('#table_header_tip').remove();
			jQuery(this).after('<div id="table_header_tip" class="table_header_tip tip_'+id+'"></div>');
		}
		
		var TableHeaderTip = jQuery('#table_header_tip').html('');

		TableHeaderTip.css({'display':'none'});
		var TableElement = jQuery(this).parents('table');
		var matches = id.match(/^(.*_)([0-9]+)_([0-9]+)$/);
		var row = parseInt(matches[2]);
		var col = parseInt(matches[3]); 
		var Tip = '';
		TableElement.find('tbody tr th').each(function(i,el){
			if (i == row){
				Tip += jQuery(el).html();
			}
		});
		if (Tip == ''){
			// this is a table without row headings. So, use the value of the first input
			Tip += jQuery('#'+matches[1]+row+'_0').val();
		}
		TableElement.find('thead tr th').each(function(i,el){
			if (i == 0 && jQuery(el).html() == '&nbsp;'){
				col++; // the +1 is to allow for the blank cell in the top left
			}
			if (i == col){
				if (Tip != ''){
					Tip += " / ";
				}
				Tip += jQuery(el).html();
			}
		});
		TableHeaderTip.html(Tip);
		TableHeaderTip.css({
			'display':'block',
			'position':'relative'
		});
	}).live('blur',function(e){
		jQuery('#table_header_tip').remove();
	});
});

function frmplus_add_field_option(field_id){
	frm_add_field_option(field_id,ajaxurl);
}