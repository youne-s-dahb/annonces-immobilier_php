<?php

//import des class
require_once(__DIR__ . "/../Models/User.php");
require_once(__DIR__ . "/../Models/Annonces.php");
require_once(__DIR__ . "/../db.php");

//demarrer session si elle n'est pas actif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userModel = new User($db);
$annonceModel = new annonces($db);
$errors = [];
$success = false;
$message = '';

//recuperer user connecte
$currentUser = $_SESSION['user'] ?? null;
$userAnnonces = [];

$action = $_GET['action'] ?? ($_POST['action'] ?? 'login');

//logout
if ($action === 'logout') {
    unset($_SESSION['user']);
    header('Location: /annonces_immobilier/Views/login.php');
    exit();
}

//s'inscrire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'register') {
    //recup les données du form
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $telephone = trim($_POST['telephone'] ?? '');

    //verify password
    if ($password !== $password_confirm) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    } else {
        $result = $userModel->register($nom, $prenom, $email, $password, $telephone);

        if (!empty($result['success'])) {
            $loginResult = $userModel->login($email, $password);

            if (!empty($loginResult['success']) && !empty($loginResult['user'])) {
                $_SESSION['user'] = $loginResult['user'];
                header('Location: /annonces_immobilier/Controlles/UserCtrl.php?action=profil');
                exit();
            }

            $success = true;
            $message = $result['message'] ?? 'Inscription réussie.';
        } else {
            $errors = $result['errors'] ?? [$result['message'] ?? 'Inscription impossible.'];
        }
    }

    include(__DIR__ . "/../Views/register.php");
    exit();
}

//login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $result = $userModel->login($email, $password);

    if (!empty($result['success']) && !empty($result['user'])) {
        $_SESSION['user'] = $result['user'];
        header('Location: /annonces_immobilier/Controlles/UserCtrl.php?action=profil');
        exit();
    }

    $errors[] = $result['message'] ?? 'Connexion impossible.';
    include(__DIR__ . "/../Views/login.php");
    exit();
}

if ($action === 'register') {
    include(__DIR__ . "/../Views/register.php");
    exit();
}

if ($action === 'profil') {
    if (empty($_SESSION['user']['id_user'])) {
        header('Location: /annonces_immobilier/Views/login.php');
        exit();
    }

    $currentUser = $_SESSION['user'];
    $userAnnonces = $annonceModel->consulterParUser((int) $currentUser['id_user']);
    include(__DIR__ . "/../Views/profil.php");
    exit();
}

include(__DIR__ . "/../Views/login.php");
?>