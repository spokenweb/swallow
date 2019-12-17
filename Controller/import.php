<?php
require_once "../Model/db.config.php";
require_once "../Model/session.php";
require_once "../Model/log.php";
require_once "../Model/map.php";
require_once "../Model/cataloguer.php";
require_once "../Model/collection.php";
require_once "../Model/item.php";


isLogged($conn);

/*
Parameters: 
$cataloguer_info,$collection_info,$metadata_info are Assoc Arrays
$schema_version id a string

This function store the record on the database
*/
function createRecord($cataloguer_info,$collection_info,$title,$schema_version,$metadata_info,$conn,$is_preview){
    //get the cataloguer
    $error = "";
    $objCataloguer = new Cataloguer($conn);
    if(array_key_exists('email',$cataloguer_info)){
        $objCataloguer->selectFromEmail($cataloguer_info['email']);
    }elseif(array_key_exists('name',$cataloguer_info) and array_key_exists('lastname',$cataloguer_info)){
        $objCataloguer->selectFromName($cataloguer_info['name'],$cataloguer_info['lastame']);
    }else{ 
        $error.=" ERROR: Can't find the cataloguer. Create the cataloguer before importing the records.";
    }

    //get the collection information
    $objCollection = new Collection($conn);
    if(array_key_exists('source_collection',$collection_info)){
        $objCollection->selectFromSourceCollection($collection_info['source_collection']);
        if($objCollection->total == 0){
            $error.=" ERROR: Can't find the collection. Create the source collection before importing the records.";   
        }
    }else{
        $error.=" ERROR: No source collection information was found.";
    }

    if($objCataloguer->total > 0 and $objCollection->total > 0){
        $objItem = new Item($conn);
        //find if there's another item with the same title
        $itemExists = $objItem->exists($title,$objCollection->id);

        if(!$is_preview){

                $id = $objItem->create($objCataloguer->id,$schema_version);
                $objItem->select($id);
                $objItem->collection_id = $objCollection->id;
                $objItem->title = $title;
                $objItem->metadata = $metadata_info;
                $objItem->save();       
                $error .= " Item with title \"$title\" was imported with id= $id.";

        }else{
            $error .= " Item with title \"$title\" OK.";
           
        }

        if($itemExists){
            $error .= " WARNING: Item with title \"$title\" already exists.";
        }
        
    }else{
        $error.=" ERROR: Won't import record: No cataloguer or collection information.";
    }

    return $error;

}


function createAssoc($header,$row){
    $result = array();
    $index = 0;
    foreach($header as $field){
        $result[$field] = $row[$index];
        $index++;
    }
    return $result;
}


function loadCSV($csvPath,$objMap,$conn,$is_preview){
    $fp = fopen($csvPath,'r');
    $header = fgetcsv($fp);

    $report = array();
    $lineNumber = 1;
    while(! feof($fp)){
        $row = fgetcsv($fp);
        $record = createAssoc($header,$row);
        
        $mappedRecord = $objMap->maprecord($record,'CSV');

        
        if($mappedRecord[4] != ""){
            $error = "ERROR: Can't import item in line $lineNumber. Cause: ".$mappedRecord[4];
        }else{
            $error = createRecord($mappedRecord[0],$mappedRecord[1],$mappedRecord[2],$objMap->target_schema,$mappedRecord[3],$conn,$is_preview);
        }
        
        $report[] = array("message"=>$error);
        $lineNumber++;
        
    }

    return $report;
    
}


function loadJSON($jsonPath,$objMap,$conn,$is_preview){
    $contents = file_get_contents($jsonPath);   
    $source = json_decode(utf8_encode($contents),TRUE);
    $lineNumber = 1;
    $report = [];

    if(!is_null($source)){
        foreach($source as $record){
        
            $mappedRecord = $objMap->maprecordJSON($record,'JSON'); 
         
              if($mappedRecord[4] != ""){
                  $error = "ERROR: Can't import item number $lineNumber. Cause: ".$mappedRecord[4];
              }else{
                  $error = createRecord($mappedRecord[0],$mappedRecord[1],$mappedRecord[2],$objMap->target_schema,$mappedRecord[3],$conn,$is_preview);
              }
              
              $report[] = array("message"=>$error);
              $lineNumber++;
          }
    }else{
        $report[] = array("message"=>"ERROR parsing JSON file: ".json_last_error_msg()); 
    }

    return $report;

}

$report = array();

if(isset($_FILES['fname'])){
    $filename = $_FILES['fname']['name'];

    $dataFile = "../Uploads/Imports/".basename($filename );       
    //should make some validation before uploading the file
    $allowed =  array('csv','json');
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if(in_array($ext,$allowed) ) {
    
        move_uploaded_file($_FILES["fname"]["tmp_name"],$dataFile);
        
        if($_POST['map'] != ''){

            $objMap = new Map();
            $objMap->load($_POST['map']);
            
            if(isset($_POST['is_preview'])){
                $preview = true;
            }else{
                $preview = false;
            }

            if($objMap->source_file_type == "CSV"){    
                $report = loadCSV($dataFile,$objMap,$conn,$preview);    
            }elseif($objMap->source_file_type == "JSON"){
                $report = loadJSON($dataFile,$objMap,$conn,$preview);  
            }

        }else{
            $report[] = array("message"=>'ERROR: No mapping function was selected'); ;
        }
    }else{
        $report[] = array("message"=>'ERROR: Unrecognized file format. Only CSV and JSON file types are accepted') ;
    }

}else{
    $report[] = array("message"=>'ERROR: No source file was selected') ;
}

echo(json_encode($report));

$conn->close();

?>