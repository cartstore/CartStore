$(document).ready(function() {
               
   $(".button").button();
   $("#accordion").accordion();
      $("#dialog-message").dialog({
      modal: true,
      buttons: {
        Ok: function() {
          $(this).dialog('close');
        }
      }
    });

 $('#menu ul').superfish();   


animatedcollapse.addDiv('quanity_pb', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('auto_parts', 'fade=1,speed=200,persist=1,hide=1')
animatedcollapse.addDiv('meta_tags', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('vendor', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('price_opts', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('shipping_opts', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('attr_opts', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('image_opts', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('desc_opts', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('extrafields', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.addDiv('general_opts', 'fade=1,speed=700,persist=1,hide=1')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
  //$: Access to jQuery
  //divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
  //state: "block" or "none", depending on state
}

animatedcollapse.init()

  
    
  
      $("a[rel=lightbox-page]").fancybox({
        'titleShow'    : false
      });

      $("a[rel=lightbox]").fancybox({
        'transitionIn'    : 'none',
        'transitionOut'    : 'none',
        'titlePosition'   : 'over',
        'titleFormat'    : function(title, currentArray, currentIndex, currentOpts) {
          return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
        }
      });

    

     
    });