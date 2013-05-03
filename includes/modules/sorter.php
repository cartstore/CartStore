<?php

if (is_numeric($_REQUEST['cPath']))
{



//price ranges
$from0=1;
$to0=100;

$from1=100;
$to1=500;

$from2=500;
$to2=1000;

$from3=1000;
$to3=3000;

$from4=3000;
$to4=0;


$current_category_id=$_REQUEST['cPath'];

$extra_fields_query = tep_db_query("select products_extra_fields_id, products_extra_fields_name from products_extra_fields where products_extra_fields_status=1 order by products_extra_fields_order asc ");
while ($extra_field=tep_db_fetch_array($extra_fields_query))
{
 $extra_fields[$extra_field['products_extra_fields_id']]=$extra_field['products_extra_fields_name'];
}




echo "							<div class=\"module sorter\"><div><div><div>
               
		
<script language=javascript>
function checkFilters()
{
 if (document.getElementById('p0').checked)
  {
   document.getElementById('pfrom0').value=$from0;
   document.getElementById('pto0').value=$to0;
  }
 if (document.getElementById('p1').checked)
  {
   document.getElementById('pfrom1').value=$from1;
   document.getElementById('pto1').value=$to1;
  }

 if (document.getElementById('p2').checked)
  {
   document.getElementById('pfrom2').value=$from2;
   document.getElementById('pto2').value=$to2;
  }

 if (document.getElementById('p3').checked)
  {
   document.getElementById('pfrom3').value=$from3;
   document.getElementById('pto3').value=$to3;
  }
 if (document.getElementById('p4').checked)
  {
   document.getElementById('pfrom4').value=$from4;
   document.getElementById('pto4').value=$to4;
  } 
var i=0;
";

foreach ($extra_fields as $extra_id => $extra_name)
{
 echo " 
  while (document.getElementById('checkbox_".$extra_id."_'+i))
  {
   if (document.getElementById('checkbox_".$extra_id."_'+i).checked)
    {
     if (document.getElementById('extra_".$extra_id."_'+i).value=='')
     {
      document.getElementById('extra_".$extra_id."_'+i).value=document.getElementById('label_".$extra_id."_'+i).firstChild.nodeValue;
     }
    }
   i++;
  }

i=0;
";
}

echo "
}
</script>";


echo '
					<form id=filterform method=GET action="advanced_search_result.php">
					<input name="filter" type=hidden value="1">
					<input name="cPath" type=hidden value="'.(int) $current_category_id.'">
					<input id=pfrom0 name=pfrom0 type=hidden value="">
					<input id=pfrom1 name=pfrom1 type=hidden value="">
					<input id=pfrom2 name=pfrom2 type=hidden value="">
					<input id=pfrom3 name=pfrom3 type=hidden value="">
					<input id=pfrom4 name=pfrom4 type=hidden value="">

					<input id=pto0 name=pto0 type=hidden value="">
					<input id=pto1 name=pto1 type=hidden value="">
					<input id=pto2 name=pto2 type=hidden value="">
					<input id=pto3 name=pto3 type=hidden value="">
					<input id=pto4 name=pto4 type=hidden value="">


						 
						<h3>SORT BY</h3>
						<h4>Price</h4>
										';
$checked="";
							if (is_numeric($_GET['pfrom0'])) {$checked="checked";} else {$checked="";}
echo '							<input class="checkbox"  '.$checked.' id=p0 type="checkbox"><label>Under $'.$to0.'</label><div class="clear"></div>';
							if (is_numeric($_GET['pfrom1'])) {$checked="checked";} else {$checked="";}
echo '							<input class="checkbox"  '.$checked.' id=p1 type="checkbox"><label>$'.$from1.' - $'.$to1.'</label><div class="clear"></div>';
							if (is_numeric($_GET['pfrom2'])) {$checked="checked";} else {$checked="";}
echo '							<input class="checkbox"  '.$checked.' id=p2 type="checkbox"><label>$'.$from2.' - $'.$to2.'</label><div class="clear"></div>';
							if (is_numeric($_GET['pfrom3'])) {$checked="checked";} else {$checked="";}
echo '							<input class="checkbox"  '.$checked.' id=p3 type="checkbox"><label>$'.$from3.' - $'.$to3.'</label><div class="clear"></div>';
							if (is_numeric($_GET['pfrom4'])) {$checked="checked";} else {$checked="";}
echo '							<input class="checkbox"  '.$checked.' id=p4 type="checkbox"><label>Over $'.$from4.'</label><div class="clear"></div>';
echo '						';


$extra_values=array();



if (!empty($extra_fields))
{
 foreach ($extra_fields as $extra_id => $extra_name)
 {
   $i=0;
   $count_query = tep_db_query("select count(*) as cnt from products_to_products_extra_fields ptpef, products_to_categories ptc where ptpef.products_id=ptc.products_id and ptc.categories_id=".(int)$current_category_id." and products_extra_fields_id=".$extra_id." ");
   $count=tep_db_fetch_array($count_query);
   if ($count['cnt']!=0)
   {
   echo "<h4>".$extra_name."</h4>";
   echo "";
   
   $extra_query = tep_db_query("select distinct ptpef.products_extra_fields_value as extra_value from products_to_products_extra_fields ptpef, products_to_categories ptc where ptpef.products_id=ptc.products_id and ptc.categories_id=".(int)$current_category_id." and products_extra_fields_id=".$extra_id." order by extra_value");
    while ($extra=tep_db_fetch_array($extra_query))
     {  
      echo "<input type=hidden id='extra_".$extra_id."_$i' name='extra_".$extra_id."[$i]' value=''>";
      $checked="";
      if (isset($_GET['extra_'.$extra_id]))
      {
	  
	  if($_GET['extra_'.$extra_id] !='Array')
	{
      foreach ($_GET['extra_'.$extra_id] as $extra_value)
      {
       if ($extra_value==$extra['extra_value'])
       { 
        $checked="checked";
       }
      }
	  }
      }
      echo '<input class="checkbox"  '.$checked.'  id="checkbox_'.$extra_id.'_'.$i.'" type="checkbox"><label id="label_'.$extra_id.'_'.$i.'" >'.$extra['extra_value'].'</label><div class="clear"></div>';
      $i++;
     }
 echo "";
    }

 }
}
echo '							<input class="submit" onclick="javascript:checkFilters();document.getElementById(\'filterform\').submit();" type="submit" value="submit">';

echo '					</form>
					<div class="clear"></div>	</div></div></div></div>';
} else {echo "";}
//if (is_numeric($_REQUEST['cPath']))
   ?>       