<?php
if (!isset($currentUser)) {
    $currentUser = $_SESSION['user'] ?? null;
}

if (!isset($userAnnonces)) {
    $userAnnonces = [];
}

$resolveImageSrc = static function (string $image): string {
    $cleanImage = trim($image);

    if ($cleanImage === '') {
        return '';
    }

    if (preg_match('/^(https?:\/\/|\/)/i', $cleanImage) === 1) {
        return $cleanImage;
    }

    if (strpos($cleanImage, 'assets/img/') === 0) {
        return '/annonces_immobilier/' . $cleanImage;
    }

    return '/annonces_immobilier/assets/img/' . $cleanImage;
};

$fullName = trim(($currentUser['nom'] ?? '') . ' ' . ($currentUser['prenom'] ?? ''));
if ($fullName === '') {
    $fullName = 'Mon compte';
}

$userInitial = strtoupper(substr($fullName, 0, 1));
$isAdmin = strtolower((string) ($currentUser['role'] ?? 'user')) === 'admin';
$publishedCount = count($userAnnonces);
$latestLabel = 'Aucune annonce pour le moment';

if (!empty($userAnnonces)) {
    $latestRawDate = $userAnnonces[0]['Date_publication'] ?? $userAnnonces[0]['Date_Publication'] ?? '';
    $latestTimestamp = strtotime((string) $latestRawDate);
    if ($latestTimestamp !== false) {
        $latestLabel = date('d/m/Y', $latestTimestamp);
    }
}

include(__DIR__ . "/header.php");
?>

