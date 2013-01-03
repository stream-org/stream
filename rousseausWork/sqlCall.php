<?PHP

include('connection.php');

function sqlCall($table, $column, $columnValue, $data) 
{

	$query = "SELECT * FROM Users WHERE " . $column . "=" . $columnValue;

	$queryResult = mysql_query($query);

	while($queryRow = mysql_fetch_array($queryResult))
	{
		echo $queryRow[$data];
		echo '<br>';
	}

}




?>