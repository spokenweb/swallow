<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../util.php";
require_once "../Model/session.php";
isLogged($conn);

if(isset($_GET['itemid'])){
    $objitem = new Item($conn);
    $objitem->select($_GET['itemid']);

    $id = $objitem->create($objitem->cataloguer_id,$objitem->schema_version);

    $objitemNew =  new Item($conn);
    $objitemNew->select($id);

    $objitemNew->title = $objitem->title." - COPY";
    $objitemNew->collection_id = $objitem->collection_id;
    $objitemNew->metadata = $objitem->metadata;

    $objitemNew->save();

}

$conn->close();

?>