<main class="page-shell">
    <div class="container listing-layout profile-layout">
        <section class="results-panel profile-panel" aria-label="Profil utilisateur">
            <div class="profile-hero">
                <div class="profile-hero-main">
                    <div class="profile-avatar" aria-hidden="true"><?php echo htmlspecialchars($userInitial); ?></div>
                    <div class="profile-copy">
                        <p class="profile-kicker">Espace personnel</p>
                        <h1><?php echo htmlspecialchars($fullName); ?></h1>
                        <p><?php echo htmlspecialchars($currentUser['email'] ?? ''); ?></p>
                        <div class="profile-badges">
                            <span class="profile-badge">Compte <?php echo $isAdmin ? 'admin' : 'user'; ?></span>
                            <span class="profile-badge profile-badge-soft"><?php echo $publishedCount; ?> annonces</span>
                        </div>
                    </div>
                </div>

                <div class="profile-hero-actions">
                    <a class="profile-action profile-action-primary" href="/annonces_immobilier/Controlles/AnnoncesCtrl.php?action=publier_ann">
                        <span class="profile-action-icon" aria-hidden="true">+</span>
                        Publier une annonce
                    </a>
                    <a class="profile-action" href="#profile-settings">
                        <span class="profile-action-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.14 12.94a7.48 7.48 0 0 0 .05-.94 7.48 7.48 0 0 0-.05-.94l2.03-1.58a.5.5 0 0 0 .12-.63l-1.92-3.32a.5.5 0 0 0-.6-.22l-2.39.96a7.28 7.28 0 0 0-1.63-.94l-.36-2.54a.5.5 0 0 0-.49-.42h-3.84a.5.5 0 0 0-.49.42l-.36 2.54a7.28 7.28 0 0 0-1.63.94l-2.39-.96a.5.5 0 0 0-.6.22L2.7 7.85a.5.5 0 0 0 .12.63l2.03 1.58c-.03.31-.05.63-.05.94s.02.63.05.94L2.82 13.52a.5.5 0 0 0-.12.63l1.92 3.32c.13.23.4.32.6.22l2.39-.96c.51.39 1.05.7 1.63.94l.36 2.54a.5.5 0 0 0 .49.42h3.84a.5.5 0 0 0 .49-.42l.36-2.54c.58-.24 1.12-.55 1.63-.94l2.39.96c.2.1.47.01.6-.22l1.92-3.32a.5.5 0 0 0-.12-.63l-2.03-1.58ZM12 15.6A3.6 3.6 0 1 1 12 8.4a3.6 3.6 0 0 1 0 7.2Z"/></svg>
                        </span>
                        Modifier le profil
                    </a>
                    <a class="profile-action" href="/annonces_immobilier/Controlles/UserCtrl.php?action=logout">
                        <span class="profile-action-icon" aria-hidden="true">↩</span>
                        Déconnexion
                    </a>
                </div>
            </div>

            <div class="profile-stats">
                <article class="profile-stat-card">
                    <span class="profile-stat-label">Annonces publiées</span>
                    <strong><?php echo $publishedCount; ?></strong>
                </article>
                <article class="profile-stat-card">
                    <span class="profile-stat-label">Dernière activité</span>
                    <strong><?php echo htmlspecialchars($latestLabel); ?></strong>
                </article>
                <article class="profile-stat-card">
                    <span class="profile-stat-label">Téléphone</span>
                    <strong><?php echo htmlspecialchars($currentUser['telephone'] ?? ''); ?></strong>
                </article>
            </div>

            <section class="profile-settings" id="profile-settings" aria-label="Paramètres du compte">
                <div class="results-head profile-section-head">
                    <div>
                        <h2>Paramètres rapides</h2>
                        <p>Modifier, gérer et organiser ton espace annonceur.</p>
                    </div>
                </div>

                <div class="profile-settings-grid">
                    <button type="button" class="profile-settings-card profile-settings-card-btn" data-profile-open="edit-profile">
                        <span class="profile-settings-icon" aria-hidden="true">✎</span>
                        <strong>Modifier mes infos</strong>
                        <span>Nom, email, téléphone</span>
                    </button>
                    <button type="button" class="profile-settings-card profile-settings-card-btn" data-profile-open="manage-annonces">
                        <span class="profile-settings-icon" aria-hidden="true">☰</span>
                        <strong>Gérer mes annonces</strong>
                        <span>Voir, éditer ou supprimer</span>
                    </button>
                    <button type="button" class="profile-settings-card profile-settings-card-btn" data-profile-open="favorites">
                        <span class="profile-settings-icon" aria-hidden="true">★</span>
                        <strong>Favoris et suivis</strong>
                        <span>Accès rapide aux biens gardés</span>
                    </button>
                </div>
            </section>

            <div class="results-head profile-section-head">
                <div>
                    <h2>Mes annonces</h2>
                    <p><?php echo $publishedCount; ?> annonce(s) prête(s) à être gérée(s)</p>
                </div>
            </div>

            <div class="cards-grid profile-cards-grid">
                <?php if (empty($userAnnonces)): ?>
                    <article class="property-card empty-state-card">
                        <div class="card-body">
                            <div class="empty-state-illus" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><path d="M12 2 2 7v15h20V7Zm0 2.3L18.4 7 12 11.1 5.6 7ZM4 9.6l7 4.5v8H4Zm9 12.5v-8l7-4.5v12.5Z"/></svg>
                            </div>
                            <h3>Aucune annonce publiée</h3>
                            <p>Le tableau est vide pour l’instant. Publie une annonce pour remplir ton espace personnel.</p>
                            <div class="card-actions">
                                <a class="card-action-btn primary" href="/annonces_immobilier/Controlles/AnnoncesCtrl.php?action=publier_ann">Créer une annonce</a>
                            </div>
                        </div>
                    </article>
                <?php else: ?>
                    <?php foreach ($userAnnonces as $annonce): ?>
                        <?php
                            $titre = $annonce['Titre'] ?? 'Bien immobilier';
                            $description = $annonce['Description'] ?? '';
                            $prix = $annonce['Prix'] ?? 0;
                            $type = $annonce['Type'] ?? '';
                            $ville = $annonce['nom_ville'] ?? '';
                            $categorie = $annonce['Categorie'] ?? '';
                            $datePublication = $annonce['Date_publication'] ?? $annonce['Date_Publication'] ?? '';
                            $images = array_filter(array_map('trim', explode(',', (string) ($annonce['url_image'] ?? ''))));
                            $image = $images[0] ?? '';
                        ?>
                        <article
                            class="property-card profile-card"
                            data-annonce-title="<?php echo htmlspecialchars((string) $titre); ?>"
                            data-annonce-description="<?php echo htmlspecialchars((string) $description); ?>"
                            data-annonce-price="<?php echo htmlspecialchars((string) $prix); ?>"
                            data-annonce-type="<?php echo htmlspecialchars((string) $type); ?>"
                            data-annonce-ville="<?php echo htmlspecialchars((string) $ville); ?>"
                            data-annonce-categorie="<?php echo htmlspecialchars((string) $categorie); ?>"
                        >
                            <div class="card-media profile-card-media">
                                <?php if ($image !== ''): ?>
                                    <img src="<?php echo htmlspecialchars($resolveImageSrc($image)); ?>" alt="<?php echo htmlspecialchars($titre); ?>" class="card-image" loading="lazy">
                                <?php else: ?>
                                    <div class="media-empty">Aucune image</div>
                                <?php endif; ?>
                                <div class="profile-card-topbar">
                                    <span class="publish-badge"><?php echo htmlspecialchars((string) $datePublication); ?></span>
                                    <div class="profile-card-icons">
                                        <button type="button" class="profile-icon-btn" data-profile-open="edit-annonce" aria-label="Modifier l'annonce">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 17.3 11.7-11.7 3.7 3.7L6.7 21H3Zm15.5-13.5 1.7-1.7a1.4 1.4 0 0 1 2 0l.7.7a1.4 1.4 0 0 1 0 2L21.2 6Z"/></svg>
                                        </button>
                                        <button type="button" class="profile-icon-btn danger" data-profile-open="delete-annonce" aria-label="Supprimer l'annonce">
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 3h6l1 2h4v2H4V5h4ZM6 9h12l-1 12H7L6 9Zm4 2v8h2v-8Zm4 0v8h2v-8Z"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-publish-row">
                                    <span class="type-badge"><?php echo htmlspecialchars((string) $type); ?></span>
                                    <span class="profile-price-pill"><?php echo htmlspecialchars((string) $prix); ?> DH</span>
                                </div>
                                <h3><?php echo htmlspecialchars($titre); ?></h3>
                                <p class="card-description"><?php echo htmlspecialchars($description); ?></p>
                                <div class="meta profile-meta">
                                    <span>Ville : <?php echo htmlspecialchars((string) $ville); ?></span>
                                    <span>Categorie : <?php echo htmlspecialchars((string) $categorie); ?></span>
                                </div>
                                <div class="card-actions">
                                    <button type="button" class="card-action-btn" data-profile-open="edit-annonce">Modifier</button>
                                    <button type="button" class="card-action-btn danger" data-profile-open="delete-annonce">Supprimer</button>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <div class="profile-modal" id="profile-modal" aria-hidden="true" hidden>
            <div class="profile-modal-backdrop" data-profile-close="true"></div>
            <section class="profile-modal-content" role="dialog" aria-modal="true" aria-labelledby="profile-modal-title">
                <header class="profile-modal-head">
                    <h3 id="profile-modal-title">Action profil</h3>
                    <button type="button" class="profile-modal-close" data-profile-close="true" aria-label="Fermer">×</button>
                </header>

                <div class="profile-modal-body" id="profile-modal-body">
                    <p>Choisis une action depuis les cartes ou les paramètres rapides.</p>
                    <div class="profile-modal-note">Interface front-end prête: édition et suppression en aperçu local.</div>
                </div>

                <footer class="profile-modal-foot">
                    <button type="button" class="card-action-btn" data-profile-close="true">Fermer</button>
                    <button type="button" class="card-action-btn primary" id="profile-modal-confirm">Valider</button>
                </footer>
            </section>
        </div>
    </div>
