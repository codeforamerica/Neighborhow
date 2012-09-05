jQuery(document).ready(function($) {

		
	// 	var step_image = '<input type=file name=step-image-' + cnt  '/>';
		var one = "<input type=file name=step-image-";
		var two = "/>";
	 	var step_image = one+counter+two;
//		$('#frm-table-146 TR.row-0').append(step_image);
		
//		$("div.nh-step-image-0").html(step_image);

	$('.nh-step-image INPUT').attr('name','step-image-0');	
	
	var counter = 1;
	$('.frmplus-add-row').click(function() {
		var i = 0;
		alert('row is '+ i);
//		$('.nh-step-image INPUT').attr('id','step-image-' + counter);
		$('.nh-step-image INPUT').each(function(index) {
			$(this).attr('name','step-image-' + i++);
			$( ).attr('name','step-image-' + i++);
		});
//		counter++;
	});


/*		var elements = $(".step");
		var cnt = elements.length;
		alert('length is ' + cnt);
		var removeBtn = "<button class='remove'>Remove this Step</button>";  
		var removeImgBtn = "<button class='remove2'>Remove this Img</button>";
		$("div.nh-step-image").html(step_image);
		elements.eq(0).clone().insertAfter('.special:last')
			.find('h5').attr('id', 'step_' + cnt)
/*			$('h5:last').html('Step ' + cnt);
			$('.elements:last').append(removeBtn);
			$('.elements:last').find('input[type=text]').val("");
			$('.elements:last').find('input[type=file]').val("");
			$('.elements:last').find('textarea').val("");
			$('.step_media:last').after(removeImgBtn);
*/	
			

	
// STOP HERE
});

