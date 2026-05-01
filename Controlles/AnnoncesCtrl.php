<?php
require_once(__DIR__."/../Models/Annonces.php");
require_once(__DIR__."/../db.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$modelAnnonces=new annonces($db);

$lesAnnonces=$modelAnnonces->consulter();

$categories=$modelAnnonces->allCategorie();

$ville=$modelAnnonces->allVille();
$lesAnnonces=$modelAnnonces->consulter();

$categories=$modelAnnonces->allCategorie();
$ville=$modelAnnonces->allVille();
if (isset($_POST["prix-min"]) && isset($_POST["prix-max"]) && !empty($_POST["prix-min"])) {
    //submit, katjib ghi l-annonces li m-filtrin
    $lesAnnonces = $modelAnnonces->filtrer_Prix($_POST["prix-min"], $_POST["prix-max"]);
} else {
    // fax ndkhel page yjib kolxy 
    $lesAnnonces = $modelAnnonces->consulter();
}

include (__DIR__."/../Views/liste.php");

    $lesAnnonces = $modelAnnonces->consulter();
}

//parti publier annonces
if(isset( $_GET["action"]) && $_GET["action"] == "publier_ann" ){
          if (empty($_SESSION['user']['id_user'])) {
             header("Location: /annonces_immobilier/Controlles/UserCtrl.php?action=login");
             exit();
          }
            require_once(__DIR__."/../Views/publier_annonce.php");
}
elseif(isset($_GET["action"]) && $_GET["action"] == "save"){
   if (empty($_SESSION['user']['id_user'])) {
       header("Location: /annonces_immobilier/Controlles/UserCtrl.php?action=login");
       exit();
   }

   $_POST['id_user'] = $_SESSION['user']['id_user'];
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
elseif(isset($_GET["action"]) && $_GET["action"] == "profil"){
    header("Location: /annonces_immobilier/Controlles/UserCtrl.php?action=profil");
    exit();
}
else{
        include (__DIR__."/../Views/liste.php");
}


 origin/dev_test

?>