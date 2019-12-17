<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../util.php";
require_once "../Model/session.php";
isLogged($conn);

if(isset($_GET['itemid'])){
    $objitem = new Item($conn);
    $objitem->select($_GET['itemid']);



    $id = $objitem->create($_SESSION['swallow_uid'],$objitem->schema_version);

    $objitemNew =  new Item($conn);
    $objitemNew->select($id);

    $objitemNew->title = $objitem->title." - COPY";
    $objitemNew->collection_id = $objitem->collection_id;
    $objitemNew->metadata = $objitem->metadata;

    // modify the title on json metadata 
    // This a very ugly hardcoded solution ... we should find a way the define the title path/key combination in a configuration file
    if($objitem->schema_version == 2){
        $objitemNew->updateValue("Item Description","title",$objitem->title." - COPY");
    }else{
        $objitemNew->updateValue("Item_Description","title",$objitem->title." - COPY");
    }

    $objitemNew->save();

}

$conn->close();

?>