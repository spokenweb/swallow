<?php
require_once "../Model/db.config.php";
require_once "../Model/cataloguer.php";
require_once "../Model/session.php";
isLogged($conn);

$objCataloguer = new Cataloguer($conn);
$objCataloguer->select($_POST['id']);

$objCataloguer->name = ( isset($_POST['fname']) ? str_replace( "'","\'",$_POST['fname'])  : $objCataloguer->name) ;
$objCataloguer->lastname  = ( isset($_POST['lname']) ? str_replace("'","\'",$_POST['lname']) : $objCataloguer->lastname ) ;
$objCataloguer->email = ( isset($_POST['email']) ? $_POST['email'] : $objCataloguer->email ) ;
$objCataloguer->institution = ( isset($_POST['institution']) ? $_POST['institution'] : $objCataloguer->institution ) ;
$objCataloguer->role = ( isset($_POST['role']) ? $_POST['role'] : $objCataloguer->role ) ;


if($_POST['pwd1'] == $_POST['pwd2'] and $_POST['pwd1'] != ''){
    $objCataloguer->pwd =  password_hash($_POST['pwd1'], PASSWORD_DEFAULT);
}

$objCataloguer->save();

$conn->close();
?>