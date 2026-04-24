<?php
require_once(__DIR__."/../Models/Annonces.php");
require_once(__DIR__."/../db.php");


$modelAnnonces=new annonces($db);
$lesAnnonces=$modelAnnonces->consulter();

$categories=$modelAnnonces->allCategorie();
$ville=$modelAnnonces->allVille();


if (isset($_POST["prix-min"]) && isset($_POST["prix-max"]) && !empty($_POST["prix-min"])) {
    //submit, katjib ghi l-annonces li m-filtrin
    $lesAnnonces = $modelAnnonces->filtrer_Prix($_POST["prix-min"], $_POST["prix-max"]);
} else {
    $lesAnnonces = $modelAnnonces->consulter();
}

//parti publier annonces
if(isset($_GET["action"]) && $_GET["action"]=="publier_ann"){
 
            require_once(__DIR__."/../Views/publier_annonce.php");
 
}
else{
        include (__DIR__."/../Views/liste.php");
}



?>