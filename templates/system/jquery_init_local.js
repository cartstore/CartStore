jQuery(document).ready(function($) {
    
    
    
	
	/* Menu Slide JS  */

$(document).ready(function(){
    
    
    
    
    $('body').on('show', '.modal', function () {
$(this).css({ 'margin-top': window.pageYOffset - $(this).height() / 2, 'top': '50%' });
$(this).css({ 'margin-left': window.pageXOffset - $(this).width() / 2, 'left': '50%' });
});
    
    
  $(".menu-btn").on('click',function(e){
      e.preventDefault();
		
		//Check this block is open or not..
      if(!$(this).prev().hasClass("open")) {
        $(".header-cart").slideDown(400);
        $(".header-cart").addClass("open");
        $(this).find("i").removeClass().addClass("fa fa-chevron-up");
      }
      
      else if($(this).prev().hasClass("open")) {
        $(".header-cart").removeClass("open");
        $(".header-cart").slideUp(400);
        $(this).find("i").removeClass().addClass("fa fa-chevron-down");
      }
  });
  $(".fancybox").fancybox();


}); 

  


	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut();
		}
	}); 
	
	
	$('#pInfoTab a:first').tab('show') // Select first tab
	
	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});
	
	  	$('#myCarousel').carousel({
	interval: 10000
	})
        
         $('#accordion').collapse()
    
    $('#myCarousel').on('slid.bs.carousel', function() {
    	//alert("slid");
	});
    


		
		
		
$('.phoenix-input').phoenix()
  $('.create_account').submit(function(e){
    $('.phoenix-input').phoenix('remove')
  })



 $('select#country').phoenix('remove');
});

jQuery(".demo input:eq(toggler)").each(function($) {

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

 

 
	// Initialize the editor.

	// Callback function can be passed and executed after full instance creation.

 
	var options = $('div.demo').find('input:radio');
	$('.inputcheckbox').ready(function() {
		options.removeAttr('checked');
	});
});
