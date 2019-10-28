<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../util.php";
require_once "../Model/session.php";
isLogged($conn);

$objitem = new Item($conn);
$id = $objitem->create($_SESSION['swallow_uid'],$GLOBALS['currentSchema']);

echo $id;

$conn->close();

?>