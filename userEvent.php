<?php 

define("TER", 1);
define("RER", 2);
define("Transilien", 3);

$STRINGS = array("UNDEFINED", "TER", "RER", "Transilien");

$historical_id = store_historical();
echo "<p>historical_id = " . $historical_id . "</p>";


function store_historical()
{
    $connection = mysqli_connect('localhost', 'root', 'jhz36ghm', 'historique_pdr');
	$post_values = "{";
	$first = true;
	
    foreach($_POST as $key => $value) {
	    if(!$first)
		{
		    $post_values = $post_values . ",";
		}
        $post_values = $post_values . $key . ":\"" . $value . "\"";
        echo "<p>POST parameter '$key' has '$value'</p>";
		$first = false;
    }

    $post_values = $post_values . "}";
	
	$sql =  "INSERT INTO brut(Timestamp , Value) VALUES(NOW(), '" . $post_values . "');";
	mysqli_query($connection, $sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());
    $ret = mysqli_insert_id($connection);
	mysqli_close($connection);
	return $ret;
}

function store_new_entry_to_check($TableName, $IdCreated, $OtherId, $Information)
{
    global $historical_id;
	
    $connection = mysqli_connect('localhost', 'root', 'jhz36ghm', 'historique_pdr');
	$sql = "INSERT INTO entry_to_check(Timestamp, TableName, IdCreated, OtherId, HistoricalId, Information) VALUES(Now(),'" . $TableName . "','" . $IdCreated . "','" . $OtherId . "','" . $historical_id . "','" . $Information ."');";      
	mysqli_query($connection, $sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());
    $ret = mysqli_insert_id($connection);
	mysqli_close($connection);
	return $ret;
}

function get_pigeons_id($email, $connection)
{
    $sql = "SELECT PigeonsId from pigeons WHERE PigeonsEmail='$email';";
	$result = mysqli_query($connection, $sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());
	if ($result->num_rows == 1)
	{
	    $row = $result->fetch_assoc();
		return (int)($row["PigeonsId"]);
	}
	if ($result->num_rows == 0)
	{
	    $sql = "INSERT INTO pigeons(PigeonsEmail) VALUES('$email');";
		mysqli_query($connection, $sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());
		return (int)(mysqli_insert_id($connection));
	}
	return -1;
}

function get_trains_id($train_type, $train_number, $train_code, $connection)
{
     switch($train_type)
	{
	    case "TER":
		    $sql = "SELECT TrainsId from trains WHERE TrainsNumber = '$train_number';";
			$result = mysqli_query($connection, $sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());
			if ($result->num_rows == 1)
	        {
                $row = $result->fetch_assoc();
				return (int)$row["TrainsId"];
			}
			if ($result->num_rows == 0)
			{
			    $sql = "INSERT INTO trains(TrainsNumber,TrainsType) VALUES ('$train_number', '" . TER . "');";
				mysqli_query($connection, $sql);
				$ret = mysqli_insert_id($connection);
				store_new_entry_to_check("trains", $ret, -1, "Train type " . $train_type . " code " . $train_code . " number " . $train_number . " created with id " . $ret);
				return $ret;
			}
			return -1;
			break;
			
		case "RER":
		    $sql = "SELECT TrainsId from trains WHERE TrainsNumber = '$train_number' AND TrainsCode = '$train_code';";
			$result = mysqli_query($connection, $sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());
			if ($result->num_rows == 1)
	        {
                $row = $result->fetch_assoc();
				return (int)$row["TrainsId"];
			}
			if ($result->num_rows == 0)
			{
			    $sql = "INSERT INTO trains(TrainsNumber, TrainsType, TrainsCode) VALUES ('$train_number', '" . RER . "', '$train_code');";
				mysqli_query($connection, $sql);
				$ret = mysqli_insert_id($connection);
				store_new_entry_to_check("trains", $ret, -1, "Train type " . $train_type . " code " . $train_code . " number " . $train_number . " created with id " . $ret);
				return $ret;
			}
			return -1;
		    break;
			
		case "Transilien":
		    $sql = "SELECT TrainsId from trains WHERE TrainsNumber = '$train_number' AND TrainsCode = '$train_code';";
			$result = mysqli_query($connection, $sql) or die('Erreur SQL !'.$sql.'<br>'.mysql_error());
			if ($result->num_rows == 1)
	        {
                $row = $result->fetch_assoc();
				return (int)$row["TrainsId"];
			}
			if ($result->num_rows == 0)
			{
			    $sql = "INSERT INTO trains(TrainsNumber, TrainsType, TrainsCode) VALUES ('$train_number', '" . Transilien . "', '$train_code');";
				mysqli_query($connection, $sql);
				$ret = mysqli_insert_id($connection);
				store_new_entry_to_check("trains", $ret, -1, "Train type " . $train_type . " code " . $train_code . " number " . $train_number . " created with id " . $ret);
				return $ret;
			}
			return -1;
		    break;	
	}
	return -1;
}

function get_formatted_name($stop)
{
    $a_accents = array("à", "ä", "â");
	$e_accents = array("é", "è", "ê", "ë");
	$i_accents = array("ï", "î");
	$o_accents = array("ô", "ö");
	$u_accents = array("ù", "ü", "û");
	$y_accents = array("ÿ");
	$spaces = array("-", "_", "\\", "/");
	$ret = str_replace($e_accents, "e", $stop);
	$ret = str_replace($a_accents, "a", $ret);
	$ret = str_replace($u_accents, "u", $ret);
	$ret = str_replace($i_accents, "i", $ret);
	$ret = str_replace($o_accents, "o", $ret);
	$ret = str_replace($y_accents, "y", $ret);
	$ret = str_replace("ç", "c", $ret);
	$ret = str_replace($spaces, " ", $ret);

	$ret = strtoupper($ret);
	return $ret;
}

