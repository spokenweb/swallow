<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once "../Model/db.config.php";
require_once "../Model/item.php";
require_once "../Model/collection.php";
require_once "../Model/cataloguer.php";
require_once "../Model/session.php";
isLogged($conn);

$page = 1;
$pagetotal = 15;
$metadataquery ='';
$objItem = new Item($conn);

$institution = '-1';
$cataloguer = '-1';
$collection = '-1';

if(isset($_GET['page'])){
  $page = $_GET['page'];
}

if(isset($_GET['cataloguer'])){
  $cataloguer = $_GET['cataloguer'];
}

if(isset($_GET['institution'])){
  $institution = $_GET['institution'];
}

if(isset($_GET['collection'])){
  $collection = $_GET['collection'];
}

if(isset($_GET['metadataquery'])){
  $metadataquery = base64_decode($_GET['metadataquery']);  
}

$objItem->metadataQuery($metadataquery,$institution,$cataloguer,$collection,$page);


$objCollection = new Collection($conn);
$objCollection->selectAll();

$objCataloguer = new Cataloguer($conn);
$objCataloguer->selectAll();


function fillInstitution($institution){
  $contents = file_get_contents("../Vocabulary/PartnerInstitution.json"); 
  $contents = utf8_encode($contents); 
  $vocabulary = json_decode($contents);

  if($vocabulary !== NULL){

      foreach($vocabulary->values as $value){
        $selected = '';
         if($value == $institution){
           $selected = "selected";
         }
         echo("
          <option value='".$value."' $selected>".$value."</option>
          ");
      }
  }
}


function fillCataloguer($institution,$objCataloguer,$cataloguer){

  for($i = 0; $i < $objCataloguer->total; $i++){
    
    $objCataloguer->go($i);
    if($objCataloguer->institution == $institution){
        
      $selected = '';
      if($objCataloguer->id == $cataloguer){
         $selected = "selected";
      }
      
      echo("
         <option value='".$objCataloguer->id."' $selected>".substr($objCataloguer->name,0,1).". ".$objCataloguer->lastname."</option>
      ");
    }
  }   
}

function fillCollection($institution,$objCollection,$collection){

  for($i = 0; $i < $objCollection->total; $i++){
    
    $objCollection->go($i);
    if($objCollection->partner_institution == $institution){
        
      $selected = '';
      if($objCollection->id == $collection){
         $selected = "selected";
      }
      
      echo("
         <option value='".$objCollection->id."' $selected>".$objCollection->source_collection."</option>
      ");
    }
  }   
}

?>


<h2>Export</h2>
<hr>

<div  id="deposit">

    

    <div class="col-sm-11 border-box" >
        
          <table class="table">
            <thead>
            <tr>
              <td>
                <span class="glyphicon glyphicon-filter" style=" font-size: 25px; margin-top:10px"></span>
              </td>
              <td>
                <select id="f_institution" class="form-control" style="width:250px" onchange="filter(1)">
                  <option value='-1' >INSTITUTION</option> 
                  <?php
                    fillInstitution($institution);
                  ?>
                </select>
              </td> 

              <td>
                <select id="f_cataloguer" class="form-control" style="width:250px" onchange="filter(1)">
                  <option value='-1' >CATALOGUER</option> 
                  <?php
                    fillCataloguer($institution,$objCataloguer,$cataloguer);
                  ?>
                </select>
              </td>

              <td>
                <select id="f_collection" class="form-control" style="width:250px" onchange="filter(1)">
                  <option value='-1' >COLLECTION</option> 
                  <?php
                    fillCollection($institution,$objCollection,$collection);
                  ?> 
                </select>
              </td>
            </tr>
            </thead>
          </table>

   
       
        <div class='form-group'>
            <span class="glyphicon glyphicon-search" style=" font-size: 25px; margin-top:10px; margin-right:20px"></span>
            <input type='text' class='form-control' style='max-width: 600px;' id='metadaquery' name='metadaquery' value='<?php echo($metadataquery)?>'>
            <button type='button' class='btn btn-primary' onclick="query()">Execute</button>
        </div>
       
    </div>

    <div class="col-sm-11 border-box" style="text-align:center; padding:10px; background-color:#ccc">

        <b>Export current dataset as: </b>
        <select id="export_format" name="export_format" class="form-control" style="width:250px;display: inline-block;margin-left:30px:margin-right:30px" >
            <option value='-1' >Select the format</option>
            <option value='1'>Swallow Json</option>
        </select>
        <button type='button' class='btn btn-primary' onclick="exportdataset()">Export</button>

    </div>

    <div class="col-sm-11" id="stepContainer" >
      <table class="table">
        <thead>
          <tr>
            <th scope="col" >ID</th>
            <th scope="col" >Partner Intitution</th>
            <th scope="col" >Collection</th>
            <th scope="col" >Cataloguer</th>
            <th scope="col" >Version</th>
            <th scope="col" >Title</th>
            
            <th scope="col"></th>

          </tr>
        </thead>
        <tbody>
          <?php

          for($i = 0; ($i < $objItem->total && $i < $pagetotal) ; $i++){
            $objItem->go($i);

            $objCollection->gotoID($objItem->collection_id);
            $objCataloguer->gotoID($objItem->cataloguer_id);        
          ?>
              <tr>
              <td><?php echo($objItem->id)?></td>
              <td><?php echo($objCataloguer->institution)?></td>
              <td><?php echo($objCollection->source_collection)?></td>
              <td><?php echo( substr($objCataloguer->name,0,1) . ". " . $objCataloguer->lastname) ?></td>
              <td><?php echo($objItem->schema_version)?></td>
              <td><?php echo($objItem->title)?></td>
              <td style="width: 30px; text-aling: left" class="link">
                  <span class="glyphicon glyphicon-search" aria-hidden="true" data-toggle="modal" data-target="#previewModal" onclick="preview('<?php echo($objItem->id)?>')"></span>
              </td>

              </tr>
          <?php
              }
          ?>

          <tbody>
      <table>

      <!-- --------------------------------------------  Pagination  ------------------------------------------------- -->
      <div class="pagination-box">
          <?php 
            $total_pages = ceil($objItem->query_total / 15);
            for($i = 1; $i < $total_pages + 1; $i++){
                if($i == $page){
                  $active = 'pagination-active';
                }else{
                  $active = '';
                }
                echo("
                  <a class='pagination-link ".$active."' onclick='filter(".$i.")'> $i</a>
                ");
            }
          ?>
      </div>
 

</div>

<!-- ---------------------------------------- PREVIEW MODAL WINDOW ----------------------------------------------- -->

<!-- Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="previewModalTitle"></h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>

      </div>

      <div id="modal-main" class="modal-body">
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


</div>
<script src="View/export.js"></script>


<?php
$conn->close();
?>