<?php
require_once "../Model/db.config.php";
require_once "../Model/session.php";
isLogged($conn);
?>

<h2>Import</h2>
<hr>

<div>
    <form id="applicationForm" method="post">
 
        <div class="form-group">
            <label for="fname" class="col-sm-2">Source File:</label>
            <input type="file" id="fname" name="fname">
        </div>

        <div class="form-group">
            <label for="map" class="col-sm-2">Mapping Function</label>
            <select name='map' class='form-control'>
                <option value=''>Select an option</option>
                <option value='../Maps/Import/swallow-csv.json'>Swallow CSV</option>
                <option value='../Maps/Import/swallow-json.json'>Swallow JSON V2 </option>
                <option value='../Maps/Import/swallow-json-v3.json'>Swallow JSON V3</option>
            </select>
        </div>

        <div class="form-group">
            <label class="col-sm-2">Preview </label>    
            <input type="checkbox" class="form-check-input" id="is_preview" name="is_preview">
        </div>

        <div class="form-group">
            <span class="col-sm-2"></span>
                <button type="submit" class="btn btn-primary">Import</button>
        </div>
    </form>
</div>

<div id="report">
    
</div>

<script src='View/import.js'></script>

<?php
    $conn->close();
?>