<?php
?>

<div class="mainBlock">
  <div class="stepsBox">
    <ol>
      <li>Database Server</li>
      <li>Web Server</li>
      <li style="font-weight: bold;">Online Store Settings</li>
      <li>Finished!</li>
    </ol>
  </div>
  <h1>New Installation</h1>
  <p>This web-based installation routine will correctly setup and configure CartStore to run on this server.</p>
  <p>Please follow the on-screen instructions that will take you through the database server, web server, and store configuration options. If help is needed at any stage, please consult the documentation or seek help at the community support forums.</p>
</div>
<div class="contentBlock">
  <div class="infoPane">
    <h3>Step 3: Online Store Settings</h3>
    <div class="infoPaneContents">
      <p>Here you can define the name of your online store and the contact information for the store owner.</p>
      <p>The administrator username and password are used to log into the protected administration tool section.</p>
    </div>
  </div>
  <div class="contentPane">
    <h2>Online Store Settings</h2>
    <form name="install" id="installForm" action="install.php?step=4" method="post">
      <table border="0" width="99%" cellspacing="0" cellpadding="5" class="inputForm">
        <tr>
          <td class="inputField"><?php
  echo 'Store Name<br />' . osc_draw_input_field('CFG_STORE_NAME', null, 'class="text"');
?></td>
          <td class="inputDescription">The name of the online store that is presented to the public.</td>
        </tr>
        <tr>
          <td class="inputField"><?php
  echo 'Store Owner Name<br />' . osc_draw_input_field('CFG_STORE_OWNER_NAME', null, 'class="text"');
?></td>
          <td class="inputDescription">The name of the store owner that is presented to the public.</td>
        </tr>
        <tr>
          <td class="inputField"><?php
  echo 'Store Owner E-Mail Address<br />' . osc_draw_input_field('CFG_STORE_OWNER_EMAIL_ADDRESS', null, 'class="text"');
?></td>
          <td class="inputDescription">The e-mail address of the store owner that is presented to the public.</td>
        </tr>
        <tr>
          <td class="inputField"><?php
  echo 'Administrator Password<br />' . osc_draw_input_field('CFG_ADMINISTRATOR_PASSWORD', null, 'class="text"');
?></td>
          <td class="inputDescription">The password to use for the administrator account.</td>
        </tr>
      </table>
      <p align="right">
        <input type="image" src="images/button_continue.gif" border="0" alt="Continue" id="inputButton" />
        &nbsp;&nbsp;<a href="index.php"><img src="images/button_cancel.gif" border="0" alt="Cancel" /></a></p>
      <?php
  reset($_POST);
  while (list($key, $value) = each($_POST)) {
      if (($key != 'x') && ($key != 'y')) {
          if (is_array($value)) {
              for ($i = 0, $n = sizeof($value); $i < $n; $i++) {
                  echo osc_draw_hidden_field($key . '[]', $value[$i]);
              }
          } else {
              echo osc_draw_hidden_field($key, $value);
          }
      }
  }
?>
    </form>
  </div>
</div>