</main>

<!-- Lier le CSS -->
<link rel="stylesheet" href="../assets/css/modifierInfo.css">

<script>
// ==================== GESTION MODAL ==================== 

const modalModifier = document.getElementById('modal-modifier-infos');
const btnOpenModifier = document.getElementById('btn-open-modifier-infos');
const btnCloseModal = document.getElementById('close-modal-btn');
const btnCancelModifier = document.getElementById('btn-modifier-cancel');
const formModifier = document.getElementById('modifier-form');
const btnSave = document.getElementById('btn-modifier-save');
const alertsContainer = document.getElementById('modifier-alerts-container');

// Ouvrir la modal
btnOpenModifier.addEventListener('click', () => {
    modalModifier.classList.add('active');
    document.body.style.overflow = 'hidden';
});

// Fermer la modal
function closeModal() {
    modalModifier.classList.remove('active');
    document.body.style.overflow = 'auto';
    alertsContainer.innerHTML = '';
}

btnCloseModal.addEventListener('click', closeModal);
btnCancelModifier.addEventListener('click', closeModal);

// Fermer en cliquant en dehors
modalModifier.addEventListener('click', (e) => {
    if (e.target === modalModifier) {
        closeModal();
    }
});

// Fermer avec Echap
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalModifier.classList.contains('active')) {
        closeModal();
    }
});

