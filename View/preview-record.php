<script>


    function setHeight(jq_in){
        jq_in.each(function(index, elem){
        // This line will work with pure Javascript (taken from NicB's answer):
        elem.style.height = elem.scrollHeight+'px'; 
     });
    }
    


</script>

<?php 
require_once "../Model/db.config.php";
require_once "../Model/Workflow.php";
require_once "../Model/session.php";
require_once "../Model/Cataloguer.php";
require_once "../Model/Collection.php";
require_once "../Model/Item.php";
isLogged($conn);

if(isset($_GET['id'])){

    $objItem = new Item($conn);
    $objItem->select($_GET['id']);

    $objWorkflow = new Workflow();
    $objWorkflow->load('../Workflow/'.$objItem->schema_version.'/workflow.json');

    $objCataloguer = new Cataloguer($conn);
    $objCataloguer->select($objItem->cataloguer_id);

    $objCollection = new Collection($conn);
    if($objItem->collection_id !== NULL){
        $objCollection->select($objItem->collection_id);
    }

    
    #navigate the workflow and show the values when present in the record
    echo("
        <h1> $objItem->title </h1>
    ");

    echo("
                <h3> Cataloguer </h3>
                <p> <b>Name</b>: $objCataloguer->name $objCataloguer->lastname </p>
                <p> <b>Partner Intitution</b>: $objCataloguer->institution </p>
                <hr />
            ");
    
    foreach ($objWorkflow->steps as $step){
        if($step->name == "Institution and Collection"){
            echo("
            <h3> Institution and Collection </h3>
            <p> <b>Contributing Unit:</b> $objCollection->contributing_unit </p>
            <p> <b>Source Collection:</b> $objCollection->source_collection </p>
            <p> <b>Source Collection Description:</b> $objCollection->source_collection_description </p>
            <p> <b>Source Collection ID:</b> $objCollection->source_collection_ID </p>
            ");
            if($objItem->getValue('Institution and Collection','persistent URL') != NULL){
                echo("<p> <b>Persistent URL:</b>: ".$objItem->getValue('Institution and Collection','persistent URL') ."</p>");
            }
            if($objItem->getValue('Institution and Collection','item ID') != NULL){
                echo("<p> <b>Item ID:</b>: ".$objItem->getValue('Institution and Collection','item ID') ."</p>");
            }
            
        }else{
            echo("<h3> $step->name </h3>");
            
            if($step->type == 'single'){
                $fields = $objWorkflow->getFields($step->name);
                foreach($fields as $field){
                
                    $value = $objItem->getValue($step->name,$field->name);
                    if($value != NULL){
                        //check if is multiple
                        if(key_exists('multiple',$field)){
                            // Deal with multiple fields here
                        }else{
                            echo("<p> <b>$field->name:</b>");
                            //check if is an image
                            if(strpos($value,".jpg") != false or strpos($value,".png")){
                                echo("<img src='".$value."' width = 200px>");
                            }elseif(strlen($value) > 64){
                                echo("<textarea class='longtext'>".$value."</textarea>");
                            }else{
                                echo($value);
                            }
                        }
                       
                        
                    }
                }
            }else{
                    $elements = $objItem->getElement($step->name);  
                    
                    foreach($elements as $element){
                        
                        $keys = array_keys($element);
                        $id = '';

                        foreach($keys as $key ){
                            if($key == 'id'){
                                $id = $element[$key];
                            }else{

                                echo("<p><b>".$key.":</b> ");

                                if(is_array($element[$key])){

                                    foreach($element[$key] as $multielement){
                                        if(strpos($multielement["value"],".jpg") != false or strpos($multielement["value"],".png")){
                                            echo("<img src='".$multielement["value"]."' width = 200px>");
                                        }elseif(strlen($multielement["value"]) > 64){
                                            echo("<textarea class='longtext' >".$multielement["value"]."</textarea>");
                                        }else{
                                            echo("".$multielement["value"].". ");
                                        }
                                    }
                                }else{

                                    if(strpos($value,".jpg") != false or strpos($value,".png")){
                                        echo("<img src='".$value."' width = 200px>");
                                    }elseif(strlen($element[$key]) > 64){
                                        echo("<textarea class='longtext' >".$element[$key]."</textarea>");
                                    }else{
                                        echo("".$element[$key]."");
                                    }
                                }
                                echo("</p>");
                            }
                        }
                        echo("<p> ------------------------  </p>");                  
                    }
               // }
            }
        }
        echo("<hr />");
    }


}

?>
