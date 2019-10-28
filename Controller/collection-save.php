<?php
require_once "../Model/db.config.php";
require_once "../Model/collection.php";
require_once "../Model/session.php";
isLogged($conn);

$objCollection = new Collection($conn);
$objCollection->select($_POST['id']);

$objCollection->partner_institution = ( isset($_POST['partner_institution']) ?  $_POST['partner_institution']  : $objCollection->partner_institution) ;
$objCollection->contributing_unit  = ( isset($_POST['contributing_unit']) ? str_replace( "'","\'",$_POST['contributing_unit']) : $objCollection->contributing_unit ) ;
$objCollection->source_collection = ( isset($_POST['source_collection']) ? str_replace( "'","\'",$_POST['source_collection']) : $objCollection->source_collection ) ;
$objCollection->source_collection_description = ( isset($_POST['source_collection_description']) ? str_replace( "'","\'",$_POST['source_collection_description']) : $objCollection->source_collection_description ) ;

$objCollection->source_collection_ID = ( isset($_POST['source_collection_ID']) ? $_POST['source_collection_ID'] : $objCollection->source_collection_ID ) ;

$objCollection->save();

$conn->close();
?>