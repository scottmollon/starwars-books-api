<?
include_once "env.php";

//class for returning results about a query
class Query_Result
{
	public $result;
	public $rows;
	public $last_updated_id;

	function __construct()
	{
		$this->result = null;
		$this->rows = 0;
		$this->last_updated_id = 0;
	}
}

//run a sql query and specify if it is a query which is editing the db or not
function run_query($query, $edit_query)
{
	global $sql_loc, $sql_db, $sql_user, $sql_password;

	$qres = new Query_Result();
	
	$mysqli= new mysqli($sql_loc, $sql_user, $sql_password, $sql_db);
	// = mysql_connect("localhost", $sql_user, $sql_password);
	//mysql_select_db($sql_db);

	if ($mysqli->connect_errno)
	{
		exit();
	}

	if ($result = $mysqli->query($query))
	{
		if ($edit_query)
		{
			$qres->result = $result;
			$qres->rows = $mysqli->affected_rows;
			$qres->last_updated_id = $mysqli->insert_id;
		}
		else
		{
			$qres->result = array();
			while($row = $result->fetch_array(MYSQLI_ASSOC))
			{
				array_push($qres->result, $row);
			}
			
			$qres->rows = $result->num_rows;
			$result->free();
		}
	}
	
	$mysqli->close();
	//mysql_close($link);
	
	return $qres;
}

//escape a string for use with SQL
function sql_escape_string($string)
{
	global $sql_user, $sql_password;
	
	$mysqli= new mysqli("localhost", $sql_user, $sql_password, $sql_db);
	//$link = mysql_connect("localhost", $sql_user, $sql_password);
	
	if ($mysqli->connect_errno)
	{
		exit();
	}
	
	$esc_string = $mysqli->real_escape_string($string);

	$mysqli->close();
	//mysql_close($link);
	
	return $esc_string;
}

?>