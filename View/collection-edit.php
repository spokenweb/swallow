<?php
require_once "../Model/db.config.php";
require_once "../Model/session.php";
require_once "../Model/collection.php";
isLogged($conn);

$objCollection = new Collection($conn);
$objCollection->select( $_GET['collectionid']);
?>

<h2> <span class="link" onclick="loadMainPage('View/collections.php')">Collections</span> > Edit Colection</h2>
<hr>

<div>
    <form id="applicationForm">

        <input type="hidden" class="form-control"  id="id" name="id" value="<?php echo($objCollection->id)?>">

        <div class="form-group">
            <label for="partner_institution" class="col-sm-3">Partner Institution</label>
            <?php 
                $disabled = '';
                if(!isAdmin($conn)){
                    $disabled = 'disabled';
                }
                echo("<select name='partner_institution' class='form-control' $disabled>");
                echo("<option value=''>Select an option</option>");

                $contents = file_get_contents("../Vocabulary/PartnerInstitution.json"); 
                $contents = utf8_encode($contents); 
                $vocabulary = json_decode($contents);

                if($vocabulary !== NULL){

                    foreach($vocabulary->values as $value){
                        $selected = "";
                        if($value == $objCollection->partner_institution){
                            $selected = "selected='selected'";
                        }
                        echo("
                        <option value='".$value."' $selected>".$value."</option>
                        ");
                    }
                }


                echo("</select>");
            ?>

        </div>

        <div class="form-group">
            <label for="contributing_unit"  class="col-sm-3">Contributing Unit</label>
            <input type="text" class="form-control" id="contributing_unit" name="contributing_unit" value="<?php echo($objCollection->contributing_unit)?>">
        </div>

        <div class="form-group">
            <label for="source_collection" class="col-sm-3">Source Collection</label>
            <input type="text" class="form-control" id="source_collection" name="source_collection" value="<?php echo($objCollection->source_collection)?>">
        </div>

        <div class="form-group">
            <label for="source_collection_description" class="col-sm-3">Source Collection Description</label>
            <textarea type="text" class="form-control" id="source_collection_description" name="source_collection_description" rows="5">
                <?php echo($objCollection->source_collection_description)?>
            </textarea>
        </div>


         <div class="form-group">
            <label for="source_collection_ID" class="col-sm-3">Source Collection ID</label>
            <input type="text" class="form-control" id="source_collection_ID" name="source_collection_ID" value="<?php echo($objCollection->source_collection_ID)?>">
        </div>


        <div class="form-group">
            <span class="col-sm-3"></span>
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn" onclick="cancel()">Finish</button>
        </div>

    
    </form>
</div>

<script src="View/collection-edit.js"></script>
<script src="View/breadcrumbs.js"></script>