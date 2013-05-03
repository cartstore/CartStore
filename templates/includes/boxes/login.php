<?php
  if (isset($_SESSION['customer_first_name'])) {
?>

<div class="module">
  <div>
    <div>
      <div>
        <h3>USER LOGIN</h3>
        <center>
          You are logged in as: <a href="account.php">
          <?php
      print($_SESSION['customer_first_name']);
?>
          </a>
        </center>
        <br />
        <a class="button" href="logoff.php">Log Out</a>
        <div class="clear"></div>
      </div>
    </div>
  </div>
</div>
<?php
      }else
      {
?>
<div class="module">
  <div>
    <div>
      <div>
        <h3>USER LOGIN</h3>
        <?php
          if (isset($_SESSION['customers_email_address']))
              print '<center><span class="pad5">You are logged in as ' . $_SESSION['customers_email_address'] . '<br /><br />



 <a class="button" href="logoff.php">Logout.</a></span></center>';
          else {
?>
        <div class="box">
          <?php  echo tep_draw_form ( 'login', tep_href_link ( FILENAME_LOGIN, 'action=process', 'SSL' ) ); ?>
            <label>eMail</label>
            <br />
            <input type="text" value="" class="inputbox" name="email_address" />
            <br />
            <label>Password</label>
            <br />
            <input type="password" value="" class="inputbox" name="password" />
            <br />
            <input name="" value="yes" alt="" type="checkbox" />
            Remember me
            <div class="clear"></div>
            <?php
				if(!tep_session_is_registered('customer_id')) {
					echo '<div id="social-login">' . $sociallogininterface . '</div>';
				}
			?>
            <input type="submit" value="Login" class="button" />
            <div class="clear"></div>
            <a href="password_forgotten.php">Forgot Password?</a><br />
            <a href="create_account.php">New User? Click here</a>
          </form>
        </div>
        <?php
          }
?>
      </div>
    </div>
  </div>
</div>
<?php
      }
?>
