jQuery(document).ready(function($) {

	var steps = "<li class='elements' id='step-' style='padding:.5em 0 .5em 0;border-top:1px solid #666;border-bottom:1px solid #666;'><p>Step 2</p><div id='step-title-' class='step-title'><label class='field_label'>Step Title 2</label><input type='text' id='field_rooch6' name='item_meta[122]' value='' class='text'/></div><div id='step-description-' class='step-description'><label class='field_label'>Step Description 2</label><textarea name='item_meta[123]' id='field_drjf8t' rows='5'  class='textarea'></textarea> </div><div id='step-image-' class='step-image'><label class='field_label'>Step Image 2</label><input type='file' name='file124' id='field_ej0e65'  class='file'/><input type='hidden' name='item_meta[124]' value='' /></div><hr></li>";

	$('#step-2').hide();
	$('#step-3').hide();
	
	$("#copy").click(function(e) {
	
		var elements = $(".elements");
		var cnt = elements.length + 1;
		var removeBtn = "<a class='remove'>Remove this Step</a>";
		var removeImgBtn = "<a class='remove2'>Remove this Img</a>";	

		
		if ($('li#step-1').css('display') == 'none') {
			$('#step-1').show();
			$('#step-image-1').after(removeImgBtn);	
		}
		else {
//		elements.eq(0).clone().insertBefore(this)
//		$('.elements').next().insertBefore(this)
		$(this).append('.elements').next()		
		.find('li').attr('id', 'step-' + cnt)
//		$('.elements').next().show();
		$('p:last').html('Step ' + cnt);
		$('.elements:last').append(removeBtn);
		$('.elements:last').find('input[type=text]').val("");
		$('.elements:last').find('input[type=file]').val("");
		$('.elements:last').find('textarea').val("");
		$('.step-image:last').after(removeImgBtn);
		}
	});
	$('.remove').live('click', function() {
		var i = 1;
		$(this).closest('.elements').remove();
		$('p').each(function(index) {
			$(this).html('Step ' + i++);
		});              
	});

	
// STOP HERE
});

