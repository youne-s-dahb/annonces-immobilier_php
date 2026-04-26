<?php
require_once(__DIR__."/../Models/Annonces.php");
require_once(__DIR__."/../db.php");


$modelAnnonces=new annonces($db);
<<<<<<< HEAD

$lesAnnonces=$modelAnnonces->consulter();

$categories=$modelAnnonces->allCategorie();

$ville=$modelAnnonces->allVille();

=======
$lesAnnonces=$modelAnnonces->consulter();

$categories=$modelAnnonces->allCategorie();
$ville=$modelAnnonces->allVille();


>>>>>>> origin/dev_test
if (isset($_POST["prix-min"]) && isset($_POST["prix-max"]) && !empty($_POST["prix-min"])) {
    //submit, katjib ghi l-annonces li m-filtrin
    $lesAnnonces = $modelAnnonces->filtrer_Prix($_POST["prix-min"], $_POST["prix-max"]);
} else {
<<<<<<< HEAD
    // fax ndkhel page yjib kolxy 
    $lesAnnonces = $modelAnnonces->consulter();
}

include (__DIR__."/../Views/liste.php");
=======
    $lesAnnonces = $modelAnnonces->consulter();
}

//parti publier annonces
if(isset( $_GET["action"]) && $_GET["action"] == "publier_ann" ){
            require_once(__DIR__."/../Views/publier_annonce.php");
}
elseif(isset($_GET["action"]) && $_GET["action"] == "save"){
    $id_annonce =$modelAnnonces->publier_annonce($_POST);

   if ($id_annonce) {
        // beaucoups d'images 
        if (!empty($_FILES['image']['name'][0])) {
            $files = $_FILES['image'];
            
            foreach ($files['name'] as $key => $val) {
                $tmp_name = $files['tmp_name'][$key];
                $file_name = time() . "_" . $files['name'][$key];
                $destination = "../assets/img/" . $file_name;

                if (move_uploaded_file($tmp_name, $destination)) {
                    // bax n liee kol image b id_annonces 
                    $modelAnnonces->publier_image($file_name, $id_annonce);
                }
            }
        }
        
        header("Location: ../index.php");
        exit();
    }
}
else{
        include (__DIR__."/../Views/liste.php");
}


>>>>>>> origin/dev_test

?>