<?php

//Class

class sRating{

////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Defined by rConfig.php

	public $dbHost, $dbName, $dbUser, $dbPass;

	public $ratingElements, $rateStyle;

	

	//Variables

	public $dbConnection;

	public $voteCheck;

	public $uniqueName, $uniqueNameID;

	public $average, $decAverage, $totalVotes;

	

	//Set variables

	public $rIdentity, $identityCheck = "TRUE";

	

////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function database(){

		//Connect to database


	//	mysql_select_db($this->dbName) or die(mysql_error());

		

		//Check if table exists

		if( mysql_num_rows( mysql_query("SHOW TABLES LIKE 'ratingItems'"))){

			//Table exists; Do nothing

		}else{

			//Create Table

			mysql_query("CREATE TABLE ratingItems (

			id INT NULL AUTO_INCREMENT PRIMARY KEY ,

			uniqueName VARCHAR(25) NOT NULL ,

			totalVotes INT DEFAULT '0' NOT NULL ,

			totalPoints INT DEFAULT '0' NOT NULL ,

			UNIQUE (uniqueName)

			) TYPE = MYISAM;");

		}//END IF

	}//END FUCNTION

	

////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function cookieCheck(){

		//Check for cookie

		if (isset($_COOKIE["sRate"])){

			$this->voteCheck="FALSE";

			$cookieArray = explode(".",$_COOKIE["sRate"]);

			$cookieArrayCount=count($cookieArray);

			for($i = 0; $i < $cookieArrayCount; $i++){

				if ($cookieArray[$i] == $this->uniqueRateID){

					$this->voteCheck="TRUE";

					break;

				}//END IF

			}//END FOR

		} else {

			$this->voteCheck="FALSE";

		}//END IF

	}//END FUCNTION

	

////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function ipCheck(){

		//If no cookie match found, check database to make sure user has not voted before

		if ($this->voteCheck=="FALSE" && $this->identityCheck=="TRUE"){

			//This variable can be changed to set a members id

			$this->rIdentity=str_replace(".","",$_SERVER["REMOTE_ADDR"]);//Get IP address

			

			//Check if table exists - ratingBans

			if( mysql_num_rows( mysql_query("SHOW TABLES LIKE 'ratingBans'"))){

				//Table exists; Do nothing

			}else{

				//Create Table

				mysql_query("CREATE TABLE ratingBans (

				identityList VARCHAR(25) NOT NULL ,

				idList VARCHAR(100) NOT NULL ,

				INDEX (identityList)

				) TYPE = MYISAM;");

			}//END IF

			

			//Get data from table

			$rGET=mysql_query("SELECT * FROM ratingBans WHERE identityList = '$this->rIdentity' LIMIT 0,1");

			

			//Look through table for identity match

			if(mysql_affected_rows() == 0){

				//No record found

				$this->voteCheck="FALSE";

				mysql_query("INSERT INTO ratingBans (identityList, idList) VALUES ('$this->rIdentity', 'a')");

			}else{

				$this->voteCheck="FALSE";

				//Load values into variable

				$ratingROW = mysql_fetch_array($rGET) or die(mysql_error());

				//If match found set $ratingVCHECK to TRUE

				$idListArray = explode(".",$ratingROW['idList']);

				$idListArrayCount=count($idListArray);

				for($i = 0; $i < $idListArrayCount; $i++){

					if ($idListArray[$i] == $this->uniqueRateID){

						$this->voteCheck="TRUE";

						break;

					}//END IF

				}//END FOR

			}//END IF

			

		}//END IF

	}//END FUCNTION

	

////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function data(){

		//Get data from table

		if ($this->voteCheck=="TRUE"){

			//For rSubmit.php

			$rGET=mysql_query("SELECT * FROM ratingItems WHERE id = '$this->uniqueRateID' LIMIT 0,1");

		} else {

			//For rSystem.php ( function create() )

			$rGET=mysql_query("SELECT * FROM ratingItems WHERE uniqueName = '$this->uniqueRate' LIMIT 0,1");

			


			//Check if $uniqueRate exists in a row, if not, create it

			if(mysql_affected_rows() == 0){

				//Create row based on unique name if first vote

				mysql_query("INSERT INTO ratingItems (id, uniqueName, totalVotes, totalPoints) VALUES (NULL, '$this->uniqueRate', '0', '0')");

				$rGET=mysql_query("SELECT * FROM ratingItems WHERE uniqueName = '$this->uniqueRate' LIMIT 0,1");

			}//END IF

		}//END IF

		

		//Load values into variable

		$ratingROW = mysql_fetch_array($rGET) or die(mysql_error());

		

		//Get ID

		$this->uniqueRateID=$ratingROW['id'];

		

		//Check if there are any votes

		if ($ratingROW['totalVotes']==0){

			//Set to 0

			$this->average=0; $this->totalVotes=0; $this->decAVG=0;

		}else{

			//Calculate Average Percent

			$average=$ratingROW['totalVotes']*$this->ratingElements;//Amount Possible: 100%

			$average=$ratingROW['totalPoints']/$average;//Average

			$this->decAVG=$average;//Store for calculating decimal average later

			$this->average=round($average*100);//Turn into percent

			//Store totalVotes

			$this->totalVotes=$ratingROW['totalVotes'];

		}//END IF

	}//END FUCNTION

	

