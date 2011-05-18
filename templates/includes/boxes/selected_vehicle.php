<? 
if($_SESSION['Make_selected']!="" && $_SESSION['Make_selected']!='all')
{
?>

<div class="selected_vehicle">
  <div>
    <div> Your current selected vehicle is a <span>
      <?=$_SESSION['Make_selected']?>
      <?=$_SESSION['Model_selected']?>
      <?=$_SESSION['Year_selected']?>
      ! </span> Click here to see <a href="allprods.php"><u>all products</u></a> or our <u><a href="featured.php">find more items</a></u> for this selected vehicle.</div>
  </div>
</div>
<?
}
?>
