<?php
  if (isset($_SESSION['customer_first_name'])) {
?>

<div class="block block-cart">
<div class="block-title">
<strong>
<span>Login</span>
</strong>
</div>

	
	
<div class="block-content"> 
        <center>
       <p> <i class="fa fa-users"></i> You are logged in as: <a href="account.php">
          <?php
      print($_SESSION['customer_first_name']);
?>
          </a>
      
       
        <a class="btn btn-info btn-sm" href="logoff.php">Log Out</a>  </p>  </center>
        
</div>

    </div>  



<?php
      }else
      {
?>

<div class="block">
<div class="block-title">
<strong>
<span>Login</span>
</strong>
</div>

	<div class="block-content">       <?php
          if (isset($_SESSION['customers_email_address']))
              print '<center><span class="pad5">You are logged in as ' . $_SESSION['customers_email_address'] . '<br /><br />



 <a class="btn btn-default btn-sm" href="logoff.php">Logout.</a></span></center>';
          else {
?>
        
          <?php  echo tep_draw_form ( 'login', tep_href_link ( FILENAME_LOGIN, 'action=process', 'SSL' ) ); ?>
            <label>eMail</label>
           
            <div class="input-group margin-bottom-sm">
<span class="input-group-addon"><i class="icon-envelope"></i></span>
           
            <input type="text" value="" class="form-control" name="email_address" />
        </div>
        
        
        
            <label>Password</label>
       
        <div class="input-group">
<span class="input-group-addon"><i class="icon-lock"></i></span>
           
            <input type="password" value="" class="form-control" name="password" />
            
             </div>
            
            
           <p>
            <input name="" value="yes" alt="" type="checkbox" />
            Remember me</p>
            <div class="clear"></div>
            <?php
				if(!tep_session_is_registered('customer_id')) {
					echo '<div id="social-login">' . $sociallogininterface . '</div>';
				}
			?>
            <input type="submit" value="Login" class="btn btn-primary btn-sm" />
            <div class="clear"></div><hr>
        <span class="pull-right">  <a href="password_forgotten.php"> Forgot Password?</a> <br />
             <a href="create_account.php"> New User?</a> </span> <div class="clear"></div>
          </form>
    
        <?php
          }
?>
    </div> </div>
<?php
      }
?>
