<?php
if(isset($_SESSION['Make_selected']) && $_SESSION['Make_selected']!='all')
{
?>

<div class="selected_vehicle">
  <div>
    <div> Your current selected vehicle is a <span>
      <?php echo $_SESSION['Make_selected'];?>
      <?php echo $_SESSION['Model_selected'];?>
      <?php echo $_SESSION['Year_selected'];?>
      ! </span> Click here to see <a href="allprods.php"><u>all products</u></a> or our <u><a href="<?php echo tep_href_link(FILENAME_ALLCATS,'','SSL'); ?>">find more items</a></u> for this selected vehicle.</div>
  </div>
</div>
<?php
}
?>