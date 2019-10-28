<?php
require_once "../Model/db.config.php";
require_once "../Model/collection.php";
require_once "../Model/item.php";
require_once "../Model/session.php";
isLogged($conn);

if(isset($_GET['id'])){
    $objCollection = new Collection($conn);
    $objCollection->select($_GET['id']);

    $id = $objCollection->create();

    $objNewCollection = new Collection($conn);
    $objNewCollection->select($id);

    $objNewCollection->partner_institution = $objCollection->partner_institution;
    $objNewCollection->contributing_unit = $objCollection->contributing_unit;
    $objNewCollection->source_collection = $objCollection->source_collection." - COPY";
    $objNewCollection->source_collection_description = $objCollection->source_collection_description;
    $objNewCollection->source_collection_ID = $objCollection->source_collection_ID;

    $objNewCollection->save();

    //get, duplicate and midify the items on teh collection
    $objItem = new Item($conn);
    $objItem->metadataQuery('',$objCollection->partner_institution,-1,$objCollection->id);

    $objitemNew  = new Item($conn);
    for($i = 0; $i < $objItem->total; $i++){
        $objItem->go($i);

        $id = $objItem->create($objItem->cataloguer_id,$objItem->schema_version);
        $objitemNew =  new Item($conn);
        $objitemNew->select($id);
        $objitemNew->title = $objItem->title;
        $objitemNew->collection_id = $objNewCollection->id;
        $objitemNew->metadata = $objItem->metadata;
        
    
        $objitemNew->save();

    }

    

}

$conn->close();
?>