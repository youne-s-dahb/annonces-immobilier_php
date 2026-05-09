<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUser = $_SESSION['user'] ?? null;
$userLabel = '';
$userInitial = 'U';

if (!empty($currentUser)) {
    $userLabel = trim(($currentUser['prenom'] ?? '') . ' ' . ($currentUser['nom'] ?? ''));
	if ($userLabel !== '') {
		$userInitial = strtoupper(substr($userLabel, 0, 1));
	}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>SMSAR | Annonces Immobilieres</title>
	<link rel="stylesheet" href="assets/css/style.css">

	<link rel="stylesheet" href="/annonces_immobilier/assets/css/style.css">
<<<<<<< HEAD

=======
	<link rel="stylesheet" href="/annonces_immobilier/assets/css/modifierInfo.css">
>>>>>>> c3f6acece62ad7706ba838083c566e7388776e85
</head>
<body>
	<header class="topbar" id="accueil">
		<div class="container topbar-inner">
			<a href="/annonces_immobilier/index.php" class="logo" aria-label="Accueil Smsar">
				<span class="logo-mark" aria-hidden="true"></span>
				<span class="logo-text">
					<span><b>Smsar </b></span>
				</span>
			</a>
		
			<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="main-menu" aria-label="Ouvrir le menu">
				<span class="menu-toggle-line" aria-hidden="true"></span>
				<span class="menu-toggle-line" aria-hidden="true"></span>
				<span class="menu-toggle-line" aria-hidden="true"></span>
			</button>
			<nav class="menu" id="main-menu" aria-label="Navigation principale">
				<a href="/annonces_immobilier/index.php">Accueil</a>
				<a href="#">Acheter</a>
				<a href="#">Louer</a>
				<a href="#">Agences</a>
				<a href="#">Contact</a>
			</nav>
			<div class="topbar-actions">
				<a class="btn btn-light" href="#">Se connecter</a>
				<a class="btn btn-primary" href="#">Publier une annonce</a>
				<a class="btn btn-register" href="#">S'inscrire</a>
				<a class="btn btn-primary" href="Controlles/AnnoncesCtrl.php?action=publier_ann">Publier une annonce</a>

				<?php if (!empty($currentUser)): ?>
					<a class="btn btn-profile-royal" href="/annonces_immobilier/Controlles/UserCtrl.php?action=profil">
						<span class="profile-chip-dot" aria-hidden="true"><?php echo htmlspecialchars($userInitial); ?></span>
						<span>Profil<?php echo $userLabel !== '' ? ' - ' . htmlspecialchars($userLabel) : ''; ?></span>
					</a>
					<a class="btn btn-register" href="/annonces_immobilier/Controlles/UserCtrl.php?action=logout">Déconnexion</a>
					<a class="btn btn-primary" href="/annonces_immobilier/Controlles/AnnoncesCtrl.php?action=publier_ann">Publier une annonce</a>
				<?php else: ?>
					<a class="btn btn-light" href="/annonces_immobilier/Views/login.php">Se connecter</a>
					<a class="btn btn-register" href="/annonces_immobilier/Views/register.php">S'inscrire</a>
					<a class="btn btn-primary" href="/annonces_immobilier/Views/login.php">Publier une annonce</a>
				<?php endif; ?>
			</div>
			
		</div>
	</header>