<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<link type="text/css" rel="stylesheet" href="/css/riot.css" />
<link type="text/css" rel="stylesheet" href="/css/main_theme.css" />


<link rel="shortcut icon" href="favicon.ico" >
<link rel="icon" href="images/x_gears.gif" type="image/gif" >

<head>
    <script src="/Chart.js-master/Chart.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>LoL Gears</title>

</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top" style="position:top;">
    <div class="navbar-inner" style="min-width:1000px;">

        <img src="images/x_gears.gif"/>

        <a href="/" > LoL Gears</a>
        <div class="navbar-search">
            <form action="index.php" method="get">
                Summoner Name: <input type="text" name="summonerName">
                <input type="submit" value="Submit">
            </form>
        </div>


    </div>
</div>

<?



if( $_GET["summonerName"]) {

    include ('connection.php');


    $connection = new createConnection(); //i created a new object

    $db =$connection->connectToDatabase(); // connected to the database


    $apiKey = '2d33b014-b236-4d80-88e4-60567ae5026c';
    $summonerName = preg_replace('/\s+/', '', $_GET["summonerName"]);
    $result = file_get_contents('https://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/' . $summonerName . '/?api_key=' . $apiKey);


    $summoners = json_decode($result);


    foreach ($summoners as $summoner)
        $summoner_id =$summoner->id;




    $sql = "SELECT * FROM matches WHERE summoner_id = $summoner_id";
    $result = mysqli_query($db, $sql);
    $displayStatus = true;
    if (mysqli_num_rows($result) == 0) {
        include ('update.php');
        $update = new updateSummoners();

       # $update->newSummoner($db,$summoner_id);
        $displayStatus = false;

    }

    else {
        $displayStatus = true;
        $creeps = array();
        $creeps10 = array();
        $creeps20 = array();
        $creeps30 = array();
        $creeps40 = array();

        $creepsd10 = array();
        $creepsd20 = array();
        $creepsd30 = array();
        $creepsd40 = array();
        while ($row = $result->fetch_assoc()) {

            if ($row["creeps"] > 40) {

                array_push($creeps, (float)$row["creeps"]);
                array_push($creeps10, (float)$row["creeps10"]);
                array_push($creeps20, (float)$row["creeps20"]);
                array_push($creeps30, (float)$row["creeps30"]);
                array_push($creeps40, (float)$row["creeps40"]);

                array_push($creepsd10, (float)$row["creepsD10"]);
                array_push($creepsd20, (float)$row["creepsD20"]);
                array_push($creepsd30, (float)$row["creepsD30"]);
                array_push($creepsd40, (float)$row["creepsD40"]);

                #echo "<br> creeps: ". $row["creeps"]. " - creeps10: ". $row["creeps10"]. " creeps20" . $row["creeps20"] . "<br>";
            }

        }
        $j_creeps = json_encode($creeps);
        $j_creeps10 = json_encode($creeps10);
        $j_creeps20 = json_encode($creeps20);
        $j_creeps30 = json_encode($creeps30);
        $j_creeps40 = json_encode($creeps40);

        $j_creepsd10 = json_encode($creepsd10);
        $j_creepsd20 = json_encode($creepsd20);
        $j_creepsd30 = json_encode($creepsd30);
        $j_creepsd40 = json_encode($creepsd40);

        $numberOfGames = sizeof($creeps);
    }

    $connection->closeConnection($db);
}


/*
$apiKey = '2d33b014-b236-4d80-88e4-60567ae5026c';

$result = file_get_contents('https://euw.api.pvp.net/api/lol/euw/v2.2/matchhistory/' . $summoner_id . '/?api_key=' . $apiKey);


$matchHistory = json_decode($result);

if ($matchHistory->matches != null) {

    foreach (array_reverse($matchHistory->matches) as $match) {
        /*
                foreach ($match->participants as $participant) {
                    $x = $participant->timeline->lane;
                    print "$x <br>";
                    echo $participant->timeline->role;
                }

    }

}
*/
if ($displayStatus)
    include ('status.php');

?>
<script>

</script>
</body>

</html>
