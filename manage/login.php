<?php
$login_request = true;
require('includes/application_top.php');

$action = (isset($_GET['action']) ? $_GET['action'] : '');
// prepare to logout an active administrator if the login page is accessed again
if (tep_session_is_registered('login_id')) {
    $action = 'logoff';
}

if (tep_not_null($action)) {
    switch ($action) {
        case 'process':
            $email_address = tep_db_prepare_input($_POST['email_address']);
            $password = tep_db_prepare_input($_POST['password']);

            $check_admin_query = tep_db_query("select admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum from " . TABLE_ADMIN . " where admin_email_address = '" . tep_db_input($email_address) . "'");
            if (!tep_db_num_rows($check_admin_query)) {
                $_GET['login'] = 'fail';
            } else {
                $check_admin = tep_db_fetch_array($check_admin_query);

                if (!tep_validate_password($password, $check_admin['login_password'])) {
                    $_GET['login'] = 'fail';
                } else {
                    if (tep_session_is_registered('password_forgotten')) {
                        tep_session_unregister('password_forgotten');
                    }
                    $login_id = $check_admin['login_id'];
                    $login_groups_id = $check_admin['login_groups_id'];
                    $login_firstname = $check_admin['login_firstname'];
                    $login_email_address = $check_admin['login_email_address'];
                    $login_logdate = $check_admin['login_logdate'];
                    $login_lognum = $check_admin['login_lognum'];
                    $login_modified = $check_admin['login_modified'];
                    $clone = 1;
                    tep_session_register('login_email_address');
                    tep_session_register('login_id');
                    tep_session_register('login_groups_id');
                    tep_session_register('login_firstname');
                    tep_session_register('clone');
                    if ($login_lognum % 50 == 0) {
                        tep_redirect(tep_href_link(FILENAME_TERMS_CONDITIONS));
                    }

                    tep_db_query("update " . TABLE_ADMIN . " set admin_logdate = now(), admin_lognum = admin_lognum+1 where admin_id = '" . $login_id . "'");
                    if (isset($_REQUEST['pma'])) {
                        tep_redirect("pmya/index.php?server=1");
                    }
                    if (tep_session_is_registered('redirect_origin')) {
                        $page = $redirect_origin['page'];
                        $get_string = '';

                        if (function_exists('http_build_query')) {
                            $get_string = http_build_query($redirect_origin['get']);
                        }

                        tep_session_unregister('redirect_origin');

                        tep_redirect(tep_href_link($page, $get_string));
                    } else {
                        if (($login_lognum == 0) || !($login_logdate) || ($login_email_address == 'admin@localhost') || ($login_modified == '0000-00-00 00:00:00')) {
                            tep_redirect(tep_href_link(FILENAME_ADMIN_ACCOUNT));
                        } else {
                            tep_redirect(tep_href_link(FILENAME_ORDERS));
                        }
                    }
                }
            }
            break;
        case 'logoff':
            tep_session_unregister('login_email_address');
            tep_session_unregister('login_id');
            tep_session_unregister('login_groups_id');
            tep_session_unregister('login_firstname');
            if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {
                tep_session_register('auth_ignore');
                $auth_ignore = true;
            }
            tep_redirect(tep_href_link(FILENAME_DEFAULT));
            break;
    }
}
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LOGIN);
?>



<!DOCTYPE html>
<html class=" js no-touch localstorage svg">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>CartStore | Login</title>
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
                                        <img width="220" height="auto" src="./templates/responsive-red/assets/logo.png">
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
                                    if ($_GET['login'] == 'fail') {
                                        $info_message = '<div class="alert alert-danger">' . TEXT_LOGIN_ERROR . '</div>';
                                    }
                                    if (isset($info_message)) {
                                        ?>
                            
                                            <?php
                                            echo $info_message;
                                            ?>
                                   
                                        <?php
                                    } else {
                                        
                                    }
                                    ?>


                                    <h1 class="text-center title">Sign in</h1>
<?php
echo tep_draw_form('login', FILENAME_LOGIN, 'action=process');
?>
                                    <div class="form-group">
                                        <div class="controls with-icon-over-input">
                                            <input value="" placeholder="E-mail" class="form-control" data-rule-required="true" name="email_address" type="text">
                                            <i class="icon-user text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="controls with-icon-over-input">
                                            <input value="" placeholder="Password" class="form-control" data-rule-required="true" name="password" type="password">
                                            <i class="icon-lock text-muted"></i>
                                        </div>
                                    </div>
                                    <div class="checkbox">
                                        <label for="remember_me">
                                            <input id="remember_me" name="remember_me" type="checkbox" value="1">
                                            Remember me </label>
                                    </div>
                                    <button class="btn btn-block">
                                        Sign in
                                    </button>


<?php
if (isset($_REQUEST['phpMyAdmin'])) {
    echo '<input type="hidden" name="pma" value="' . $_REQUEST['phpMyAdmin'] . '" />';
}
?>
                                    </form>
                                    <div class="text-center">
                                        <hr class="hr-normal">
                                        <a href="password_forgotten.php">Forgot your password?</a>
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