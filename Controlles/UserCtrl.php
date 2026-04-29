<?php
session_start();

require_once(__DIR__ . "/../Models/User.php");
require_once(__DIR__ . "/../Models/Annonces.php");
require_once(__DIR__ . "/../db.php");

class UserController {
    private User $userModel;
    private Annonces $annonceModel;
    
    public function __construct($db) {
        $this->userModel = new User($db);
        $this->annonceModel = new Annonces($db);
    }
    
    public function handleRequest() {
        $action = $this->getAction();
        
        // Actions qui nécessitent une authentification
        $protectedActions = ['profil', 'update_profil'];
        if (in_array($action, $protectedActions) && !$this->isAuthenticated()) {
            $this->redirectToLogin();
        }
        
        // Routage des actions
        $method = "handle{$action}";
        if (method_exists($this, $method)) {
            $this->$method();
        } else {
            $this->showLogin();
        }
    }
    
    private function getAction(): string {
        return $_GET['action'] ?? ($_POST['action'] ?? 'login');
    }
    
    private function isAuthenticated(): bool {
        return isset($_SESSION['user']['id_user']);
    }
    
    private function getCurrentUser(): ?array {
        return $_SESSION['user'] ?? null;
    }
    
    private function redirectToLogin(): void {
        http_response_code(401);
        header('Location: /annonces_immobilier/Views/login.php');
        exit();
    }
    
    private function redirectToProfile(): void {
        header('Location: /annonces_immobilier/Controlles/UserCtrl.php?action=profil');
        exit();
    }
    
    // MODIFICATION DE PROFIL (AJAX)
    private function handleUpdate_profil(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(false, 'Méthode non autorisée', 405);
        }
        
        $userId = (int) ($_SESSION['user']['id_user'] ?? 0);
        $data = $this->sanitizeInput([
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telephone' => $_POST['telephone'] ?? ''
        ]);
        
        $result = $this->userModel->modifierProfil(
            $userId, 
            $data['nom'], 
            $data['prenom'], 
            $data['email'], 
            $data['telephone']
        );
        
        header('Content-Type: application/json');
        
        if ($result['success']) {
            $this->updateSessionUser($data);
            $this->jsonResponse(true, 'Profil mis à jour avec succès!');
        } else {
            $this->jsonResponse(false, $result['message'], 400);
        }
    }
    
    // INSCRIPTION
    private function handleRegister(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processRegister();
        } else {
            $this->showRegister();
        }
    }
    
    private function processRegister(): void {
        $data = $this->sanitizeInput([
            'nom' => $_POST['nom'] ?? '',
            'prenom' => $_POST['prenom'] ?? '',
            'email' => $_POST['email'] ?? '',
            'telephone' => $_POST['telephone'] ?? ''
        ]);
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';
        
        $errors = $this->validateRegister($data, $password, $passwordConfirm);
        
        if (empty($errors)) {
            $result = $this->userModel->register(
                $data['nom'], 
                $data['prenom'], 
                $data['email'], 
                $password, 
                $data['telephone']
            );
            
            if ($result['success']) {
                $loginResult = $this->userModel->login($data['email'], $password);
                if ($loginResult['success']) {
                    $_SESSION['user'] = $loginResult['user'];
                    $this->redirectToProfile();
                }
            }
            $errors = $result['errors'] ?? [$result['message'] ?? 'Erreur inscription'];
        }
        
        $_SESSION['register_errors'] = $errors;
        $this->showRegister();
    }
    
    private function validateRegister(array $data, string $password, string $passwordConfirm): array {
        $errors = [];
        
        if (empty($data['nom']) || empty($data['prenom']) || empty($data['email'])) {
            $errors[] = 'Tous les champs obligatoires doivent être remplis.';
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        
        if (strlen($password) < 6) {
            $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
        }
        
        if ($password !== $passwordConfirm) {
            $errors[] = 'Les mots de passe ne correspondent pas.';
        }
        
        return $errors;
    }
    
    // CONNEXION
    private function handleLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        } else {
            $this->showLogin();
        }
    }
    
    private function processLogin(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $result = $this->userModel->login($email, $password);
        
        if ($result['success'] && !empty($result['user'])) {
            $_SESSION['user'] = $result['user'];
            $this->redirectToProfile();
        }
        
        $_SESSION['login_errors'] = [$result['message'] ?? 'Identifiants incorrects'];
        $this->showLogin();
    }
    
    // PROFIL
    private function handleProfil(): void {
        $currentUser = $this->getCurrentUser();
        $userAnnonces = $this->annonceModel->consulterParUser((int) $currentUser['id_user']);
        
        include(__DIR__ . "/../Views/profil.php");
        exit();
    }
    
    // DÉCONNEXION
    private function handleLogout(): void {
        session_destroy();
        header('Location: /annonces_immobilier/Views/login.php');
        exit();
    }
    
    private function showLogin(): void {
        $errors = $_SESSION['login_errors'] ?? [];
        unset($_SESSION['login_errors']);
        include(__DIR__ . "/../Views/login.php");
    }
    
    private function showRegister(): void {
        $errors = $_SESSION['register_errors'] ?? [];
        unset($_SESSION['register_errors']);
        include(__DIR__ . "/../Views/register.php");
    }
    
    private function sanitizeInput(array $data): array {
        return array_map('trim', array_map('htmlspecialchars', $data));
    }
    
    private function updateSessionUser(array $data): void {
        $_SESSION['user']['nom'] = $data['nom'];
        $_SESSION['user']['prenom'] = $data['prenom'];
        $_SESSION['user']['email'] = $data['email'];
    }
    
    private function jsonResponse(bool $success, string $message, int $code = 200): void {
        http_response_code($code);
        echo json_encode([
            'success' => $success,
            'message' => $message
        ]);
        exit();
    }
}

// Initialisation et exécution
try {
    $controller = new UserController($db);
    $controller->handleRequest();
} catch (Exception $e) {
    error_log("UserController Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
}
?>