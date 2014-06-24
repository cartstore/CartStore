											







</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<footer id="footer">
    <div class="footer-wrapper">
        <div class="row">
            <div class="col-sm-6 text">
            </div>

        </div>
    </div>
</footer>
</div>
</section>
</div>

<div class="navbar navbar-fixed-bottom hidden-xs" id="status">
    <div class="btn-toolbar">
        <div class="btn-group pull-right">
            <p>CartStore 7 Phoenix ©2006-2014 Adoovo Inc. NV USA
            </p>

        </div>
        <?php
          $whos_online_query = tep_db_query("select customer_id, full_name, ip_address, time_entry, time_last_click, last_page_url, http_referer, user_agent, session_id from " . TABLE_WHOS_ONLINE . ' order by time_last_click DESC');
  			$total_sess = tep_db_num_rows($whos_online_query);
        ?>
        <div class="btn-group viewsite"><a target="_blank" href="../../"><i class="icon-share-alt"></i> View Site</a></div>
        
        <div class="btn-group divider"></div>
        
        <div class="btn-group loggedin-users"><a href="./whos_online.php"><span class="badge"><?php echo $total_sess; ?></span> Visitors</a></div><div alt="0 Messages" title="" class="btn-group hasTooltip no-unread-messages" data-original-title="0 Messages"><a href="orders.php?status=1"><span class="badge"><?php echo tep_db_num_rows($orders_pending_query); ?></span> Pending </a></div>
            
            <div class="btn-group divider"></div>
                
            
                
   
        
        <div class="btn-group loggedin-users"><a href="#"><span class="badge">3</span> Low Stock</a></div>
        
        
     <div class="btn-group logout"><a href="./logoff.php"><i class="fa fa-sign-out"></i> Log out</a>
        </div>
    
        
        
        
        
        
        </div>
    </div>
</div>  

<!-- / jquery [required] -->
<script src="./templates/responsive-red/assets/jquery.min.js" type="text/javascript"></script>
<!-- / jquery mobile (for touch events) -->
<script src="./templates/responsive-red/assets/jquery.mobile.custom.min.js" type="text/javascript"></script>
<!-- / jquery migrate (for compatibility with new jquery) [required] -->
<script src="./templates/responsive-red/assets/jquery-migrate.min.js" type="text/javascript"></script>
<!-- / jquery ui -->
<script src="./templates/responsive-red/assets/jquery-ui.min.js" type="text/javascript"></script>
<!-- / jQuery UI Touch Punch -->
<script src="./templates/responsive-red/assets/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
<!-- / bootstrap [required] -->
<script src="./templates/responsive-red/assets/bootstrap.js" type="text/javascript"></script>
<!-- / modernizr -->
<script src="./templates/responsive-red/assets/modernizr.min.js" type="text/javascript"></script>
<!-- / retina -->
<script src="./templates/responsive-red/assets/retina.js" type="text/javascript"></script>
<!-- / theme file [required] -->
<script src="./templates/responsive-red/assets/theme.js" type="text/javascript"></script>
<!-- / END - page related files and scripts [optional] -->
<link href="//fonts.googleapis.com/css?family=Source+Sans Pro:200italic,200,300italic,300,400italic,400,600italic,600,700italic,700,900italic,900" rel="stylesheet" type="text/css">



<script src="<?php
echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>templates/jquery.init.local.js" type="text/javascript"></script>
<!--<script type="text/javascript" src="<?php
echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php
echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>ckfinder/ckfinder.js"></script>-->
 
<script type="text/javascript" src="<?php echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;?>redactor/redactor.min.js"></script>
<script type="text/javascript" src="<?php echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;?>redactor/fontcolor.js"></script>
<script type="text/javascript" src="<?php echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;?>redactor/fontfamily.js"></script>
<script type="text/javascript" src="<?php echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;?>redactor/fontsize.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".redactor").redactor({
			convertVideoLinks: true,
			imageUpload: 'categories.php?action=redactor-upload',
			imageUploadParam: 'cartstore_redactor_image',
			imageGetJson: 'categories.php?action=redactor-imageslist',
			uploadFields: {
				currentPage: '<?php echo basename($_SERVER['PHP_SELF']); ?>'
			},
			plugins: ['fontcolor','fontfamily','fontsize']
		});
	});
</script>
<script language="javascript" src="<?php
echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
?>includes/general.js"></script>



<script language="javascript" type="text/javascript">
<!--
    function popUp(url) {
        var winHandle = randomString();
        newwindow = window.open(url, winHandle, 'height=800,width=1000');
    }

    function randomString() {
        var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
        var string_length = 8;
        var randomstring = '';
        for (var i = 0; i < string_length; i++) {
            var rnum = Math.floor(Math.random() * chars.length);
            randomstring += chars.substring(rnum, rnum + 1);
        }
        return randomstring;
    }

    jQuery("form[name='search'] .dropdown-menu a").click(function() {
        $("form[name='search'] .dropdown-menu").find("a i").remove();
        $(this).append('<i class="fa fa-check"></i>');
        $("form[name='search']").attr('action', $(this).attr('data-target'));
    });
    var curSearchSel = jQuery("form[name='search'] .dropdown-menu").find("i.fa");
    jQuery(curSearchSel).parent().click();

    // -->
</script>

</body>
</html>