function HtmlEncode(data){
	return data.replace(/&/g, '&amp;')
	    .replace(/"/g, '&quot;')
	    .replace(/'/g, '&#39;')
	    .replace(/</g, '&lt;')
	    .replace(/>/g, '&gt;');
}

function HtmlDecode(data){
	return data.replace(/&amp;/g, '&')
	    .replace(/&quot;/g, '"')
	    .replace(/&#39;/g, "'")
	    .replace(/&lt;/g, '<')
	    .replace(/&gt;/g, '>');
}

function setLoading(element, ctr){
	var div = $('<div class="loading"></div>');
	div.width(element.width());
	div.height(element.height());
	div.css('padding-top', element.css('padding-top'));
	div.css('padding-bottom', element.css('padding-bottom'));
	div.css('padding-left', element.css('padding-left'));
	div.css('padding-right', element.css('padding-right'));
	
	element.css('position', 'relative');
	element.append(div);
	
	if (ctr != undefined)
		$(ctr).addClass('hidden');
}

function removeLoading(element, ctr){
	$('.loading', element).remove();
	if (ctr != undefined)
		$(ctr).removeClass('hidden');
}

function getTagParent(mthis, tag){
	if (mthis.length <= 0) return null;
	var parent = mthis.parent();
	do{
		if (parent[0].nodeName.toLowerCase() == 'body'){
			return null;
		}else{
			if (tag.indexOf('#') != -1 && tag.substring(1) == parent.attr('id')){
				return parent;
			}else if (tag.indexOf('.') != -1 && parent.hasClass(tag.substring(1))){
				return parent;
			}else if(parent[0].tagName.toLowerCase() == tag.toLowerCase())
				return parent;
			}
		parent = parent.parent();
		
	}while(true);
	return null;
}



function formatButton(){
}

function dropdownlist(element){
	var obj = $(element);
	var parent = obj.parent();
	obj.find("li").live('click', function(){
		var rel = $(this).find("a").attr('rel');
		$('.dropdown-btn', parent).html($(this).find("a").html());
		obj.hide();
		parent.removeClass('active');
		$('input', parent).val(rel);
		return false;
	});
}

$(function(){
	showMessage();
	formatButton();
	
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	$( "#dialog-message" ).dialog({
		modal: true,
		position: 'top',
        draggable: true,
        resizable: false,
		buttons: {
			Ok: function() {
				$(this).dialog( "close" );
			}
		}
	});
	
	function showMessage(){
		if ($("#dialog-message").length > 0){
			$("#dialog-message").dialog("open");
		}
	}
		
	$.xPopupInit = function(id){		
		$( id ).dialog({
			autoResize: false,
		    //show: "clip",
		    //hide: "clip",
		    height: 408,
		    width: 787,
		    autoOpen: false,
		    modal: true,
		    draggable: false,
		    resizable: false,
		});
	}
	
	$.xPopupAutoSizeInit = function(id){		
		$( id ).dialog({
			autoResize: false,
			show: "clip",
			hide: "clip",
			height: 'auto',
		    width: 'auto',
			position: "top",
			autoOpen: false,
			modal: true,
			draggable: false,
			resizable: false,
		});
	}
	
	$.xPopupOpen = function(id){
		$(id).dialog('open');
	}
	
	$.xMessage = function(ob, msg){
		var obj = $(ob);
		if (obj.length > 0){
			obj.html(msg);
			obj.show();
			obj.fadeOut(1500, function(){
				$(this).hide;
			});
		}
	}
});
