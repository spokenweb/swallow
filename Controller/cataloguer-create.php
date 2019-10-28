<?php
require_once "../Model/db.config.php";
require_once "../Model/cataloguer.php";
require_once "../Model/session.php";
isLogged($conn);
isAdmin($conn);


$objCataloguer = new Cataloguer($conn);


$in_name = ( isset($_POST['name']) ? $_POST['name'] : 'User' ) ;
$in_lastname = ( isset($_POST['lastname']) ? $_POST['lastname'] : 'New' ) ;
$in_email = ( isset($_POST['email']) ? $_POST['email'] : '' ) ;
$in_pwd = ( isset($_POST['pwd']) ? $_POST['pwd'] : bin2hex(openssl_random_pseudo_bytes(6)) ) ;
$in_institution = ( isset($_POST['institution']) ? $_POST['institution'] : '' ) ;


$id = $objCataloguer->create($in_name, $in_lastname, $in_email, password_hash($in_pwd, PASSWORD_DEFAULT), $in_institution);

echo($id);

$conn->close();
?>