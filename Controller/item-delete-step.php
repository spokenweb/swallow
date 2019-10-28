<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../util.php";
require_once "../Model/session.php";
isLogged($conn);

$objitem = new Item($conn);

$itemid = $_GET['itemid'];
$stepname = $_GET['stepname'];
$elementid = $_GET['id'];

$objitem->select($itemid);
$objitem->deleteElement($stepname,$elementid);
$objitem->save();

echo(" {\"step\": \"".$stepname."\" , \"itemid\":\"".$itemid."\", \"stepType\":\"multiple\" }");

$conn->close();
?>