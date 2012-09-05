jQuery(document).ready(function($) {
//	$(".next-steps").hide();
	
	$('.addit').hide();
	


	$("#addstep2").click(function() {
		$('#step-container-2').show();
		$('#addstep2').hide();
	});	

	$("#addstep3").click(function() {
		$('#step-container-3').show();
		$('#addstep3').hide();
	});

	$('#removestep2').click(function() {
//		$('#step-container-2 input').val('');
//		$('#step-container-2 textarea').val('');
		
//		$("#step-image-2 input").replaceWith($("#step-image-2 input").clone(true));		
		
		
		$('#step-container-2').hide();		
		$('#addstep2').show();					
	});

	$('#removestep3').click(function() {
		$('#step-container-3 input').val('');
		$('#step-container-3 textarea').val('');
		$('#step-container-3 file').val('');
		$('#step-container-3').hide();	
		$('#addstep3').show();					
	});
});


function reset_html(id) {
    $('#'+id).html($('#'+id).html());
}


function resetFileInput(id) {
	var fld = document.getElementById(id);
	fld.form.reset();
	fld.focus();
}