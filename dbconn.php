<?php
include('includes/configure.php');

class connec{
var $hostname;
var $user;
var $dblink;
var $password;
var $result;
var $returnRows;
var $strquery;
var $row;
var $db;

function connec()
{
	$this->hostname=DB_SERVER;

$this->user=DB_SERVER_USERNAME;
$this->password=DB_SERVER_PASSWORD;
$this->db=DB_DATABASE;

}

function openconn()
{
if ($this->dblink=mysql_connect($this->hostname,$this->user,$this->password))
		{
			return $this->dblink;
		}
	else
	{
	return false;
	}	
}

function selectdatabase()
{
	if(mysql_select_db($this->db,$this->dblink ))
		{
			return true;
		}
	else
		{
		return false;
		}
		
}

function setqurey($qurey)
{
	$this->strquery=$qurey;

}
function printqurey()
{
	return $this->strquery;
}
function getAffectedRows()
{
	return $this->returnRows;
}
function executequery()
{
if (	$this->result=mysql_query($this->strquery))
	{
		$this->returnRows =mysql_affected_rows();
		return true;
	}
	else
	{
	return false;
	}
}

function nextrow()
{
if(	$this->row=mysql_fetch_object($this->result))
	{
		return $this->row;
	}
	else
	{
		return false;
	}
}
function closeconnection()
{
	if (mysql_close($this->dblink))
	{
		return true;
	}
	else
	{
		return false;
	}
}
}

?>