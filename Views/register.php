<?php 
if (!isset($errors)) $errors = [];
if (!isset($success)) $success = false;
if (!isset($message)) $message = '';

include(__DIR__ . "/header.php"); 
?>

<main class="page-shell">
    <div class="container auth-container">
        <div class="auth-card auth-card-register">
            <!-- Messages d'état -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✓</span>
                    <span><?php echo htmlspecialchars($message); ?> Redirection...</span>
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
                    <h1>Créer un compte</h1>
                    <p>Inscrivez-vous pour publier vos annonces</p>
                </div>

                <form method="POST" action="../Controlles/UserCtrl.php" class="auth-form" id="registerForm">
                    <input type="hidden" name="action" value="register">

                    <!-- Nom -->
                    <div class="form-group">
                        <label for="nom" class="form-label">
                            Nom
                            <span class="form-hint">(3-30 caractères)</span>
                        </label>
                        <input
                            type="text"
                            id="nom"
                            name="nom"
                            class="form-input"
                            placeholder="Dupont"
                            required
                            minlength="3"
                            maxlength="30"
                            value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>"
                        >
                    </div>

                    <!-- Prénom -->
                    <div class="form-group">
                        <label for="prenom" class="form-label">
                            Prénom
                            <span class="form-hint">(3-30 caractères)</span>
                        </label>
                        <input
                            type="text"
                            id="prenom"
                            name="prenom"
                            class="form-input"
                            placeholder="Jean"
                            required
                            minlength="3"
                            maxlength="30"
                            value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>"
                        >
                    </div>

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

                    <!-- Téléphone -->
                    <div class="form-group">
                        <label for="telephone" class="form-label">
                            Téléphone
                            <span class="form-hint">(+212 6/7 ou 06/07)</span>
                        </label>
                        <input
                            type="tel"
                            id="telephone"
                            name="telephone"
                            class="form-input"
                            placeholder="+212 612345678"
                            required
                            value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>"
                        >
                    </div>

                    <!-- Mot de passe -->
                    <div class="form-group">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input form-input-password"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        >
                        <div class="password-hint">
                            <p class="hint-title">Critères requis:</p>
                            <ul class="hint-list">
                                <li id="hint-length">Minimum 8 caractères</li>
                                <li id="hint-lower">Minimum une lettre minuscule (a-z)</li>
                                <li id="hint-upper">Minimum une lettre majuscule (A-Z)</li>
                                <li id="hint-number">Minimum un chiffre (0-9)</li>
                                <li id="hint-special">Minimum un caractère spécial (!@#$%^&* etc)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Confirmation mot de passe -->
                    <div class="form-group">
                        <label for="password_confirm" class="form-label">Confirmer le mot de passe</label>
                        <input
                            type="password"
                            id="password_confirm"
                            name="password_confirm"
                            class="form-input"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    <!-- Bouton -->
                    <button type="submit" class="btn-auth">
                        S'inscrire
                    </button>
                </form>

                <!-- Lien connexion -->
                <p class="auth-footer">
                    Vous avez déjà un compte? 
                    <a href="login.php" class="auth-link">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</main>

<script>
// Validation en temps réel du mot de passe
const passwordInput = document.getElementById('password');
const hints = {
    length: document.getElementById('hint-length'),
    lower: document.getElementById('hint-lower'),
    upper: document.getElementById('hint-upper'),
    number: document.getElementById('hint-number'),
    special: document.getElementById('hint-special')
};

function updatePasswordHints() {
    const pwd = passwordInput.value;

    const checks = {
        length: pwd.length >= 8,
        lower: /[a-z]/.test(pwd),
        upper: /[A-Z]/.test(pwd),
        number: /[0-9]/.test(pwd),
        special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(pwd)
    };

    Object.entries(checks).forEach(([key, valid]) => {
        if (valid) {
            hints[key].classList.add('hint-valid');
            hints[key].classList.remove('hint-invalid');
        } else {
            hints[key].classList.add('hint-invalid');
            hints[key].classList.remove('hint-valid');
        }
    });
}

passwordInput.addEventListener('input', updatePasswordHints);
passwordInput.addEventListener('change', updatePasswordHints);
</script>

<?php include(__DIR__ . "/footer.php"); ?>