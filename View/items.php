<?php
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
$orderby = '';

if(isset($_GET['page']) ){
  $page = $_GET['page'];
  $_SESSION['swallow_items_page'] = $_GET['page'];
}else{
  if(isset($_SESSION['swallow_items_page'])){
    $page = $_SESSION['swallow_items_page'];
  }
}

if(isset($_GET['cataloguer'])){
  $cataloguer = $_GET['cataloguer'];
  $_SESSION['swallow_items_cataloguer'] = $_GET['cataloguer'];
}else{
  if(isset($_SESSION['swallow_items_cataloguer'])){
    $cataloguer = $_SESSION['swallow_items_cataloguer'];
  }
}

if(isset($_GET['institution'])){
  $institution = $_GET['institution'];
  $_SESSION['swallow_items_institution'] = $_GET['institution'];
}else{
  if(isset($_SESSION['swallow_items_institution'])){
    $institution = $_SESSION['swallow_items_institution'];
  }
}

if(isset($_GET['collection'])){
  $collection = $_GET['collection'];
  $_SESSION['swallow_items_collection'] = $_GET['collection'];
}else{
  if(isset($_SESSION['swallow_items_collection'])){
    $collection = $_SESSION['swallow_items_collection'];
  }
}


if(isset($_GET['orderby'])){
  $orderby = $_GET['orderby'];
  $_SESSION['swallow_items_orderby'] = $_GET['orderby'];
}else{
  if(isset($_SESSION['swallow_items_orderby'])){
    $orderby = $_SESSION['swallow_items_orderby'];
  }
}

if(isset($_GET['metadataquery'])){
  $metadataquery = base64_decode($_GET['metadataquery']);
  $_SESSION['swallow_items_metadataquery'] = base64_decode($_GET['metadataquery']);
}else{
  if(isset($_SESSION['swallow_items_metadataquery'])){
    $metadataquery = $_SESSION['swallow_items_metadataquery'];
  }
}

$objItem->metadataQuery($metadataquery,$institution,$cataloguer,$collection,$page,$orderby);


$objCollection = new Collection($conn);
$objCollection->selectAll();

$objCataloguer = new Cataloguer($conn);
$objCataloguer->selectAll();

$objLoggedCataloguer = new Cataloguer($conn);
$objLoggedCataloguer->select($_SESSION['swallow_uid']);


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


<h2>Items</h2>
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
                <select id="f_institution" class="form-control" style="min-width:130px;" onchange="filter(1)">
                  <option value='-1' >INSTITUTION</option> 
                  <?php
                    fillInstitution($institution);
                  ?>
                </select>
              </td> 

              <td>
                <select id="f_cataloguer" class="form-control" style="min-width:150px;" onchange="filter(1)">
                  <option value='-1' >CATALOGUER</option> 
                  <?php
                    fillCataloguer($institution,$objCataloguer,$cataloguer);
                  ?>
                </select>
              </td>

              <td>
                <select id="f_collection" class="form-control" style="min-width:130px;" onchange="filter(1)">
                  <option value='-1' >COLLECTION</option> 
                  <?php
                    fillCollection($institution,$objCollection,$collection);
                  ?> 
                </select>
              </td>

              <td>
                <div style="margin-top:-20px;"><span class = "label label-default">Order by:</span>
                <select id="f_orderby" class="form-control sort-style" style="min-width:130px;" onchange="filter(1)">
                  <?php
                    $f_orderby_selected = array('','','','');
                    if(isset($orderby)){
                      if($orderby == '') {$f_orderby_selected[0] = 'selected';}
                      if($orderby == 'last_modified') {$f_orderby_selected[1] = 'selected';}
                      if($orderby == 'create_date') {$f_orderby_selected[2] = 'selected';}
                      if($orderby == 'title') {$f_orderby_selected[3] = 'selected';}
                    }else{
                      $f_orderby_selected[0] = 'selected';
                    }
                  ?>
                  <option value='' <?php echo($f_orderby_selected[0])?> >Default (Fast)</option>
                  <option value='last_modified' <?php echo($f_orderby_selected[1])?> >Last Modified</option>
                  <option value='create_date' <?php echo($f_orderby_selected[2])?>> Creation Date</option>
                  <option value='title' <?php echo($f_orderby_selected[3])?> >Title</option> 
                  
                </select>
                </div>
              </td>

            </tr>
            </thead>
          </table>

       
        <div class='form-group'>
            <span class="glyphicon glyphicon-search" style=" font-size: 25px; margin-top:10px; margin-right:20px"></span>
            <span class="glyphicon glyphicon-question-sign" style="cursor:pointer" data-toggle="modal" data-target="#helpModal" onclick="loadhelp(&quot;/view/help_search.php &quot;)"></span>
            
            <input type='text' class='form-control' style='max-width: 600px;' id='metadaquery' name='metadaquery' value="<?php echo($metadataquery)?>">
            <button type='button' class='btn btn-primary' onclick="query(1)">Execute</button>
        </div>
       
    </div>

