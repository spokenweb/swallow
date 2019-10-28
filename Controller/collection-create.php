<?php 
require_once "../Model/db.config.php";
require_once "../Model/collection.php";
require_once "../Model/cataloguer.php";
require_once "../Model/session.php";
isLogged($conn);

$objCataloguer = new Cataloguer($conn);
$objCataloguer->select($_SESSION["swallow_uid"]);

$objCollection = new Collection($conn);
$result = $objCollection->create($objCataloguer->institution);
echo $result;

$conn->close();
?>