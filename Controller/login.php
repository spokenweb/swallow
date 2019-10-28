<?php
require_once "../Model/db.config.php";
require_once "../Model/cataloguer.php";

$in_email = $_POST['login'];
$in_pwd = $_POST['pwd'];

$objCataloguer = new Cataloguer($conn);

$uid = $objCataloguer->authenticate($in_email,$in_pwd); 

if ( $uid !== false ){

    session_start();
    $_SESSION['swallow_uid'] = $uid;
    header("Location: ../main.php");

}else{
    header("Location: ../index.php?err=1");
}

?>