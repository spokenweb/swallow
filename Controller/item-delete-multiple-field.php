<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../util.php";
require_once "../Model/session.php";
isLogged($conn);

$itemid = $_GET['itemid'];
$stepname = $_GET['stepname'];
$fieldname = $_GET['fieldname'];
$elementid = $_GET['elementid'];
$parentid = $_GET['parentid'];
$steptype = $_GET['steptype'];

$objitem = new Item($conn);
$objitem->select($itemid);
$objitem->deleteElement($stepname,$elementid,$fieldname,$parentid);
$objitem->save();

echo(" {\"step\": \"".$stepname."\" , \"itemid\":\"".$itemid."\", \"stepType\":\"$steptype\" }");

$conn->close();

?>