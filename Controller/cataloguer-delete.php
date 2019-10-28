<?php
require_once "../Model/db.config.php";
require_once "../Model/cataloguer.php";
require_once "../Model/session.php";
isLogged($conn);
isAdmin($conn);

$objCataloguer = new Cataloguer($conn);
$objCataloguer->delete($_GET['id']);

$conn->close();
?>