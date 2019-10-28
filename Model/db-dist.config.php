<?php
/*------------------------------------------------------------
GLOBAL DATABASE CONFIGURATION
------------------------------------------------------------*/
$username = "";
$password ="";
$host = "";
$dbname = "";

$GLOBALS['currentSchema'] = "3";

$conn = new mysqli($host, $username, $password, $dbname);

if (mysqli_connect_error()) {
    die('Connect Error (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

$conn->set_charset('utf8');

/*------------------------------------------------------------
MAIL CONFIGURATION
------------------------------------------------------------*/
ini_set('SMTP', '');
ini_set('smtp_port', '');


/*------------------------------------------------------------
SESSION
------------------------------------------------------------*/
session_start();

?>
