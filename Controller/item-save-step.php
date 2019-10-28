<?php
require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../Model/workflow.php";
require_once "../util.php";
require_once "../Model/session.php";
require_once "../Model/log.php";

isLogged($conn);


$stepname = $_POST['step'];
$itemid = $_POST['itemid'];

$objItem = new Item($conn);
$objItem->select($itemid);

$objWorkflow = new Workflow();
$objWorkflow->load('../Workflow/'.$objItem->schema_version.'/workflow.json');
$fields = $objWorkflow->getFields($stepname);

$errorDetails = "";

if($_POST['stepType'] == 'single'){

    if(isset($_FILES['image'])){
        $dirname = '../Uploads/'.$itemid.'/';
        if(file_exists($dirname) == false){
            mkdir($dirname);
        }
        
        if($_FILES['image']['name'] !=''){
            $filename = $_FILES['image']['name'];
            $target_file = $dirname .basename($filename);
            //should make some validation before uploading the file
            $allowed =  array('gif','png' ,'jpg');
            
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(in_array($ext,$allowed) ) {
                if($_FILES['image']['size'] < 2000000) {
                    move_uploaded_file($_FILES["image"]["tmp_name"],$target_file);
                    $objItem->updateValue($stepname,'image',$target_file);
                }else{
                    $errorDetails = "Image size is too big. Image files must be less that 2Mb";
                }
                
            }else{
                $errorDetails = "Invalid filetype. Only images (gif, png or jpg) are allowed. The field was not updated";
            }
            
        }
        
    }

    foreach($_POST as $key => $value) {

        if($key != 'step' and $key != 'itemid' and $key != 'stepType' and strpos($key,'ignore') === false){
            if($key == 'collection_id'){
                $objItem->collection_id = $value;
            }else{
                if($key == 'title'){
                    $objItem->title = $value;
                }
               
                $objItem->updateValue($stepname,$key,$value);
            }
        }
    }

}else{

    $element = [];

    if(isset($_FILES['image'])){
        $dirname = '../Uploads/'.$itemid.'/';
        if(file_exists($dirname) == false){
            mkdir($dirname);
        }
        
        if($_FILES['image']['name'] !=''){
            $filename = $_FILES['image']['name'];
            $target_file = $dirname .basename($filename);
            //should make some validation before uploading the file
            $allowed =  array('gif','png' ,'jpg');
            
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(in_array($ext,$allowed) ) {
                if($_FILES['image']['size'] < 2000000) {
                    move_uploaded_file($_FILES["image"]["tmp_name"],$target_file);
                    $element['image'] = $target_file;
                }else{
                    $errorDetails = "Image size is too big. Image files must be less that 2Mb";
                }
                
            }else{
                $errorDetails = "Invalid filetype. Only images (gif, png or jpg) are allowed. The field was not updated";
            }
            
        }
        
    }

    foreach($_POST as $key => $value) {
        if($key != 'step' and $key != 'itemid'  and $key != 'stepType' and strpos($key,'ignore') === false ){
            $element[$key] = $value;
        }
    }

    if(array_key_exists('id',$element)){ // is an update
        $originalElementList = $objItem->getElement($stepname);
        foreach($originalElementList as $originalElement){
            if($originalElement['id'] == $element['id']){
            //check if there's an image that's need to be preserved before deleting
                if(!key_exists('image',$element)){
                    if(key_exists('image', $originalElement)){
                        $element['image'] = $originalElement['image'];
                    }
                }

            // if there's a multiple fiels it must also be preserved
            foreach($originalElement as $key => $value){
                if( is_array($originalElement[$key]) ){
                //is a multiple field
                    $element[$key] = $value;
                }
            }
            
        }
    }
        $objItem->deleteElement($stepname,$element['id']);
    }
        $objItem->addElementMultiple($stepname,$element);
    
}

if($objItem->collection_id == ''){
    $objItem->collection_id = 'NULL';
}

if($objItem->schema_version == ''){
    $objItem->schema_version = $objWorkflow->version;
}

$result = $objItem->save();

if($result == false){
    $errorMgs = "Something went wrong saving. ".$errorDetails;
}else{
    $errorMgs = "".$errorDetails;
}

echo(" {\"step\": \"".$stepname."\" , \"itemid\":\"".$itemid."\", \"stepType\":\"".$_POST['stepType']."\", \"errorMgs\": \"".$errorMgs."\" }");

$conn->close()
?>