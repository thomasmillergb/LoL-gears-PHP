
<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 28/12/2014
 * Time: 17:14
 */

class lolapi {

    function getAPI(){

        return '2d33b014-b236-4d80-88e4-60567ae5026c';

    }
    function getSummoner($summonerName){
        $summonerName = preg_replace('/\s+/', '', $summonerName);
        $result = file_get_contents('https://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/' . $summonerName . '?api_key=' . $this->getAPI());

        return $summoner = json_decode($result)->$summonerName;

    }
    function getMatchHistory($summonerID, $begin,$end){
        $result =  file_get_contents('https://euw.api.pvp.net/api/lol/euw/v2.2/matchhistory/' . $summonerID . '/?beginIndex=' . $begin . '&endIndex=' . $end . '&api_key=' . $this->getAPI());

        $matchHistory = json_decode($result);

        return $matchHistory;

    }

}
?>