<?php
  if(isAdmin($conn)){
?>
    <div class="col-sm-11 border-box" style="text-align:center; padding:10px; background-color:#ccc">

        <b>Batch delete all this items  </b>
        <button type='button' class='btn btn-primary' onclick="deletedataset()" style="margin-left: 20px">Delete
        </button>
        <div id="batchDeleteStatus"></div>

    </div>

<?php
  }
?>

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
            
            <th scope="col"colspan="4"> <button id="btn_create_cataloguer" class="btn btn-primary" style="width:100%" onclick="createItem()">New Item</button></th>

          </tr>
        </thead>
        <tbody>
          <?php
          
          for($i = 0; ($i < $objItem->total && $i < $pagetotal) ; $i++){
            $objItem->go($i);

            $collectionFound = $objCollection->gotoID($objItem->collection_id);
            $objCataloguer->gotoID($objItem->cataloguer_id);        
          ?>
              <tr>
              <td><?php echo($objItem->id)?></td>
              <td><?php echo($objCataloguer->institution)?></td>
              <td><?php if( $collectionFound){ echo($objCollection->source_collection); }?></td>
              <td><?php echo( substr($objCataloguer->name,0,1) . ". " . $objCataloguer->lastname) ?></td>
              <td><?php echo($objItem->schema_version)?></td>
              <td><?php echo($objItem->title)?></td>
              <td style="width: 30px; text-aling: left" class="link">
                  <span class="glyphicon glyphicon-search" aria-hidden="true" data-toggle="modal" data-target="#previewModal" onclick="preview('<?php echo($objItem->id)?>')"></span>
              </td>

              <?php
              if( ($objItem->locked == 0 ) or ($objItem->locked == 1 and $objItem->cataloguer_id == $_SESSION['swallow_uid']) or (isAdmin($conn)) ){
              
                if( $objLoggedCataloguer->role > 0 or $objItem->cataloguer_id == $_SESSION['swallow_uid'] or ($objCollection->partner_institution == $objLoggedCataloguer->institution)){
              ?>

             <td style="width: 30px; text-aling: left" class="link">
                  <span class="glyphicon glyphicon-pencil" aria-hidden="true" onclick="editItem('<?php echo($objItem->id)?>')"></span>
              </td>
              <td style="width: 30px; text-aling: left" class="link">
                  <span class="glyphicon glyphicon-duplicate" aria-hidden="true" onclick="duplicateItem('<?php echo($objItem->id)?>')"></span>
              </td>
              <?php
                }
              ?>
              
              <?php
              
              if($objLoggedCataloguer->role > 0  or $objItem->cataloguer_id == $_SESSION['swallow_uid']){
              ?>  
                <td style="text-aling: left" class="link">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true" onclick="deleteItem('<?php echo($objItem->id)?>')"></span></td>
                </tr>
                
            <?php
              }else{ // if is not an administrator  or the owner => cannot delete items
            ?>
              <td style="text-aling: left" class="link">
                 
              </tr>
            <?php
              }           
            }else{
            ?>
            
            <td style="width: 30px; text-aling: left" class="link">            
              <span class="glyphicon glyphicon-lock" aria-hidden="true" ></span>
            </td>

            <td style="width: 30px; text-aling: left" class="link">
                  <span class="glyphicon glyphicon-duplicate" aria-hidden="true" onclick="duplicateItem('<?php echo($objItem->id)?>')"></span>
            </td>

            <td style="text-aling: left" class="link"> </td>
              
          </tr>
          
        <?php
           }//}else{
        } //for($i = 0; ($i < $objItem->total && $i < $pagetotal) ; $i++){
        ?>
          

          <tbody>
      <table>

      <!-- --------------------------------------------  Pagination  ------------------------------------------------- -->
      <div class="pagination-box">
          <?php
            
           // if($hasFilter){
              
              #determine the number of pages
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
        //    }
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
<script src="View/items.js"></script>


<!-- Modal -->
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

<script>
function loadhelp(in_url){
    document.getElementsByName('helpframe')[0].src = in_url;
}

</script>


<?php

$conn->close();

?>