
<h1>ftest</h1>

<?php
$mysqli = new mysqli("50.62.209.147", "killermil8lergb", "Towcester1", "killermillergb_",3306);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo $mysqli->host_info . "\n";



?>