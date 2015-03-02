
<?php

include ('connection.php');
include ('api.php');




$connection = new createConnection();

$api = new lolapi();

$db =$connection->connectToDatabase();

if( $_GET["summonerName"]) {
    print "<h1>Updating all Summoners </h1>";
    $updateSummoners = new updateSummoners();

    $summonerId  = $api->getSummoner($_GET["summonerName"])->id;
    $updateSummoners->newSummoner($db, $summonerId);
}
else
{
    print "<h1>Updating all Summoners </h1>";
    $updateSummoners = new updateSummoners();
    $updateSummoners->upSummoners($db);
}


$connection->closeConnection($db);





class updateSummoners
{

    function upSummoners($db)
    {
        set_time_limit(1000);
        $sql = "SELECT summoner_id FROM users";
        $summoners = mysqli_query($db, $sql);
        $start = time();
        $currentTimeCycle = time();
        $api_Cycle = 0;
        $api_requests = 0;
        $total_api_requests = 0;



        $beginIndex = 0;
        $endIndex = 2;
        $api = new lolapi();
        foreach ($summoners as $summoner) {
            if ($api_requests < 10) {

                $api_requests++;
                $total_api_requests++;

                if (time() - $currentTimeCycle >= 11) {
                    $currentTimeCycle = time();
                    $api_requests = 0;
                    print 'reset';
                }
            }
            else{
                    $rest =time() - $currentTimeCycle-10;
                    print "<br>sleep for $rest</br>";
                    sleep($rest);
                    $api_requests = 0;
                    $currentTimeCycle = time();
                    $api_Cycle++;
                    $total_api_requests++;


            }
            //  quick bodge line to retrivel all users data
//            $this->newSummoner($db,$summoner['summoner_id']);


            $matchHistory = $api->getMatchHistory($summoner['summoner_id'],$beginIndex,$endIndex);

            $this->updateSummoner($matchHistory, $db, $summoner['summoner_id']);



        }
        $complete = time() - $start;
        print "<p>updateff Stats</p>";
        print "<p>time to complete $complete seconds</p>";
        print "<p>api requests $total_api_requests</p>";

    }


