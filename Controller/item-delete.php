<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../util.php";
require_once "../Model/session.php";
isLogged($conn);

if(isset($_GET['itemid'])){
    $objitem = new Item($conn);
    if(isAdmin($conn) || isEditor($conn) || $_SESSION['swallow_uid'] == $objitem->cataloguer_id ){
        $objitem->delete($_GET['itemid']);
    }
}

$conn->close();

?>