function slidedown() {
  $(function() {
    $("#deslideup").click(function() { $.cookie('dont_show', 'true', { expires: 3650, path: '/', domain: '' }); });
    $('#slideup').slideUp("slow");
	
	$.fancybox.close()
  });
}