    function updateSummoner($matchHistory, $db, $summoner_id)
    {
        if (ob_get_level() == 0) ob_start();
        if ($matchHistory->matches != null) {
            foreach ($matchHistory->matches as $match) {
                $matchID = $match->matchId;
                foreach ($match->participants as $participant) {

                    $creep = $participant->stats->minionsKilled;
                    $creep10 = $participant->timeline->creepsPerMinDeltas->zeroToTen * 10;
                    $creep20 = $participant->timeline->creepsPerMinDeltas->tenToTwenty * 10;
                    $creep30 = $participant->timeline->creepsPerMinDeltas->twentyToThirty * 10;
                    $creepEnd = $participant->timeline->creepsPerMinDeltas->thirtyToEnd * 10;

                    $lane = $participant->timeline->lane;
                    $role = $participant->timeline->role;
                    $creepdiff10 = $participant->timeline->csDiffPerMinDeltas->zeroToTen * 10;
                    $creepdiff20 = $participant->timeline->csDiffPerMinDeltas->tenToTwenty * 10;
                    $creepdiff30 = $participant->timeline->csDiffPerMinDeltas->twentyToThirty * 10;
                    $creepdiff40 = $participant->timeline->csDiffPerMinDeltas->thirtyToEnd * 10;


                    /*
                     *
                     *ancientGolemKillsPerMinCounts
                     *baronKillsPerMinCounts
                     *dragonKillsPerMinCounts
                     *elderLizardKillsPerMinCounts
                     *
                     * type ParticipantTimeline struct {
                        AncientGolemAssistsPerMinCounts ParticipantTimelineData // Ancient golem assists per minute timeline counts
                        AncientGolemKillsPerMinCounts   ParticipantTimelineData // Ancient golem kills per minute timeline counts
                        AssistedLaneDeathsPerMinDeltas  ParticipantTimelineData // Assisted lane deaths per minute timeline data
                        AssistedLaneKillsPerMinDeltas   ParticipantTimelineData // Assisted lane kills per minute timeline data
                        BaronAssistsPerMinCounts        ParticipantTimelineData // Baron assists per minute timeline counts
                        BaronKillsPerMinCounts          ParticipantTimelineData // Baron kills per minute timeline counts
                        CreepsPerMinDeltas              ParticipantTimelineData // Creeps per minute timeline data
                        CsDiffPerMinDeltas              ParticipantTimelineData // Creep score difference per minute timeline data
                        DamageTakenDiffPerMinDeltas     ParticipantTimelineData // Damage taken difference per minute timeline data
                        DamageTakenPerMinDeltas         ParticipantTimelineData // Damage taken per minute timeline data
                        DragonAssistsPerMinCounts       ParticipantTimelineData // Dragon assists per minute timeline counts
                        DragonKillsPerMinCounts         ParticipantTimelineData // Dragon kills per minute timeline counts
                        ElderLizardAssistsPerMinCounts  ParticipantTimelineData // Elder lizard assists per minute timeline counts
                        ElderLizardKillsPerMinCounts    ParticipantTimelineData // Elder lizard kills per minute timeline counts
                        GoldPerMinDeltas                ParticipantTimelineData // Gold per minute timeline data
                        InhibitorAssistsPerMinCounts    ParticipantTimelineData // Inhibitor assists per minute timeline counts
                        InhibitorKillsPerMinCounts      ParticipantTimelineData // Inhibitor kills per minute timeline counts
                        Lane                            string                  // Participant's lane (legal values: MID, MIDDLE, TOP, JUNGLE, BOT, BOTTOM)
                        Role                            string                  // Participant's role (legal values: DUO, NONE, SOLO, DUO_CARRY, DUO_SUPPORT)
                        TowerAssistsPerMinCounts        ParticipantTimelineData // Tower assists per minute timeline counts
                        TowerKillsPerMinCounts          ParticipantTimelineData // Tower kills per minute timeline counts
                        TowerKillsPerMinDeltas          ParticipantTimelineData // Tower kills per minute timeline data
                        VilemawAssistsPerMinCounts      ParticipantTimelineData // Vilemaw assists per minute timeline counts
                        VilemawKillsPerMinCounts        ParticipantTimelineData // Vilemaw kills per minute timeline counts
                        WardsPerMinDeltas               ParticipantTimelineData // Wards placed per minute timeline data
                        XpDiffPerMinDeltas              ParticipantTimelineData // Experience difference per minute timeline data
                        XpPerMinDeltas                  ParticipantTimelineData // Experience per minute timeline data
                    }
                    type ParticipantFrame struct {
                        CurrentGold         int      // Participant's current gold
                        JungleMinionsKilled int      // Number of jungle minions killed by participant
                        Level               int      // Participant's current level
                        MinionsKilled       int      // Number of minions killed by participant
                        ParticipantID       int      // Participant ID
                        Position            Position // Participant's position
                        TotalGold           int      // Participant's total gold
                        XpPerMinDeltas      int      // Experience earned by participant
                    }
                     */
                    $matchID = $match->matchId;


                    $sql = "SELECT * FROM matches WHERE match_id = $matchID AND summoner_id = $summoner_id ";

                    $result = mysqli_query($db, $sql);

                    if (mysqli_num_rows($result) == 0) {

                        $sql = "INSERT INTO matches (match_id, summoner_id, creeps,creeps10,creeps20,creeps30,creeps40, creepsD10, creepsD20, creepsD30, creepsD40, lane, role)
                                        VALUES ($matchID,$summoner_id, $creep,$creep10,$creep20,$creep30,$creepEnd,$creepdiff10, $creepdiff20, $creepdiff30, $creepdiff40,'$lane', '$role')" ;
                        $result = mysqli_query($db, $sql)or trigger_error("Query Failed! SQL: $sql - Error: ".mysqli_error(), E_USER_ERROR);;
                        echo $result;
                      /*  while ($row = mysqli_fetch_array($result)) {
                            print_r($row);
                        }*/
                        flush();

                    }


                    ob_flush();
                    flush();
                }
            }
        }
        ob_end_flush();
    }

    function newSummoner($db, $summoner){
        $start = time();
        $currentTimeCycle = time();
        $api_Cycle = 0;
        $api_requests = 0;
        $total_api_requests =0;

        for ($x = 10; $x >= 0 ; $x--) {
            if ($api_requests < 10) {

                $api_requests++;
                $total_api_requests++;

                if (time() - $currentTimeCycle >= 11) {
                    $currentTimeCycle = time();
                    $api_requests = 0;

                }
            }
            else{
                $rest =time() - $currentTimeCycle-10;

                flush();
                sleep($rest);
                $api_requests = 0;
                $currentTimeCycle = time();
                $api_Cycle++;
                $total_api_requests++;


            }
            $beginIndex = $x*15;
            $endIndex = (($x+1)*15);
            $api = new lolapi();

            $matchHistory = $api->getMatchHistory($summoner,$beginIndex,$endIndex);

            $this->updateSummoner($matchHistory, $db, $summoner);

        }

        $complete = time()-$start;
        print "<p>update Stats</p>";
        print "<p>time to complete $complete seconds</p>";
        print "<p>api requests $total_api_requests</p>";

    }
}


?>
