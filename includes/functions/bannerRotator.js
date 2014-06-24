/*
  BANNER ROTATOR PLUGIN
  This was developed by BEACON9 (http://beacon9.ca).
  It is an open source jQuery plugin which is available for free download from our site.
  You have our full permission to use this for whatever you want, just keep this notice at the top of the script
  AND let us know how you use it.
  
  to use this script you will need jquery
  include it in the <head> part of your site from Google (e.g. )
  
  then underneath it call the plugin like this...
  
  <script type="text/javascript">
    $(document).ready(function(){ //stuff to run when page is loaded
      bannerRotator('the list item  you want the script to work on', 'how long a transition should last', 'how long to wait before scrolling');
    });
  </script>
  
*/

function bannerRotator(selector, scrollTime, pauseTime){
  //default values of scroll time and pause time if nothing is set
  if (scrollTime == null){
    scrollTime=400;
  }
  if(pauseTime== null){
    pauseTime=5000;
  }
  
  $(selector+" li:first").css("display", "block"); //show the first list item
  $(selector+" li").each(function( intIndex ){
     $(this).attr('rel', (intIndex+1));   //add the list position to the rel of each of our nav items
  });

 //create navigation buttons for each banner in the list
  var count = $(selector+" li").size(); //get total number of list items
  var i = 1;
  while(i <= count){
	if(i == $(selector+" li:visible").attr('rel')){  //if its the nav item that belongs to the visible image, mark it as the active nav item
      $('#bannerNav').append("<a class='active' rel='"+i+"' href='#'></a> ");
	}
	else{
	  $('#bannerNav').append("<a rel='"+i+"' href='#'></a> ");
	}
	i++;
  }
  
  scrollImages(count, selector, scrollTime, pauseTime); //start the images scrolling
  
  //handle navigation by clicking
  $("#bannerNav a").click(function () {
    $("#bannerNav a.active").removeClass('active');									
	$(this).addClass('active'); //move the active nav item to this item

	var currentClassName = $(selector+" li:visible").attr('rel');
	var nextClassName = $(this).attr('rel');
	var storedTimeoutID = $("#bannerNav").attr('timeoutID');

	clearTimeout(storedTimeoutID);//stop the images from looping when a nav button is pressed
	$("span.pause").hide();
	$("span.play").show();
	
	if( nextClassName != currentClassName ){ //if they click on the button for the image they are already viewing... do nothing.	
	  $(selector+" li:visible").fadeOut(scrollTime);
	  $(selector+" li[rel="+nextClassName+"]").fadeIn(scrollTime);
	}
	return false; //stop the link from going to where the href attribute tells it
  });
  
  //print a pause/play button
  $('#bannerNav').append("<span class='pause'></span> ");
  $('#bannerNav').append("<span href='#' class='play' style='display:none;'></span> ");
  
  //stop the images looping on pause click
  $("span.pause").click(function () { 
    var storedTimeoutID = $("#bannerNav").attr('timeoutID');
	clearTimeout(storedTimeoutID);
	$("span.pause").hide();
	$("span.play").show();
  });
  
  //start the images looping on play click
  $("span.play").click(function () { 
    scrollImages(count, selector, scrollTime, pauseTime);
	$("span.play").hide();
	$("span.pause").show();
  });
}

function scrollImages(count, selector, scrollTime, pauseTime){
  currentClass = $(selector+" li:visible").attr('rel'); //get the list position of the image that we save to the rel attribute on page load
  nextClass = $(selector+" li:visible").attr('rel'); //open a new variable for the next class
  if (currentClass == count ){ nextClass=1; } //if you've reached the end of the images... start from number 1 again
  else{ nextClass++; } //if not just add one to the last number
  var timeout = setTimeout(function(){
    $(selector+" li[rel="+currentClass+"]").fadeOut(scrollTime) //fade out old image
	$("#bannerNav a.active").removeClass('active'); //remove active class from our nav
	$("#bannerNav a[rel="+nextClass+"]").addClass('active'); //add new active class to the next nav item
	$(selector+" li[rel="+nextClass+"]").fadeIn(scrollTime, scrollImages(count, selector, scrollTime, pauseTime)) //fade in the new image and start the loop again
  }, pauseTime);
  $("#bannerNav").attr('timeoutID', timeout); //save the timeout id as an attribute of that LI so we can cancel the loop later if a nav button is pressed
  $(selector+" li[rel="+currentClass+"]").animate({opacity : 1.0}, scrollTime, function(){ timeout }); //hold the image for as long as our scroll time is set for
}