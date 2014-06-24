<?php
$login_request = true;
$require_name = FALSE;

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $email_address = tep_db_prepare_input($_POST['email_address']);
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $log_times = $_POST['log_times'] + 1;
    if ($log_times >= 4) {
        $password_forgotten = true;
        tep_session_register('password_forgotten');
    }

    $check_admin_query = tep_db_query("select admin_id as check_id, admin_firstname as check_firstname, admin_lastname as check_lastname, admin_email_address as check_email_address from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
    if (!tep_db_num_rows($check_admin_query)) {
        $_GET['login'] = 'fail';
    } else {
        $check_admin = tep_db_fetch_array($check_admin_query);
        if ($require_name == TRUE && $check_admin['check_firstname'] != $firstname) {
            $_GET['login'] = 'fail';
        } else {
            $_GET['login'] = 'success';

            function randomize() {
                $salt = "ABCDEFGHIJKLMNOPQRSTUVWXWZabchefghjkmnpqrstuvwxyz0123456789";
                srand((double) microtime() * 1000000);
                $i = 0;
                while ($i <= 7) {
                    $num = rand() % 33;
                    $tmp = substr($salt, $num, 1);
                    $pass = $pass . $tmp;
                    $i++;
                }
                return $pass;
            }

            $makePassword = randomize();
            tep_mail($check_admin['check_firstname'] . ' ' . $check_admin['admin_lastname'], $check_admin['check_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $check_admin['check_firstname'], HTTP_SERVER . DIR_WS_ADMIN, $check_admin['check_email_address'], $makePassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            tep_db_query("update " . TABLE_ADMIN . " set admin_password = '" . tep_encrypt_password($makePassword) . "' where admin_id = '" . $check_admin['check_id'] . "'");
        }
    }
}
?>



<!DOCTYPE html>
<html class=" js no-touch localstorage svg">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>CartStore | Password</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="./templates/responsive-red/assets/light-theme.css" media="all" id="color-settings-body-color" rel="stylesheet" type="text/css">

        <link href="./templates/responsive-red/assets/bootstrap.css" media="all" rel="stylesheet" type="text/css">



        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
        <link href="//codeorigin.jquery.com/ui/1.10.3/themes/blitzer/jquery-ui.css" rel="stylesheet">



    </head>
    <body class="contrast-red login contrast-background" style="">
        <div class="middle-container">
            <div class="middle-row">
                <div class="middle-wrapper">
                    <div class="login-container-header">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="text-center">
                                        <img width="121" height="auto" src="./templates/responsive-red/assets/logo.png">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="login-container">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-4 col-sm-offset-4">


                                    <?php
                                    if ($_GET['login'] == 'success') {
                                        $success_message = TEXT_FORGOTTEN_SUCCESS;
                                    } elseif ($_GET['login'] == 'fail') {
                                        $info_message = '<div class="alert alert-danger">No Match for E-Mail Address.</div>';
                                    }
                                    ?>


                                    <?php
                                    if (tep_session_is_registered('password_forgotten')) {
                                        echo TEXT_FORGOTTEN_FAIL;
                                        ?>

                                        <?php
                                        echo ' ';
                                    } elseif (isset($success_message)) {
                                        echo '<div class="alert alert-success">The new password was sent to your email address. Please check your email and click back to login.</div>';
                                        ?>


                                        <?php
                                        echo ' ';
                                    } else {
                                        if (isset($info_message)) {
                                            echo $info_message;
                                        } else {
                                            echo tep_draw_hidden_field('log_times', '0');
                                        }
                                        ?>                   









                                        <h1 class="text-center title">Lost Password</h1>
                                        <?php echo tep_draw_form('login', FILENAME_PASSWORD_FORGOTTEN, 'action=process'); ?>

                                        <?php if ($require_name == TRUE): ?>
                                            <div class="form-group">
                                                <div class="controls with-icon-over-input">
                                                    <input value="" placeholder="First Name" class="form-control" data-rule-required="true" name="firstname" type="text">
                                                    <i class="icon-lock text-muted"></i>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <?php
                                        echo tep_draw_hidden_field('log_times', $log_times);
                                    }
                                    ?>

                                    <div class="form-group">
                                        <div class="controls with-icon-over-input">
                                            <input value="" placeholder="E-mail" class="form-control" data-rule-required="true" name="email_address" type="text">
                                            <i class="icon-user text-muted"></i>
                                        </div>
                                    </div>


                                    <button class="btn btn-block">
                                        Submit
                                    </button>



                                    </form>
                                    <div class="text-center">
                                        <hr class="hr-normal">
                                        <a href="login.php"><i class="fa fa-chevron-circle-left"></i> Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="login-container-footer">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="text-center">
                                        <a href="http://www.cartstore.com/" target=""_BLANK"> <i class="icon-user"></i> Need Support or Service? </a>
                                    </div>
                                </div>
                            </div>
                        </div>
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



        <script src="<?php
                                    echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
                                    ?>templates/jquery.init.local.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?php
                                    echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
                                    ?>ckeditor/ckeditor.js"></script>

        <script type="text/javascript" src="<?php
                                    echo(($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_ADMIN;
                                    ?>ckfinder/ckfinder.js"></script>

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
            // -->
        </script>

    </body>
</html>