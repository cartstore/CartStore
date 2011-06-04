<?php
include_once 'includes/classes/facebook.php';
include_once "fbconnect.php";
if ($fbme){
    $param  =   array(
        'method'     => 'users.getinfo',
        'uids'       => $fbme['id'],
        'fields'     => 'birthday_date, locale',
        'callback'   => ''
    );
    try{
        $info           =   $facebook->api($param);
    }
    catch(Exception $o){
        error_log("Legacy Api Calling Error!");
    }
}
  if ($_SESSION['customer_first_name'] != "") {
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
          if ($_SESSION['customers_email_address'] != '')
              print '<center><span class="pad5">You are logged in as ' . $_SESSION['customers_email_address'] . '<br /><br />



 <a class="button" href="logoff.php">Logout.</a></span></center>';
          else {
?>
        <div class="box">
          <form name="login" action="login.php?action=process" method="post">
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
            <input type="submit" value="Login" class="button" />
            <div class="clear"></div>
<div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?php echo $fbconfig['appid' ]; ?>', status: false, cookie: true, xfbml: true});

                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                    logout();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());

            function login(){
                document.location.href = "login.php";
            }

            function logout(){
                document.location.href = "logoff.php";
            }

            function createAccount(){
                document.location.href = "create_account.php";
            }
       </script>
    <p class="fb-login-btn">
        <fb:login-button autologoutlink="true" perms="email,offline_access,user_birthday,user_location,user_work_history,user_religion_politics,user_relationships">Login with Facebook</fb:login-button>
    </p>
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
