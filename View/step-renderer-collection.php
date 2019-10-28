<?php
require_once "../Model/db.config.php";
require_once "../Model/session.php";
require_once "../Model/collection.php";
require_once "../Model/item.php";
isLogged($conn);

$objCollection = new Collection($conn);
$objCollection->select( $_GET['collectionid']);

$objItem = new Item($conn);
$objItem->select($_GET['itemid'])
?>

<div class="form-group">
    <label for="contributing_unit"  class="col-sm-3">Contributing Unit</label>
    <input type="text" class="form-control" id="contributing_unit" name="contributing_unit" value="<?php echo($objCollection->contributing_unit)?>" disabled>
</div>

<div class="form-group">
    <label for="source_collection" class="col-sm-3">Source Collection</label>
    <input type="text" class="form-control" id="source_collection" name="source_collection" value="<?php echo($objCollection->source_collection)?>" disabled>
</div>

<div class="form-group">
    <label for="source_collection_description" class="col-sm-3">Source Collection Description</label>
    <textarea type="text" class="form-control" id="source_collection_description" name="source_collection_description" rows="5" disabled>
        <?php echo($objCollection->source_collection_description)?>
    </textarea>
</div>

<div class="form-group">
    <label for="source_collection_ID" class="col-sm-3">Source Collection ID</label>
    <input type="text" class="form-control" id="source_collection_ID" name="source_collection_ID" value="<?php echo($objCollection->source_collection_ID)?>" disabled>
</div>

<div class="form-group">
    <label for="persistent URL" class="col-sm-3">persistent URL</label>
    <input type="text" class="form-control" id="persistent URL" name="persistent URL" value="<?php echo ($objItem->getValue('Institution_and_Collection','persistent URL'))?>" >
</div>

<div class="form-group">
    <label for="item ID" class="col-sm-3">item ID</label>
    <input type="text" class="form-control" id="item ID" name="item ID" value="<?php echo ($objItem->getValue('Institution_and_Collection','item ID'))?>" >
</div>

<?php
$conn->close();
?>