$(document).ready(function() {
	
	


	$("a[rel=day_view]").fancybox({

		'titleShow' : true,

		'transitionIn' : 'none',

		'transitionOut' : 'none'

	});

	$("a[rel=44day_view]").fancybox({

		'titlePosition' : 'over'

	});

	$("a[rel=lightbox]").fancybox({

		'transitionIn' : 'none',

		'transitionOut' : 'none',

		'titlePosition' : 'over',

		'titleFormat' : function(title, currentArray, currentIndex, currentOpts) {

			return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';

		}
		
		
		

		
		
		
	});

	

});

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

$(function() {

	var config = {

		toolbar : [['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'], ['UIColor']]

	};

	$(".button, .button").button();

	$("#tabs").tabs();

	$("#datepicker").datepicker();

	// Initialize the editor.

	// Callback function can be passed and executed after full instance creation.

	$('a[rel="ckeditor"]').ckeditor(config);

	var options = $('div.demo').find('input:radio');
	$('.inputcheckbox').ready(function() {
		options.removeAttr('checked');
	});
});
