<?php
require_once "cataloguer.php";


function isLogged(){
    if(isset($_SESSION['swallow_uid']) and $_SESSION['swallow_uid'] != ''){
        return true;
    }else{
        header("Location: index.php");
    }
   
}

function isAdmin($conn){
    $objCataloguer = new Cataloguer($conn);
    $objCataloguer->select($_SESSION['swallow_uid']);
    if($objCataloguer->role == 1){
        return true;
    }else{
        return false;
    }
}

function isEditor($conn){
    $objCataloguer = new Cataloguer($conn);
    $objCataloguer->select($_SESSION['swallow_uid']);
    if($objCataloguer->role == 1 or $objCataloguer->role == 2){
        return true;
    }else{
        return false;
    }
}

?>