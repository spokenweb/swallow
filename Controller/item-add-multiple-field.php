<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../util.php";
require_once "../Model/session.php";
isLogged($conn);

$itemid = $_GET['itemid'];
$stepname = $_GET['stepname'];
$fieldname = $_GET['fieldname'];
$fieldvalue = $_GET['fieldvalue'];
$stepType = $_GET['steptype'];
$parentid =  $_GET['parentid'];

$objitem = new Item($conn);
$objitem->select($itemid);


$objitem->addElementMultiple($stepname,array("value"=>$fieldvalue),$fieldname,$parentid);


$objitem->save();

echo(" {\"step\": \"".$stepname."\" , \"itemid\":\"".$itemid."\", \"stepType\":\"$stepType\" }");

$conn->close();

?>