<?php
require_once "../Model/db.config.php";
require_once "../Model/Workflow.php";
require_once "../Model/session.php";
require_once "../Model/Cataloguer.php";
require_once "../Model/Collection.php";
require_once "../Model/Item.php";
require_once "../Model/log.php";

isLogged($conn);


function renderTextfield($name,$in_value){
    
    echo("
        <label for='".$name."' class='col-sm-4'>$name</label>
        <input type='text' class='form-control' id='".$name."' name='".$name."' value=\"".$in_value."\">
        ");

}


function renderTextarea($name,$in_value){
    echo("   
        <label for='".$name."' class='col-sm-4'>$name</label>
        <textarea type='text' class='form-control' id='".$name."' name='".$name."' rows='5'>".$in_value."</textarea>
");

    if($name == 'title'){
         
        echo("
            <script>
                $('#item-title').html('<h1 id=\"item-title\">".str_replace("'","\'",$in_value)."</h1>')
            </script>"
        );
    }
}


function renderLookup($name,$url,$in_value,$id){
    $label = explode('-',$name);

    if(is_array($in_value) ){
        $in_value = '';
    }

    echo("

        <label for='".$name."' class='col-sm-4'>$label[0]</label>
        <input type='text' class='form-control' id='".$id."".$name."' name='".$name."' value=\"".$in_value."\">
        <span class='glyphicon glyphicon-search btn btn-primary' aria-hidden='true' data-toggle='modal' data-target='#lookupModal' onclick='loadLookup(\"". $url."\",\"".$id."".$name ."\")'></span>
    ");
}

function renderFileupload($name,$in_value){

    // modify the enctype of the form
    echo("
        <script>
            $('#stepForm')
                .attr( 'enctype', 'multipart/form-data')
                .attr( 'encoding', 'multipart/form-data');
        </script>
    ");

    echo("
        <label for='".$name."' class='col-sm-4'>$name</label>");
    
    if($in_value != NULL){
        echo("
            <img src='".$in_value."' width='350px' />
        ");
    }

    echo("
        <span class='col-sm-4'></span>
        <input type='file'  id='".$name."' name='".$name."' value=''>
        ");

    echo("");
    
 
}

function renderDropdown($name,$listurl,$in_value,$id='',$isMultiple = false){

    $label = explode('-',$name);
    
    echo("<label for='".$name."' class='col-sm-4'>".$label[0]."</label>");
    echo("<select name='".$name."' id='".$id.$name."' class='form-control'  >");
    echo("<option value='-1'>Select an option</option>");

    $contents = file_get_contents( '../Workflow/'.$GLOBALS['currentSchema'].'/'.$listurl ); 
    $contents = utf8_encode($contents); 
    $vocabulary = json_decode($contents,true);

    if($vocabulary !== NULL){

        foreach($vocabulary["values"] as $item){
            $label = "";
            $value = "";
            $help = "";

            if(key_exists('label',$item)){
                $label = $item['label'];
            }
            
            $value = $label;
            
            if(key_exists('help',$item)){
                $help = $item['help'];
            }

            $selected = '';
            if($value == $in_value){
                $selected = "selected";
            }

            echo("
            <option value='".$value."' ".$selected." >".$label."</option>
            ");
        }
    }
    echo("</select>");

}   

function renderField($field,$value,$id='',$renderMultiple = true){ 
    
    $type = explode("|",$field->type);
    $fieldname = $field->name;
    if(key_exists('multiple',$field)){
        $fieldname .="-ignore";
        $isMultiple = true;
    }else{
        $isMultiple = false;
    }

    if(!$isMultiple or ($isMultiple and $renderMultiple) ){
        switch ($type[0]){
            case "shorttext":
                renderTextfield($fieldname,$value);
                break;
            case "dropdown":
                renderDropdown($fieldname,$field->source,$value,$id,$isMultiple);
                break;
            case "uploadFile":
                renderFileupload($fieldname,$value);
                break;            
            case "longtext":
                renderTextarea($fieldname,$value);
                break;
            case "lookup":
                renderLookup($fieldname,$field->source,$value,$id);
                break;
        }
    }else{
        if($isMultiple and !$renderMultiple){
            $name = explode('-ignore',$fieldname);
            echo("
                <label class='col-sm-4'>".$name[0]."</label>
                <span>To add values to this field, please add the item first </span>
            ");
        }
    }
       
}



function renderForm($conn,$stepName,$itemid){

    $objItem = new Item($conn);
    $objItem->select($itemid);

    $objWorkflow = new Workflow();
    $objWorkflow->load('../Workflow/'.$objItem->schema_version.'/workflow.json');
    $fields = $objWorkflow->getFields($stepName);
    $step = $objWorkflow->getStep($stepName);

    

    foreach($fields as $field){
        echo("<div class='form-group'>");

        if(key_exists('multiple',$field)){

            if($step->type == 'single'){
                renderField($field,$objItem->getValue($stepName,$field->name),'',true);   
                # add button   
                echo("<span class='btn btn-primary' onclick=\"addMultipleFieldValue(  '$itemid','$stepName','$field->name','single',0 ) \" \>Add</span>");
                # render the list of values
                $values = $objItem->getValue($stepName,$field->name);

                if(is_array($values)){
                    echo("<div class='fields-multiple-group'>");
                    foreach($values as $value){
                        echo("
                            <div id=".$value['id'].">
                                <label class='col-sm-4'></label>
                                <div class='fields-multiple-outbox'>".$value['value']."<span class='fields-multiple-outbox-btn' onclick=\"removeMultipleFieldValue(  '$itemid','$stepName','$field->name','".$value['id']."','0','".$step->type."')\">X</span></div>  
                            </div>
                        ");
        
                    }
                    echo("</div>");
                }

            }else{ // if is a multiple step, adding multiple fields should be done after the item is created => disable the field
                renderField($field,$objItem->getValue($stepName,$field->name),'',false);   
            }   
            
        }else{
            renderField($field,$objItem->getValue($stepName,$field->name),'',true);   
        }//if(key_exists('multiple',$field)){

        echo("</div>");
        
    }
   
} // function renderForm($conn,$stepName,$itemid){

function sortMultipleFieldList($elementsArray){
    
    if(count($elementsArray) > 0){
        
        $keys = [];
        $result = [];
        
        $fields = array_keys($elementsArray[0]);
        $sortingField = "id";

        foreach($elementsArray as $element){
            $keys[] = $element[$sortingField];
        }
        
        asort($keys);
    
        foreach($keys as $key){
            foreach($elementsArray as $element){
                if($element[$sortingField] == $key){
                    $result[] = $element; 
                }
            }
        }

        return $result;
    }else{
        return $elementsArray;
    }

}

function  renderList($conn,$stepName,$itemid){

    $objItem = new Item($conn);
    $objItem->select($itemid);

    $objWorkflow = new Workflow();
    $objWorkflow->load('../Workflow/'.$objItem->schema_version.'/workflow.json');
    $fields = $objWorkflow->getFields($stepName);
    $step = $objWorkflow->getStep($stepName);

   

    $elements = $objItem->getElement($stepName);
    $elements = sortMultipleFieldList($elements);


    foreach($elements as $element){
        
        $keys = array_keys($element);
        $id = '';

        $id = $element['id'];
        $formid = "fid".str_replace('.','',$id);
        echo("<form id=$formid >");  
        echo("<input type='hidden' id='step' name='step' value='".$stepName."'>");   
        echo("<input type='hidden' id='itemid' name='itemid' value='".$itemid."'>");   
        echo("<input type='hidden' id='stepType' name='stepType' value='multiple'>"); 
        echo("<input type='hidden' id='id' name='id' value='".$id."'>");   
            
            
        foreach($fields as $field){
            
            echo("<div class='form-group'>");
            
            $encoded_key = str_replace(' ','_',$field->name);
            if(array_key_exists($encoded_key,$element)){   
                $fieldValue = $element[$encoded_key];
            }else{
                $fieldValue = '';
            }
        

            renderField($field,$fieldValue,$id);      

            if(key_exists('multiple',$field)){
                # add button                 
                echo("<span class='btn btn-primary' onclick=\"addMultipleFieldValue(  '$itemid','$stepName','$field->name','multiple','$id' ) \" \>Add</span>");
                # render the list of values

                if(key_exists($field->name,$element)){
                    $values = $element[$field->name];
                
                    if(is_array($values)){
                        echo("<div class='fields-multiple-group'>");
                        foreach($values as $value){
                            echo("
                                <div id=".$value['id'].">
                                <label class='col-sm-4'></label>
                                <div class='fields-multiple-outbox'>".$value['value']."<span class='fields-multiple-outbox-btn' onclick=\"removeMultipleFieldValue(  '$itemid','$stepName','$field->name','".$value['id']."','$id','".$step->type."')\">X</span></div>  
                                </div>
                            ");
                
                        }
                        echo("</div>");
                    }
                }//if(key_exists($field->name,$elements)){
                
            } // if(key_exists('multiple',$field)){
            echo("</div>"); 
        }
           
        echo("<div>");
        echo("<span class='col-sm-4'></span>");
        echo("<button type='button' class='btn btn-primary' style='margin-right:20px' onclick='saveChanges(\"$formid\")'>Save</button>");
        echo("<button type='button' class='btn btn-primary' onclick='deleteElement(\"$id\",\"$stepName\",$itemid)'>Delete</button>");
        echo("</div>");
        echo("</div>"); 
        echo("</form>");  
        echo("<hr />");
    }
}


function renderCataloguer($conn,$cataloguerid,$itemid){
    $objItem = new Item($conn);
    $objItem->select($itemid);

    $objCataloguer = new Cataloguer($conn);
    $objCataloguer->select($objItem->cataloguer_id);

    echo("  
    <div class='form-group'>
        <label for='fname' class='col-sm-3'>Name</label>
        <input type='text' class='form-control' disabled id='fname' name='fname' value='".$objCataloguer->name."'>
    </div>

    <div class='form-group'>
        <label for='lname'  class='col-sm-3'>Last Name</label>
        <input type='text' class='form-control' disabled id='lname' name='lname' value='".$objCataloguer->lastname."'>
    </div>"
    );
}

function renderCollection($conn,$cataloguerid,$itemid){

    $objItem = new Item($conn);
    $objItem->select($itemid);

    $objCataloguer = new Cataloguer($conn);
    $objCataloguer->select($objItem->cataloguer_id);

    $objCollection = new Collection($conn);
    $objCollection->selectFromPartner($objCataloguer->institution);


    echo("
        <div class='form-group'>    
            <label for='fname' class='col-sm-3'>Institution</label>
            <input type='text' class='form-control' disabled id='intitution' name='intitution' value='".$objCataloguer->institution."'>
        </div>
        <div class='form-group'>
            <label for='fname' class='col-sm-3'>Collection</label>
            <select name='collection_id' id='collection_id' class='form-control'>
                <option value=''>Select an option</option>
        ");
    
    for($i = 0 ; $i < $objCollection->total;$i++){
        $objCollection->go($i);
        $selected = "";
        
        if($objItem->collection_id ==  $objCollection->id){
            $selected = "selected";
        }

        echo("<option value='".$objCollection->id."' ".$selected.">".$objCollection->source_collection."</option>'");
    }

    echo("</select>
        </div>
        <div id='step-collection-info'></div>
    ");

    if($objItem->collection_id > 0){
        echo("
        <script>
            $('#step-collection-info').load('View/step-renderer-collection.php?collectionid=".$objItem->collection_id."&itemid=".$objItem->id."');
        </script>");
    }

}


if(isset($_GET['name']))
{
    $objWorkflow = new Workflow();
    if (!$objWorkflow->load('../Workflow/'.$GLOBALS['currentSchema'].'/workflow.json'))
    {
        echo('warning: error on load of workflow');
    };

    $step = $objWorkflow->getStep(str_replace(' ','_',$_GET['name']));
    if ($step==false){echo('warning: step '.$_GET['name'].' not retrieved');}
    if(isset($step->help)){
        $help = $step->help;
    }else{
        $help = "";
    }

    echo("<div class='step-subtitle'>");
    echo(str_replace('_',' ',$_GET['name']));
    if ($help!=""){  
        echo("<span class='glyphicon glyphicon-question-sign' style='cursor:pointer' data-toggle='modal' data-target='#helpModal' onclick='loadhelp(\"".$help." \")'></span>");
    }
    echo("</div>");

    $submittext = 'Save';
    if(isset($_GET['type']) and $_GET['type'] == "multiple" ){
        renderList($conn,$_GET['name'],$_GET['itemid']);
        $submittext = 'Add';
    }

    echo("<form id='stepForm' action='Controller/item-save-step.php'>");  
    echo("<input type='hidden' id='step' name='step' value='".$_GET['name']."'>");   
    echo("<input type='hidden' id='itemid' name='itemid' value='".$_GET['itemid']."'>");   
    echo("<input type='hidden' id='stepType' name='stepType' value='".$_GET['type']."'>");   

    switch ($_GET['name']){
        case "Cataloguer":
            renderCataloguer($conn,$_SESSION['swallow_uid'],$_GET['itemid']);
            break;
        
        case "Institution_and_Collection":
            renderCollection($conn,$_SESSION['swallow_uid'],$_GET['itemid']);
            break;
        case "Institution and Collection":
            renderCollection($conn,$_SESSION['swallow_uid'],$_GET['itemid']);
            break;

        default:
            renderForm($conn,$_GET['name'],$_GET['itemid'],$_GET['type']);
            break;
    }   
    
    
    echo("
    <div class='form-group'>
        <span class='col-sm-4'></span>
    ");



    echo("
        <button type='submit' class='btn btn-primary'>$submittext</button>
    </div>
    ");

    echo("</form>");
    echo("<script src='View/step-renderer.js'></script>");
}

$conn->close();
?>


<!-- HELP Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="helpModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="helpModalTitle"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

      <div id="modal-main" class="modal-body">
        <iframe name="helpframe" width="100%" height="600" frameborder="0"></iframe>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- LOOKUP -->

<div class="modal fade" id="lookupModal" tabindex="-1" role="dialog" aria-labelledby="lookupModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="width:400px" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lookupModalTitle"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

      <div id="lookupModalContainer" class="modal-body" name="lookupModalContainer">
        <iframe name="lookupframe" width="100%" height="600" frameborder="0"></iframe>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
function loadhelp(in_url){
    document.getElementsByName('helpframe')[0].src = in_url;
}

function loadLookup(url, id){
    document.getElementsByName('lookupframe')[0].src = url+"?id="+id;
}
</script>