// ==================== SOUMISSION FORMULAIRE ==================== 

formModifier.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Récupérer les données
    const nom = document.getElementById('modifier-nom').value.trim();
    const prenom = document.getElementById('modifier-prenom').value.trim();
    const email = document.getElementById('modifier-email').value.trim();
    const telephone = document.getElementById('modifier-telephone').value.trim();

    // Validation basique côté client
    if (!nom || !prenom || !email || !telephone) {
        showAlert('❌ Tous les champs sont obligatoires', 'error');
        return;
    }

    if (nom.length < 3 || prenom.length < 3) {
        showAlert('❌ Le nom et prénom doivent avoir au moins 3 caractères', 'error');
        return;
    }

    if (nom.length > 30 || prenom.length > 30) {
        showAlert('❌ Le nom et prénom ne doivent pas dépasser 30 caractères', 'error');
        return;
    }

    // Validation email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showAlert('❌ Format d\'email invalide', 'error');
        return;
    }

    // Validation téléphone (format Maroc)
    const phoneRegex = /^(\+212|0)[67]\d{8}$/;
    const cleanPhone = telephone.replace(/\s|-/g, '');
    if (!phoneRegex.test(cleanPhone)) {
        showAlert('❌ Format téléphone invalide (ex: +212 612345678 ou 0612345678)', 'error');
        return;
    }

    // Envoyer au serveur
    btnSave.classList.add('loading');
    btnSave.disabled = true;

    try {
        const formData = new FormData(formModifier);
        const response = await fetch('Controllers/UserCtrl.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showAlert('✅ ' + result.message, 'success');
            
            // Rafraîchir la page après 2 secondes
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            const errorMsg = result.message || 'Une erreur est survenue';
            showAlert('❌ ' + errorMsg, 'error');
        }
    } catch (error) {
        console.error('Erreur:', error);
        showAlert('❌ Erreur serveur. Veuillez réessayer.', 'error');
    } finally {
        btnSave.classList.remove('loading');
        btnSave.disabled = false;
    }
});

// ==================== FONCTION AFFICHAGE ALERTES ==================== 

function showAlert(message, type) {
    const alertClass = type === 'success' ? 'modal-alert-success' : 'modal-alert-error';
    const alertIcon = type === 'success' ? '✓' : '!';

    const alertHTML = `
        <div class="modal-alert ${alertClass}">
            <span class="modal-alert-icon">${alertIcon}</span>
            <div class="modal-alert-content">
                <p>${message}</p>
            </div>
        </div>
    `;

    alertsContainer.innerHTML = alertHTML;

    // Auto-masquer les alertes d'erreur après 5 secondes
    if (type === 'error') {
        setTimeout(() => {
            alertsContainer.innerHTML = '';
        }, 5000);
    }
}
</script>

<?php include(__DIR__ . "/footer.php"); ?>