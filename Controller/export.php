<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../Model/collection.php";
require_once "../Model/cataloguer.php";
require_once "../Model/session.php";
require_once "../Model/workflow.php";
isLogged($conn);

/*
DESCRIPTION: Traverses all metadata fields values on the item and adds the uri from the vocabulary file
*/


function prepareMetadata($in_item,$in_conn){
    //load the schema
    $objWorkflow = new Workflow($in_conn);    
    $objWorkflow->loadFromVersion($in_item->schema_version);
    
    $result = [];
    $metadata = $in_item->metadata;
    $stepsKeys = array_keys($metadata);
    
    for($i = 0; $i < count($stepsKeys); $i++){
        $stepName = $stepsKeys[$i];
        $stepData = $metadata[ $stepName ];
        $stepSchema = $objWorkflow->getStep( $stepName );
        $result[$stepName] = [];

        if($stepSchema != false){

            if($stepSchema->type == 'single'){
                $objWorkflow->getFields($stepName);    
                $dataFields = array_keys($stepData);
                for($j = 0; $j < count($dataFields); $j++){
                    $fieldname = $dataFields[$j];
                    $fieldValue = $stepData[$fieldname];
                    $result[$stepName][$fieldname] = $fieldValue;
                    //check for URIs in the controlled vocabularies
                    if($objWorkflow->getVocabulary($fieldname)){
                        $uri = $objWorkflow->getURI($stepName ,$fieldname ,$fieldValue);
                        if($uri !== false){
                            $result[$stepName][$fieldname."_uri"] = $uri;
                        }
                    }
                }

            }else{
                $objWorkflow->getFields($stepName); 
                
                foreach($stepData as $stepDataElement){
                    $resultElement = [];
                    $dataFields = array_keys($stepDataElement);
                    for($j = 0; $j < count($dataFields); $j++){
                        $fieldname = $dataFields[$j];
                        $fieldValue = $stepDataElement[$fieldname];
                        $resultElement[$fieldname] = $fieldValue;
                        //check for URIs in the controlled vocabularies
                        if($objWorkflow->getVocabulary($fieldname)){
                            $uri = $objWorkflow->getURI($stepName ,$fieldname ,$fieldValue);
                            if($uri !== false){
                                $resultElement[$fieldname."_uri"] = $uri;
                            }
                        }
                    }
                    
                    $result[$stepName][] = $resultElement;
                }
            
            }
        } //if($stepSchema != false){
    }
    return $result; 
}

$metadataquery = base64_decode($_GET['query']);
$institution = $_GET['institution'];
$cataloguer = $_GET['cataloguer'];
$collection = $_GET['collection'];
$format = $_GET['format'];

$objItem = new Item($conn);
$objItem->metadataQuery($metadataquery,$institution ,$cataloguer,$collection);

$objCataloguer = new Cataloguer($conn);
$objCollection = new Collection($conn);

$fulldataset = [];

for($i = 0; $i < $objItem->total;$i++){
    $record = [];
    $objItem->go($i);

    $record["schema"] = "Swallow JSON";
    $record["schema_version"] = $objItem->schema_version;
    $record["swalllow_id"] = $objItem->id;

    
    $objCataloguer->select($objItem->cataloguer_id);
    $record["cataloguer"]["name"] = $objCataloguer->name;
    $record["cataloguer"]["lastname"] = $objCataloguer->lastname;
    $record["cataloguer"]["email"] = $objCataloguer->email;

    $record["partner institution"] = $objCataloguer->institution;

    $objCollection->select($objItem->collection_id);

    $record["collection"]["contributing unit"] =  $objCollection->contributing_unit;
    $record["collection"]["source_collection"] =  $objCollection->source_collection;
    $record["collection"]["source_collection_description"] =  $objCollection->source_collection_description;
    $record["collection"]["source_collection_id"] =  $objCollection->source_collection_ID;

    if($objItem->metadata != NULL){
        $fulldataset[] = $record + prepareMetadata($objItem,$conn);
    }else{
        $fulldataset[] = $record + [];
    }

}


header('Content-Type: application/json; charset=utf-8');
echo(json_encode($fulldataset));

$conn->close();

?>