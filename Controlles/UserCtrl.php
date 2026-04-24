<?php

    use User;

    require_once(__DIR__ . "/../Models/User.php");
    require_once(__DIR__ . "/../db.php");

    session_start();

    $userModel = new User($db);
    $errors = [];
    $success = false;
    $message = '';

    //register
    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action']) && $_POST['action']==='register'){
        //njibo data mn form, ila ma kantch kat3mer b valeur khawya
        $nom = $_POST['nom'] ?? '';
        $prenom = $_POST['prenom'] ?? '';
        $email = $_POST['nom'] ?? '';
        $password = $_POST['nom'] ?? '';
        $password_confirm = $_POST['nom'] ?? '';
        $telephone = $_POST['nom'] ?? '';
    }

    // Vérifier que les mots de passe correspondent

?>