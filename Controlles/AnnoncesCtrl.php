<?php
require_once(__DIR__."/../Models/Annonces.php");
require_once(__DIR__."/../db.php");


$modelAnnonces=new annonces($db);

$lesAnnonces=$modelAnnonces->consulter();

include (__DIR__."/../Views/liste.php");

?>