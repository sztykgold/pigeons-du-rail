
<?php 
header( 'content-type: text/html; charset=utf-8' );

function connect_db()
{
	return mysqli_connect('localhost', 'root', 'jhz36ghm', 'les_pigeons_du_rail');
}

function stations($regionsId)
{
	$connection = connect_db();
	mysqli_set_charset($connection, 'utf8' );
	
	$where = " ;";
	if($regionsId >= 0)
	{
		$where = " WHERE RegionsId = " . $regionsId . " ;";
	}
	$sql = "SELECT StationsId, Name from stations" . $where;
	$results = mysqli_query($connection, $sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());

	echo "<datalist id=\"stations\" >";
	
	foreach($results as $row)
	{
		echo "<option value=\"" . $row["Name"] . "\" id=\"" . $row["StationsId"] . "\"/>";
	}
	
	echo "</datalist><form><input type=\"text\" name=\"trainStop\" list=\"stations\" required></form>";
	
}

stations(-1);
?>