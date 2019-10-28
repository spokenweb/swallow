<?php

$prefix = "https://spokenweb-metadata-scheme.readthedocs.io/en/latest/";
$page = $_GET['page'];

$html = file_get_contents($prefix.$page);
echo($html);
?>