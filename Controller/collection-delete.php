<?php
require_once "../Model/db.config.php";
require_once "../Model/collection.php";
require_once "../Model/session.php";
isLogged($conn);

$objCollection = new Collection($conn);
$objCollection->delete($_GET['id']);

$conn->close();
?>