////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function draw(){

		//Dimensions of image block, based on style

		switch ($this->rateStyle) {

		    case "smallRS":

		        $imageHeight = 16; $imageWidth = 16;

		        break;

		    case "largeRS":

				$imageHeight = 20; $imageWidth = 20;

				break;

		}//END SWITCH

			

		//Total width for # of rating elements

		$totalWidth = $imageWidth*$this->ratingElements;

		//Percent of space taken by each rating image of # rating elements

		$percentWidth = 100/$this->ratingElements;

		

		//Determine if ratings should be active

		if ( $this->voteCheck == "TRUE" ){

			//Don't activate rating selection

			echo '<form method="post" action="#" style="width:'.$totalWidth.'px; height:'.$imageHeight.'px" class="'.$this->rateStyle.'">'."\n";

		} else {

			echo '<form method="post" action="'.srRatingURL.'rSubmit.php" style="width:'.$totalWidth.'px; height:'.$imageHeight.'px" class="'.$this->rateStyle.'">'."\n";

			//Set Variables

			$createElements=1;

			$topIndex = $this->ratingElements+1;

			$totalElements = $this->ratingElements+1;

			//Hidden fields for storing data

			echo '<input type="hidden" name="uniqueRateID" value="'.$this->uniqueRateID.'" />'."\n";

			echo '<input type="hidden" name="rateStyle" value="'.$this->rateStyle.'" />'."\n";

			echo '<input type="hidden" name="ratedJS" value="0" />'."\n";

			//Loop until all element ratings are created

			while ($createElements!=$totalElements){

				$currentIndex=$topIndex-$createElements;

				$currentPercent=$percentWidth*$createElements;

				//Draw a rating element

				echo '<input type="submit" name="rated" value="'.$createElements.'" style="width:'.$currentPercent.'%; z-index:'.$currentIndex.'" />'."\n";

				//Increase $createElements

				$createElements++;

			}//END WHILE	

		}//END IF

		

		//Average Rating

		echo '<div class="rAverage" style="width:'.$this->average.'%"></div>'."\n";

		//Close Form

		echo '</form>'."\n";

		//Text

		echo '<p class="'.$this->rateStyle.'p"><span class="rtxtL">'.$this->totalVotes.' Votes</span>';

		echo '<span class="rtxtR">('.round($this->decAVG*$this->ratingElements,1).')</span></p>'."\n";

	}//END FUCNTION

	

////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function close(){

	//	mysql_close($this->dbConnection);

	}//END FUCNTION

	

////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function create($uniqueRate){

		//Retreive unique name

		$this->uniqueRate = $uniqueRate;

		

		//Start New - Reset voteCheck

		$this->voteCheck = "FALSE";

		$this->database();

		

		//Check Style

		$this->rateStyle=strtolower($this->rateStyle);

		if ($this->rateStyle!="large" && $this->rateStyle!="small"){

			//Invalid style, set a default

			$this->rateStyle="large";

		}//END IF

		//Set CSS class name

		$this->rateStyle=$this->rateStyle.'RS';

	

		$this->data();		

		$this->cookieCheck();

		$this->ipCheck();

		

		echo '<div class="ratingBox">'."\n";

		$this->draw();

		echo '</div>'."\n";

		

		$this->close();

	}//END FUCNTION



////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function submit(){

		//Came from here

		$referer = $_SERVER['HTTP_REFERER'];

		//Deny direct access

		if ($referer=="") die("Error");

		

		//Get form data

		$this->uniqueRateID = intval($_POST['uniqueRateID']);

		if ($this->uniqueRateID=="") die("ERROR");

		$this->rateStyle = $_POST['rateStyle'];

		$ratedJS = intval($_POST['ratedJS']);

		//Store rating from ajax / non ajax vote

		if ( $ratedJS == 0 ){

			//non ajax

			$ratingMe = intval($_POST['rated']);

		} else {

			//ajax

			$ratingMe = $ratedJS;

		}//END IF

		

		$this->cookieCheck();

		$this->database();

		$this->ipCheck();

		

		//Cookie Data

		if (isset($_COOKIE["sRate"])){

			$cookieData = $_COOKIE["sRate"].".".$this->uniqueRateID;

		} else {

			$cookieData = $this->uniqueRateID;

		}//END IF

		

		//Set Cookie and expire in 1 year

		$cookieExpire=time()+60*60*24*365;

		$cookieDomain=".".str_replace("www.","",$_SERVER['HTTP_HOST']);

		setcookie("sRate", $cookieData, $cookieExpire, "/", $cookieDomain); 

		

		//Verify vote numnber

		if ($ratingME > $this->ratingElements) die("Invalid Vote");

		

		//Store rating in database

		if ($this->voteCheck=="FALSE"){//If user has not voted, record vote

			mysql_query("UPDATE ratingItems SET totalVotes = totalVotes + 1, totalPoints = totalPoints + $ratingMe WHERE id = '$this->uniqueRateID'");

			if ($this->identityCheck=="TRUE"){//Record identity

				$appendString = ".";

				$appendString.= $this->uniqueRateID;

				mysql_query("UPDATE ratingBans SET idList = CONCAT(idList,'$appendString') WHERE identityList = '$this->rIdentity'");

			}//END IF

		}//END IF

		

		//Perform non ajax response

		if ( $ratedJS == 0 ){

			//Close Database Connection

			$this->close();

			//Send back

			header("Location: $referer");

		}//END IF

		

		//User has voted

		$this->voteCheck="TRUE";

		

		$this->data();

		$this->draw();		

		$this->close();

	}//END FUCNTION

////////////////////////////////////////////////////////////////////////////////////////////////////////////

}//END CLASS



//New Class

$SimpleRatings = new sRating;



//Load rConfig

require('rConfig.php');

?>