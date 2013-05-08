<?php
  if (isset($_SESSION['customer_first_name'])) {
?>
<div class="well">
<li class="nav-header">Login</li>

        <center>
          You are logged in as: <a href="account.php">
          <?php
      print($_SESSION['customer_first_name']);
?>
          </a>
        </center>
       
        <a class="btn button" href="logoff.php">Log Out</a>
        
</div>
<?php
      }else
      {
?>
<div class="well">
<li class="nav-header">Login</li>
        <?php
          if (isset($_SESSION['customers_email_address']))
              print '<center><span class="pad5">You are logged in as ' . $_SESSION['customers_email_address'] . '<br /><br />



 <a class="btn button" href="logoff.php">Logout.</a></span></center>';
          else {
?>
        
          <?php  echo tep_draw_form ( 'login', tep_href_link ( FILENAME_LOGIN, 'action=process', 'SSL' ) ); ?>
            <label>eMail</label>
           
            <div class="input-prepend">
<span class="add-on"><i class="icon-envelope"></i></span>
           
            <input type="text" value="" class="inputbox" name="email_address" style="width:85%;" />
        </div>
        
        
        
            <label>Password</label>
       
        <div class="input-prepend">
<span class="add-on"><i class="icon-lock"></i></span>
           
            <input type="password" value="" class="inputbox" name="password" style="width:85%;" />
            
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
            <input type="submit" value="Login" class="btn button" />
            <div class="clear"></div>
            <a href="password_forgotten.php">Forgot Password?</a><br />
            <a href="create_account.php">New User? Click here</a>
          </form>
    
        <?php
          }
?>
    </div>
<?php
      }
?>
