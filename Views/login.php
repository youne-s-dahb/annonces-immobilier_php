<?php 
if (!isset($errors)) $errors = [];
if (!isset($success)) $success = false;
if (!isset($message)) $message = '';

include(__DIR__ . "/header.php"); 
?>

<main class="page-shell">
    <div class="container auth-container">
        <div class="auth-card">
            <!-- Messages d'état -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✓</span>
                    <span><?php echo htmlspecialchars($message); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">!</span>
                    <div class="alert-content">
                        <?php foreach ($errors as $error): ?>
                            <p><?php echo htmlspecialchars($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Formulaire -->
            <div class="auth-form-wrapper">
                <div class="auth-header">
                    <h1>Se connecter</h1>
                    <p>Accédez à votre compte pour gérer vos annonces</p>
                </div>

                <form method="POST" action="/annonces_immobilier/Controlles/UserCtrl.php" class="auth-form">
                    <input type="hidden" name="action" value="login">

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-input"
                            placeholder="votre@email.com"
                            required
                            autocomplete="email"
                            value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        >
                    </div>

                    <!-- Mot de passe -->
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                        >
                    </div>

                    <!-- Bouton -->
                    <button type="submit" class="btn-auth">
                        Se connecter
                    </button>
                </form>

                <!-- Lien inscription -->
                <p class="auth-footer">
                    Pas encore de compte? 
                    <a href="/annonces_immobilier/Views/register.php" class="auth-link">S'inscrire</a>
                </p>
            </div>
        </div>
    </div>
</main>

<?php include(__DIR__ . "/footer.php"); ?>