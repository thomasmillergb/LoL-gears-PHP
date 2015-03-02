
<h1>ftest</h1>

<?php


include ('connection.php');

$connection = new createConnection(); //i created a new object

$db =$connection->connectToDatabase(); // connected to the database
error_reporting(E_ERROR);
//$sql_statement="INSERT INTO matches `match_id`, `summoner_id`, `datetime`, `matchid`, `creeps`, `deaths`) VALUES ('2', '1', '2014-12-10 03:10:11', '3', '4', '4')";//or die(mysqli_error($db));
//$sql_statement="INSERT INTO matches VALUES ('3', '1', '2014-12-10 03:10:12', '3', '4', '4')";
$sql_statement="INSERT INTO matches (creeps) VALUES ('3')";
mysqli_query($db,$sql_statement);
printf ("New Record has id %d.\n", mysqli_insert_id($db));



$connection->closeConnection($db);// closed connectiond


/*
$mysqli = new mysqli("50.62.209.147", "killermillergb", "Towcester1", "killermillergb_",3306);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "\n";
*/


?>