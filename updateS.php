<?php

class updateS {

    function updateSumner($summoner_id, $db){
        $result = file_get_contents('https://euw.api.pvp.net/api/lol/euw/v2.2/matchhistory/' . $summoner_id . '/?api_key=' . $apiKey);
        $matchHistory = json_decode($result);
        foreach(array_reverse($matchHistory->matches) as $match){

            foreach($match->participants as $participant ){
                $creep = $participant->stats->minionsKilled;
                $creep10 =$participant->timeline->creepsPerMinDeltas->zeroToTen *10;
                $creep20 =$participant->timeline->creepsPerMinDeltas->tenToTwenty *10;
                $creep30 =$participant->timeline->creepsPerMinDeltas->twentyToThirty *10;
                $creepEnd =$participant->timeline->creepsPerMinDeltas->thirtyToEnd *10;

                $matchID = $match->matchId;





                $sql = "SELECT EXISTS ( SELECT * FROM matches WHERE match_id = $matchID
                           AND summoner_id = $summoner_id)";

                $result =mysqli_query($db,$sql);

                if($result)
                {
                    //print "added . $matchID";
                }
                else
                {
                    $sql_statement="INSERT INTO matches (match_id, summoner_id, creeps,creeps10,creeps20,creeps30,creeps40)
                                        VALUES ($matchID,$summoner_id, $creep,$creep10,$creep20,$creep30,$creepEnd)";
                    mysqli_query($db,$sql_statement);

                }





            }
        }
    }
}