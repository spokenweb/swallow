<?php
require_once "../Model/db.config.php";
require_once "../Model/session.php";
isLogged($conn);



?>

<h2>Help - Search Syntax</h2>
<p>Search for those items that have a particular word anywhere in the metadata by entering a search term and clicking on Execute,
for example:
<ul><li><p>poetry</p></li></ul>
</p>
<p>It is also possible to search using Boolean operators (+AND+, +OR+) for items that have a cobination of words, for example:</p>
<ul>
<li><p>Dylan+AND+vinyl</p></li>
<li><p>Dylan+OR+Ginsberg</p></li>
</ul>
<hr>


<?php
    $conn->close();
?>