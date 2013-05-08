$(".demo input:eq(toggler)").each(function() {
	$(this).click(function(evt) {
		var divID = '#' + $(this).val();
		$(divID).toggle();
	});
	$("#dialog-message").dialog({
		modal : true,
		buttons : {
			Ok : function() {
				$(this).dialog('close');
			}
		}

	});

	$("#container a[href][title]").qtip({
		style : {
			tip : {// Now an object instead of a string
				corner : 'topLeft', // We declare our corner within the object using the corner sub-option
				color : '#727272',
				size : {
					x : 20, // Be careful that the x and y values refer to coordinates on screen, not height or width.
					y : 8 // Depending on which corner your tooltip is at, x and y could mean either height or width!
				}
			}
		}
	});

});
function popup() {
	$(function() {
		if($.cookie('dont_show') == null) {
			$("a[rel=popup]").trigger("click");
		}
	});
}

$(document).bind('pageinit',function() {
	var config = {
		toolbar : [['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'], ['UIColor']]
	};
	var myPhotoSwipe = $("#gallery a").photoSwipe({ enableMouseWheel: false , enableKeyboard: false });
	$(".button, .button").button();
	//$("#tabs").tabs();
	//$("#datepicker").datepicker();
	// Initialize the editor.
	// Callback function can be passed and executed after full instance creation.
	$('a[rel="ckeditor"]').ckeditor(config);
	var options = $('div.demo').find('input:radio');
	$('.inputcheckbox').ready(function() {
		options.removeAttr('checked');
	});
});


