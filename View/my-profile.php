<?php
require_once "../Model/db.config.php";
require_once "../Model/session.php";
require_once "../Model/cataloguer.php";
isLogged($conn);

$objCataloguer = new Cataloguer($conn);
$objCataloguer->select( $_SESSION['swallow_uid']);
?>

<h2>My Profile</h2>
<hr>

<div>

    <form id="applicationForm">
    <input type="hidden" class="form-control"  id="id" name="id" value="<?php echo($objCataloguer->id)?>">


        <div class="form-group">
            <label for="fname" class="col-sm-2">Name</label>
            <input type="text" class="form-control" id="fname" name="fname" value="<?php echo($objCataloguer->name)?>">
        </div>

        <div class="form-group">
            <label for="lname"  class="col-sm-2">Last Name</label>
            <input type="text" class="form-control" id="lname" name="lname" value="<?php echo($objCataloguer->lastname)?>">
        </div>

        <div class="form-group">
            <label for="email" class="col-sm-2">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="<?php echo($objCataloguer->email)?>">
        </div>

        <div class="form-group">
            <label for="pwd1" class="col-sm-2">Password</label>
            <input type="password" class="form-control" id="pwd1" name="pwd1" value="">
        </div>

         <div class="form-group">
            <label for="pwd2"  class="col-sm-2">Confirm Password</label>
            <input type="password" class="form-control" id="pwd2" name = "pwd2" value="">
        </div>

         <div class="form-group">
            <label for="institution" class="col-sm-2">Institution</label>
            <?php
                 echo("<select name='institution' class='form-control'>");
                 echo("<option value=''>Select an option</option>");

                 $contents = file_get_contents("../Vocabulary/PartnerInstitution.json"); 
                 $contents = utf8_encode($contents); 
                 $vocabulary = json_decode($contents);
 
                 if($vocabulary !== NULL){
 
                     foreach($vocabulary->values as $value){
                         $selected = "";
                         if($value == $objCataloguer->institution) {
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
            <span class="col-sm-2"></span>
                <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<script src="View/my-profile.js"></script>
<?php
    $conn->close();
?>