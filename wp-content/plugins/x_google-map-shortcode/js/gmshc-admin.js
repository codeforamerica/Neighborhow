/**
 * Google Map Shortcode 
 * Version: 3.1.1
 * Author: Alain Gonzalez
 * Plugin URI: http://web-argument.com/google-map-shortcode-wordpress-plugin/
*/

(function ($) {

	 $(window).load(function(){     
	 
		var iconSelect = "";
		
		$(".gmshc_icon,.gmshc_thumb").click(function(){
			gmshc_switchImg($(this)); 
		}).mouseover(function(){
			$(this).css({"border":"solid #BBBBBB 1px"})
		}).mouseout(function(){
			$(this).css({"border":"solid #F9F9F9 1px"})
		});         
		
		$(".insert_map").click(function(){		
			gmshc_add_map();
			parent.tb_remove();			
		});
		
		$(".gmshc_show").click(function(){
			var mapDiv = $("#gmshc_map");
			var refreshMap = $(".gmshc_refresh");
			var mapBtn = $(".gmshc_show");
			if (mapDiv.height() >1) {
				mapDiv.height("0");				
				mapBtn.text(mapBtn.attr("show"));
				refreshMap.hide();
				$("#iframe_sc").hide();
			} else {
				mapDiv.height("440");				
				mapBtn.text(mapBtn.attr("hide"));
				refreshMap.show();
				deploy_map();												
			}
			return false;
		});	
		
		$(".gmshc_refresh").click(deploy_map);		
			
		$("#windowhtml").change(function(){
			$("#gmshc_html_previews").html($(this).val());			
		});
		
		var winHtml = $("#windowhtml").val();
		
		$("#windowhtml").val($.trim(winHtml));

		
		$(".gmshc_list_icon, .gmshc_list_thumb").click(function(){	
		    $(".gmshc_box_close").trigger("click");	
			$(this).addClass("gmshc_active");	
			var pos = $(this).position();			
			var posLeft = pos.left;			
			var posTop = pos.top;
			
			var mapPos = $("#gmshc_map").position();
			var mapPosTop = mapPos.top;
			
			if ((posTop+200) > mapPosTop){
				posTop = mapPosTop - 180; 
			}
			
			var elem = "icon";
			if ($(this).hasClass("gmshc_list_thumb")) {
				elem = "thumb";
			}
			
			$('#gmshc_list_'+elem+'_cont').show(100,function(){
				var children = $(".gmshc_bx",this).children(".gmshc_"+elem);
				if (children.length == 0){				
					$('#gmshc_'+elem+'_cont')
						.children(".gmshc_"+elem)
						.clone()
						.appendTo('#gmshc_list_'+elem+'_cont .gmshc_bx')
						.removeClass("gmshc_selected")
						.click(function(){
							var imgSrc = $("img",this).attr("src");
							var attch = imgSrc;
							if (elem == "thumb") {
								attch = $("img",this).attr("attch");
							}
							$("div.gmshc_active img").attr("src",imgSrc);
							$("div.gmshc_active input").val(attch);			
					});						
				}
			}).css({"left":(posLeft+50)+"px","top":posTop+"px"});	
		
		});		

		
		$(".gmshc_box_close").click(function(){
			$("#gmshc_list_icon_cont, #gmshc_list_thumb_cont").hide();			
			$(".gmshc_list_icon, .gmshc_list_thumb").removeClass("gmshc_active");
			return false;
		});
	
	 });
	 
	 function deploy_map(){		 
		urlP = gmshc_generate_sc();
		var iframeUrl = $("#iframe_url").val()+"map=true&"+urlP[1];
		$("#iframe_sc").show().attr("src",iframeUrl);
		$("#gmshc_map").focus();
	 }

	function gmshc_switchImg(obj) {		
		var iconSrc = obj.children("img").attr("src");
		var attchID = obj.children("img").attr("attch");
		obj.siblings().removeClass('gmshc_selected');			
		obj.addClass('gmshc_selected');	
		var attr = iconSrc;
		if (typeof attchID != "undefined"){
			attr = attchID;
		}
			obj.siblings("input").val(attr);
	}
	
     function gmshc_add_map(){
		 
		var str = gmshc_generate_sc();        
		var win = window.dialogArguments || opener || parent || top;
		win.send_to_editor(str[0]);		
   
    }
	
	function gmshc_generate_sc(){
		
        var width = $("#width").val();
		var defaultWidth = $("#default_width").val();
        
		var height = $("#height").val();
		var defaultHeight = $("#default_height").val();
		
		var margin = $("#margin").val();
		var defaultMargin = $("#default_margin").val();
		
		var align = "";
		if($("#aleft").is(':checked')) align = "left"; 
		else if($("#acenter").is(':checked')) align = "center"; 
		else if ($("#aright").is(':checked')) align = "right"; 
		
		var defaultAlign = $("#default_align").val();				
        
		var zoom = $("#zoom").val();
		var defaultZoom = $("#default_zoom").val();
		
		var type = $("#type").val();
		var defaultType = $("#default_type").val();
		
		var focusPoint = $("#focus").val();
		var defaultFocusPoint = $("#default_focus").val();

		var focusType = $("#focus_type").val();
		var defaultFocusType= $("#default_focus_type").val();
		
		var module = $("#module").val();			
        
        str = "[google-map-sc";
		urlP = "";
		if (width != defaultWidth)
			str += " width=\""+width+"\"";
			urlP += "width="+width+"&";
		if (height != defaultHeight)
			str += " height=\""+height+"\"";
			urlP += "height="+height+"&";
		if (margin != defaultMargin)
			str += " margin=\""+margin+"\"";			
		if (align != defaultAlign)
			str += " align=\""+align+"\"";						
		if (zoom != defaultZoom)
			str += " zoom=\""+zoom+"\"";
			urlP += "zoom="+zoom+"&";
		if(type != defaultType)
			str += " type=\""+type+"\"";
			urlP += "type="+type+"&";	
		if(focusPoint != defaultFocusPoint)
			str += " focus=\""+focusPoint+"\"";
			urlP += "focus="+focusPoint+"&";
		if(focusType != defaultFocusType)
			str += " focus_type=\""+focusType+"\"";
			urlP += "focus_type="+focusType;
		if(module != "")
			str += " module=\""+module+"\"";
			urlP += "focus="+module+"&";											
		str +="]";
		
		return [str,urlP]; 		
	}
    
    function gmshc_delete_point(id,msg){
        var answer = confirm(msg);
		alert(answer);
        if (answer) {
        var width = $("#width").val();
        var height = $("#height").val();
        var zoom = $("#zoom").val();        
        var url = "?post_id=<?php echo $post_id ?>&tab=gmshc&delp="+id+"&width="+width+"&height="+height+"&zoom="+zoom;
        window.location = url;
        } else {
        return false;
        }	
    }
	
	
 
	 
})(jQuery);
	
	
	