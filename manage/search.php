<?php
  require('includes/configure.php');
  require('includes/application_top.php');
  
  ?>
<?php
  require(DIR_WS_INCLUDES . 'header.php');
?>
<body>
    
    <div class="page-header"><h1>Search Orders by Model # </h1></div>
                
                 
                 
                 
    <p>
<form name="form" action="search.php" method="get">
    <div class="form-group">
  <input type="text" name="q" class="form-control"/>
    </div>
    <div class="form-group">
  <input type="submit" name="Submit" value="Enter Part Number Here" class="btn btn-default"/>
    </div>
</form>
</p>
</body>
<?php

  // Get the search variable from URL

  $var = @$_GET['q'] ;
  $trimmed = trim($var); //trim whitespace from the stored variable

// rows to return
$limit=1000; 

// check for an empty string and display a message.
if ($trimmed == "")
  {
  echo "<p>Please enter a part number...</p>";
  exit;
  }

// check for a search parameter
if (!isset($var))
  {
  echo "<p>We dont seem to have an order with that part number!</p>";
  exit;
  }


//connect to your database ** EDIT REQUIRED HERE **
mysql_connect(DB_SERVER,DB_SERVER_USERNAME,DB_SERVER_PASSWORD); //(host, username, password)


//specify database ** EDIT REQUIRED HERE **
mysql_select_db(DB_DATABASE) or die("Unable to select database"); //select which database we're using

// Build SQL Query  
$query = "SELECT `orders_id`,`products_model` FROM `orders_products` where products_model like \"%$trimmed%\"  
  order by orders_id"; // EDIT HERE and specify your table and field names for the SQL query

 $numresults=mysql_query($query);
 $numrows=mysql_num_rows($numresults);

// If we have no results, offer a google search as an alternative

if ($numrows == 0)
  {
  echo "<h4>Order Number:</h4>";
  echo "<p>Sorry, your search: &quot;" . $trimmed . "&quot; returned zero results</p>";


  }

// next determine if s has been passed to script, if not use 0
  if (empty($s)) {
  $s=0;
  }

// get results
  $query .= " limit $s,$limit";
  $result = mysql_query($query) or die("Couldn't execute query");

// display what the person searched for
echo "<p>You searched for: &quot;" . $var . "&quot;</p>";

// begin to show results set
echo "Order Numbers....";
$count = 1 + $s ;

// now you can display the results returned
  while ($row= mysql_fetch_array($result)) {
  $title = $row["orders_id"];

  echo "<b>$count.</b>)$title    " ;
  $count++ ;
  }

$currPage = (($s/$limit) + 1);

//break before paging
  echo "<br />";

  // next we need to do the links to other results
  if ($s>=1) { // bypass PREV link if s is 0
  $prevs=($s-$limit);
  print "&nbsp;<a href=\"$PHP_SELF?s=$prevs&q=$var\">&lt;&lt; 
  Prev 10</a>&nbsp&nbsp;";
  }

// calculate number of pages needing links
  $pages=intval($numrows/$limit);

// $pages now contains int of pages needed unless there is a remainder from division

  if ($numrows%$limit) {
  // has remainder so add one page
  $pages++;
  }

// check to see if last page
  if (!((($s+$limit)/$limit)==$pages) && $pages!=1) {

  // not last page so give NEXT link
  $news=$s+$limit;

  echo "&nbsp;<a href=\"$PHP_SELF?s=$news&q=$var\">Next 10 &gt;&gt;</a>";
  }

$a = $s + ($limit) ;
  if ($a > $numrows) { $a = $numrows ; }
  $b = $s + 1 ;
  echo "<p>Showing results $b to $a of $numrows</p>";
  
?>