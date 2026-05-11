<?php
require_once '../Models/db.php'; // bach y9leb 3la fichier oyinsere contenu 
require_once '../Models/favorie.php';//On charge d'abord la connexion à la base de données  //(db.php) pour avoir l'objet puis le modèle Favorie.php pour pouvoir utiliser la classe Favorie 

//initialisation
$favorieModel = new Favorie($pdo);
//creation d'une nouvelle instance (objet concret) fl classe favorie
//passe $pdo (la connexion à la base de données) dans le constructeur
//$favorieModel est prêt à faire des requêtes SQL


// On récupère l'ID de l'user connecté (via session )
$id_user = $_SESSION['user_id']; 

// On récupère les données
$mesFavoris = $favorieModel->consulter_list_favorie($id_user);
//exécuter et de stocker le résultat 


// Et on appelle la vue pour afficher tout  ça
include '../Views/liste.php'; 
?>