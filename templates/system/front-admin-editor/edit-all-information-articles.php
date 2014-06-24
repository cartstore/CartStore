<?php
	if (isset($_COOKIE['osCAdminID'])):
		$admin_check = tep_db_query("select value from " . TABLE_SESSIONS . " where sesskey = '" . $_COOKIE['osCAdminID'] . "' and expiry > '" . time() . "'");
		$is_admin = false;
		if (tep_db_num_rows($admin_check) > 0){
			$admin_info = tep_db_fetch_array($admin_check);
			$bits = explode(";",$admin_info['value']);
			$check = array();
			foreach ($bits as $pieces){
				list($k, $v) = explode("|",$pieces);
				$val = unserialize($v);
				$check[$k] = $val;
			}
			if ( (isset($check['login_id']) && $check['login_id'] > 0) && isset($check['login_email_address']) && ( isset($check['login_groups_id']) && $check['login_groups_id'] == 1))
				$is_admin = true;
		}
		if ($is_admin):
?>

        <!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

 
        <style type="text/css">
           #edit-all-information-articles.modal .modal-dialog {
                left: 20px;
                margin: 0;
                position: fixed;
                right: 20px;
                top: 20px;
                width: auto;
                height:auto;
                bottom:20px;
            }
        

           #edit-all-information-articles.modal .modal-content {
                height: 100%;
                border-radius: 0;
            }
          #edit-all-information-articles.modal .modal-body {
                min-height: 100%;
                padding:0px;

            }

           #edit-all-information-articles.modal.fade.in {
               
            }
        </style>




        <a data-target="#edit-all-information-articles" data-toggle="modal"><i class="fa fa-pencil"></i></a>
        
<div id="edit-all-information-articles" class="modal fade front-admin-editor" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" style="display: none;">

    
     <div class="modal-dialog">
    <div class="modal-content">
 
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3>Administrator Action</h3>	</div>	
        
        <div class="modal-body">



                <iframe src="manage/articles.php?selected_box=articles"  frameborder="0" style="position:absolute;top:0px;width:100%;height:100%;" height="100%" width="100%" id="information-page-edit">
                </iframe>
            </div>

        </div> </div> </div>




        <script type='text/javascript'>


$(function() {
	$(".modal, button[data-dismiss='modal']").click(function(){
		location.reload(true);
	});
         

   
        });
        </script>
<?php
	endif; // valid admin
endif; // osCAdminID     