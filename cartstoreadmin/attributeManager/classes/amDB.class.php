<?php
/*
  $Id: DB.class.php,v 1.0 21/02/06 Sam West$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
  
  Copyright © 2006 Kangaroo Partners
  http://kangaroopartners.com
  osc@kangaroopartners.com
*/

/**
 * OSC Database functions wrapper - just in case they decide to release ms3 the moment i release this - he he
 */
class amDB {
	
	/**
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @package $strQuery sting - sql query string
	 * @return query reference
	 */
	function query($strQuery) {
		return tep_db_query($strQuery);
	}
	
	/**
	 * Fetches the next array from a mysql query reference
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $ref - referece from a mysql query
	 * @return array
	 */
	function fetchArray($ref) {
		return tep_db_fetch_array($ref);
	}
	
	/**
	 * Gets the field count from a mysql query reference
	 * @author Tomasz Iwanow aka TomaszBG - microvision@gmail.com
	 * @param $ref - referece from a mysql query
	 * @return int - number of fields in result
	 */
	function numFields($ref) {
		return mysql_num_fields($ref);
	}
	
	/**
	 * Gets the field name from a mysql query reference
	 * @author Tomasz Iwanow aka TomaszBG - microvision@gmail.com
	 * @param $ref - referece from a mysql query
	 * @param $offset - offset of a field
	 * @return string - name of the field
	 */
	function fieldName($ref,$offset) {
		return mysql_field_name($ref,$offset);
	}
	
	/**
	 * Counts the number of results from a mysql query referece
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $ref - reference from a mysql query
	 * @return int - number of rows in result
	 */
	function numRows($ref) {
		return tep_db_num_rows($ref);
	}
	
	/**
	 * peforms inserts / updates
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $strTable string tablename
	 * @param $arrData array data to be inserted/ updated
	 * @param $strAction sting - update / insert
	 * @param $strParams string additonal where clauses
	 * @return void
	 */
	function perform($strTable,$arrData,$strAction='insert',$strParams='') {
		return tep_db_perform($strTable,$arrData,$strAction,$strParams);
	}
	
	/**
	 * Returns a singular result from a mysql query
	 * @param $strQuery string - mysql query
	 * @return mixed - first record, first row
	 */
	function getOne($strQuery) {
		$res = amDB::query($strQuery);
		if ($res && amDB::numRows($res)) 
			return mysql_result($res,0,0);
		return false;
	}
	
	/**
	 * Returns all results from a mysql query
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $strQuery string - mysql query
	 * @return array - all results
	 */
	function getAll($strQuery) {
		$res = amDB::query($strQuery);
		$results = array();
		while($row = amDB::fetchArray($res))
			$results[] = $row;
		return $results;
	}
	
	/**
	 * Prepares string for database input
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $str string 
	 * @return void
	 */
	function input($str) {
		return tep_db_prepare_input($str);
	}
	
	/**
	 * Returns placebo autoincrement value
	 * @access public
	 * @param $strTable string table name
	 * @param $strField string field name
	 * @return mixed
	 */
	function getNextAutoValue($strTable,$strField) {
		return (int)amDB::getOne("select max($strField) + 1 as next from $strTable limit 1");
	}
	/**
	 * Some contributions such as the Ultimate SEO URLs have there own 
	 * database functions. This can cause the internal, last insert id to be 
	 * wrong if the link id isn't included in the mysql_insert_id statement.
	 * For this reason i have not used the default osc function for this one as for some
	 * reason they haven't put the link in their wrapper function.
	 * @author Sam West aka Nimmit - osc@kangaroopartners.com
	 * @param $link sting - db link name
	 * @return void
	 */
	function insertId($link = 'db_link' ) {
		global $$link;
		return mysql_insert_id($$link);
	}
}

?>