function get_stops_id($stopName, $trainsId, $connection)
{
    $sql = "SELECT StopsId FROM stops WHERE TrainsId = $trainsId;";
	$result = mysqli_query($connection, $sql);
	$stopsId = -1;
	
	if($result->num_rows == 0)
	{
	   $formatted = get_formatted_name($stopName);
	   $sql = "SELECT StationsId FROM stations WHERE Name = '$formatted';";
	   $result2 = mysqli_query($connection, $sql);
	   $stationsId = -1;
	   
	   echo "<p>" . $sql . "</p>";
	   if($result2->num_rows == 0)
	   {
	       $sql = "INSERT INTO stations(Name, RegionsId) VALUES ('" . $formatted . "', '-1');";
		   mysqli_query($connection, $sql);
		   $stationsId = mysqli_insert_id($connection);
		   if($stationsId < 1)
		   		return -1;
	       store_new_entry_to_check("stations", $stationsId, $trainsId, "new station " . $formatted . " created");
	   }
	   else
	   {
	       $row = $result2->fetch_assoc();
	       $stationsId = (int)$row["StationsId"]; // TODO
		   if($stationsId < 1)
		   		return -1;
	   }
	   
	   $sql = "INSERT INTO stops(StationsId, TrainsId) VALUES ('" . $stationsId . "', '" . $trainsId . "');";
	   mysqli_query($connection, $sql);
	   $stopsId = mysqli_insert_id($connection);
	   
	   store_new_entry_to_check("stops", $stopsId, $trainsId, "stop " . $formatted . " (" . $stationsId . ") associated to " . $trainsId);
	   return $stopsId;	    	
	}
	else
	{
	    $row = $result->fetch_assoc();
	    $stopsId = (int)$row["StopsId"]; // TODO
	}
    return $stopsId;
}

function get_retards_id($trainsId, $stopsId, $duration, $connection)
{
    $sql = "SELECT RetardsId FROM retards WHERE RetardsTrainsId = $trainsId AND RetardsArretsId = $stopsId AND RetardsDuration = $duration";
	$result = mysqli_query($connection, $sql);
	$retardsId = -1;
	
	if($result->num_rows == 0)
	{
	    $sql = "INSERT INTO retards(RetardsTrainsId, RetardsArretsId, RetardsDuration) VALUES ('" . $trainsId . "','" . $stopsId . "','" . $duration . "');";
		mysqli_query($connection, $sql);
		
		$retardsId = mysqli_insert_id($connection);
	    if($retardsId < 1)
            return -1;
	}
	else
	{
	    $row = $result->fetch_assoc();
	    $retardsId = (int)$row["RetardsId"]; // TODO
	}
	return $retardsId;
}

function late($pigeonsId, $trainsId, $stopsId, $duration, $connection)
{
    $retardsId = get_retards_id($trainsId, $stopsId, $duration, $connection);	
	echo "<p> " . " retard Id =  " . $retardsId . "</p>";
	
	$sql = "SELECT * FROM pigeons_retard WHERE Date = Date(NOW()) AND PigeonsId = $pigeonsId AND RetardsId = $retardsId;";
	$result = mysqli_query($connection, $sql);
	
	if($result->num_rows == 0)
	{
	    $sql = "INSERT INTO pigeons_retard(Date,PigeonsId,RetardsId) VALUES(DATE(NOW()), $pigeonsId, $retardsId);";
	    mysqli_query($connection, $sql);
	}
}
/*
function deleted($email, $duration, $stop, $train_type, $train_number, $connection)
{

}
*/


if(isset($_POST['eventType']))
    $eventType=$_POST['eventType'];
else
    return;

// connexion à la base
$connection = mysqli_connect('localhost', 'root', 'jhz36ghm', 'les_pigeons_du_rail');

$pigeonsId = get_pigeons_id($_POST['userEmail'], $connection);

if($pigeonsId < 0)
{
    echo "<p>Erreur interne lors de la creation d'un nouvel utilisateur avec l'email " . $_POST['userEmail'] . "</p>";
	return;
}
echo "<p>" . "connection du pigeon " . $pigeonsId . "</p>";

$trainsId = get_trains_id($_POST['trainType'], $_POST['trainNumber'], "", $connection);
if($trainsId < 0)
{
    echo "<p>Erreur interne lors de la creation d'un nouveau train : " . $_POST['trainType'] . " : " . $_POST['trainNumber'] ."</p>";
	return;
}

echo "<p> " . "declare le train " . $trainsId . "</p>";

$stopsId = get_stops_id($_POST['trainStop'], $trainsId, $connection);	

if($stopsId < 0)
{
    echo "<p>Erreur interne lors de la creation d'un nouvel arret : " . $_POST['trainStop'] ."</p>";
	return;
}	


	   
switch($eventType)
{
  case "Late":
	   late($pigeonsId, $trainsId, $stopsId, (int)$_POST['lateDuration'], $connection); 
	   break;
  case "Full":
       full($pigeonsId, $trainsId, $connection);
	   break;
}

mysqli_close($connection);  // on ferme la connexion 
?>