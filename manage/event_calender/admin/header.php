<link rel="stylesheet" href="event_calender/admin/stylesheet.css" />
<link rel="stylesheet" media="screen" type="text/css" href="event_calender/admin/datepicker.css" />
<script type="text/javascript" src="event_calender/admin/js/jquery-1.3.2.js"></script>
<script type="text/javascript" src="event_calender/admin/js/ui.core.js"></script>
<script type="text/javascript" src="event_calender/admin/js/ui.datepicker.js"></script>

<script type="text/javascript">
	$(function() {
		$("#datepicker").datepicker();
	});
</script> 

<!-- Load TinyMCE -->
<script type="text/javascript" src="event_calender/admin/js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
	$().ready(function() {
		$('textarea.tinymce').tinymce({
			// Location of TinyMCE script
			script_url : 'event_calender/admin/js/tiny_mce/tiny_mce.js',

			// General options
			theme : "advanced",
			plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,link,unlink,anchor,image,cleanup,help,code,|,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,cite,abbr,acronym,del,ins,attribs|,outdent,indent,blockquote,|,undo,redo,",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : false,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",
			
		});
	});
</script>
<!-- /TinyMCE --> 
 
</head>
<body>
 <a href="index.php"><h2>Calendar Control Panel</h2></a>
  

