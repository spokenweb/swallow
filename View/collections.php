<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once "../Model/db.config.php";
require_once "../Model/collection.php";
require_once "../Model/cataloguer.php";
require_once "../Model/session.php";
isLogged($conn);

$objCollection = new Collection($conn);
$objCollection->selectAll();

$objCataloguer = new Cataloguer($conn);
$objCataloguer->select($_SESSION["swallow_uid"]);

?>


<h2>Collections</h2>
<hr>

<div class="col-sm-11">

<table class="table ">
  <thead>
    <tr>
      <th scope="col" >ID</th>
      <th scope="col" >Partner Institution</th>
      <th scope="col" >Contributing Unit</th>
      <th scope="col" >Source Collection</th>
      <th scope="col"colspan="3"> <button id="btn_create_cataloguer" class="btn btn-primary" onclick="createCollection()">New Collection</button></th>
    </tr>
  </thead>
  <tbody>
    <?php
    for($i = 0; $i < $objCollection->total; $i++){
        $objCollection->go($i);
    ?>
        <tr>
        <th scope="row"><?php echo($objCollection->id)?></th>
        <th scope="row"><?php echo($objCollection->partner_institution)?></th>
        <td><?php echo($objCollection->contributing_unit)?></td>
        <td><?php echo($objCollection->source_collection)?></td>
        <td style="width: 30px; text-aling: left" class="link">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true" onclick="editCollection('<?php echo($objCollection->id)?>')"></span>
        </td>
        <?php
          if(isAdmin($conn) or ($objCataloguer->institution == $objCollection->partner_institution) ){
        ?>
        <td style="width: 30px; text-aling: left" class="link">
            <span class="glyphicon glyphicon-duplicate" aria-hidden="true" onclick="duplicateCollection('<?php echo($objCollection->id)?>')"></span>
        </td>
        <td style="text-aling: left" class="link">
            <span class="glyphicon glyphicon-trash" aria-hidden="true" onclick="deleteCollection('<?php echo($objCollection->id)?>')"></span></td>
        </tr>
        
      <?php
        }else{
        ?>
        <td style="width: 30px; text-aling: left" class="link">
            
        </td>
        <td style="text-aling: left" class="link">
            
        </tr>
        
        
        <?php
        }
      }
    ?>

    <tbody>
<table>

</div>
<script src="View/collections.js"></script>


<?php
$conn->close();
?>