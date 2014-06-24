<?php if($_COOKIE['Make_selected']!="" && $_COOKIE['Make_selected']!='all')
{
?>
<div class="selected_vehicle">
<div>
<div>
Your selected vehicle is <span>"<?php echo $_COOKIE['Make_selected'];?> <?php echo $_COOKIE['Model_selected'];?> <?php echo $_COOKIE['Year_selected'];?>"</span>, only showing parts compatbile with your vehicle.
</div>
</div>
</div>
<?php
}
?>