<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 15/12/14
 * Time: 13:04
 */

class createConnection {


    function connectToDatabase() // create a function for connect database
    {
        error_reporting(E_ERROR);

        $myconn = new mysqli("50.62.209.147", "killermillergb", "Towcester1", "killermillergb_",3306);
        if ($myconn->connect_errno) {
            echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        else{


            // echo $myconn->host_info . "\n";

        }
        return $myconn;

    }

    function closeConnection($myconn) // close the connection
    {
        mysqli_close($myconn);
    }

} 