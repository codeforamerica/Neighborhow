jQuery(document).ready(function($) {
	
	$(".nh-container-next").hide();

	$("#addstep2").click(function() {
//		$('#nh-container-2').show();
		$(this).next().show();		
		$('#addstep2').hide();
	});


/*	$(".next-steps").hide();

	$("#addstep2").click(function() {
		$('#step-container-2').show();
		$('#addstep2').hide();
	});	

	$("#addstep3").click(function() {
		$('#step-container-3').show();
		$('#addstep3').hide();
	});

	$('#removestep2').click(function() {
		$('#step-container-2 input').val('');
		$('#step-container-2 textarea').val('');
		$('#step-container-2').hide();
		$('#addstep2').show();						
	});

	$('#removestep3').click(function() {
		$('#step-container-3 input').val('');
		$('#step-container-3 textarea').val('');
		$('#step-container-3').hide();
		$('#addstep3').show();						
	});
*/	

});

