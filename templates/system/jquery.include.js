(function($){
/*
 * includeMany 1.0.0
 *
 * Copyright (c) 2009 Arash Karimzadeh (arashkarimzadeh.com)
 * Licensed under the MIT (MIT-LICENSE.txt)
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Date: Feb 19 2009
 */
$.include = function(urls,finaly){
	var luid = $.include.luid++;
	var onload = function(callback,data){
						if($.isFunction(callback))
							callback(data);
						if(--$.include.counter[luid]==0&&$.isFunction(finaly))
							finaly();
					}
	if(typeof urls=='object' && typeof urls.length=='undefined'){
		$.include.counter[luid] = 0;
		for(var item in urls)
			$.include.counter[luid]++;
		return $.each(urls,function(url,callback){$.include.load(url,onload,callback);});
	}
	urls = $.makeArray(urls);
	$.include.counter[luid] = urls.length;
	$.each(urls,function(){$.include.load(this,onload);});
}
$.extend(
	$.include,
	{
		luid: 0,
		counter: [],
		load: function(url,onload,callback){
			if(/.css$/.test(url))
				$.include.loadCSS(url,onload,callback);
			else if(/.js$/.test(url))
				$.getScript(url,function(){onload(callback)});
			else
				$.get(url,function(data){onload(callback,data)});
		},
		loadCSS: function(url,onload,callback){
			var css = $('<link>').appendTo($('head'))
								.attr({rel:'stylesheet',type:'text/css',href:url})
			$.browser.msie
				?css.get(0).onreadystatechange = 
						function(){
							if(this.readyState=='loaded' || this.readyState=='complete')
								onload(callback);
						}
				:onload(callback);//other browsers do not support it
		}
	}
);
//
})(jQuery);