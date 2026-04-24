<?php
include(__DIR__."/header.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/annonces_immobilier/assets/css/publier_annonce.css">
</head>
<body>
    
        <main class="pa-page" aria-label="Page publier annonce">
            <div class="container pa-layout">
                <section class="pa-intro" aria-label="Introduction publication">
                    <p class="pa-kicker">Espace annonceur</p>
                    <h1>Publie ton bien rapidement</h1>
                    <p class="pa-text">
                        Une interface claire pour ajouter les infos importantes de ton annonce sans perdre du temps.
                    </p>
                    <ul class="pa-points">
                        <li>Formulaire simple et lisible</li>
                        <li>Upload image avec apercu du nombre de fichiers</li>
                        <li>Validation rapide avant envoi</li>
                    </ul>
                </section>

                <section class="pa-card" aria-label="Formulaire publier annonce">
                    <div class="pa-head">
                        <h2>Publier une annonce</h2>
                        <p>Remplis les champs ci-dessous.</p>
                    </div>

                    <form class="pa-form" action="" method="POST" enctype="multipart/form-data" id="publish-form" novalidate>
                        <div class="pa-field">
                            <label for="publish-images">Images</label>
                            <input id="publish-images" type="file" name="image" accept="image/*" multiple>
                            <small id="images-feedback" class="pa-help">Aucun fichier selectionne</small>
                            <div id="images-preview" class="pa-preview-grid" aria-live="polite"></div>
                        </div>

                        <div class="pa-grid pa-grid-2">
                            <div class="pa-field">
                                <label for="publish-type">Type</label>
                                <select id="publish-type" name="type" required>
                                    <option value="" disabled selected>Choisir type</option>
                                    <option value="Location">Location</option>
                                    <option value="Vente">Vente</option>
                                </select>
                            </div>

                            <div class="pa-field">
                                <label for="publish-phone">Telephone</label>
                                <input id="publish-phone" type="tel" name="telephone" placeholder="06XXXXXXXX" required>
                            </div>
                        </div>

                        <div class="pa-field">
                            <label for="publish-title">Titre</label>
                            <input id="publish-title" type="text" name="titre" placeholder="Ex: Appartement moderne a louer" required>
                        </div>

                        <div class="pa-field">
                            <label for="publish-description">Description</label>
                            <textarea id="publish-description" name="description" rows="5" maxlength="500" placeholder="Decris ton bien immobilier..." required></textarea>
                            <small id="description-count" class="pa-help">0 / 500</small>
                        </div>

                        <div class="pa-grid pa-grid-2">
                            <div class="pa-field">
                                <label for="publish-city">Ville</label>
                                <select id="publish-city" name="ville" required>
                                    <option value="" disabled selected>Choisir ville</option>
                                    <?php foreach ($ville as $vil): ?>
                                        <option value="<?php echo htmlspecialchars($vil['nom_ville']); ?>"><?php echo htmlspecialchars($vil['nom_ville']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="pa-field">
                                <label for="publish-category">Categorie</label>
                                <select id="publish-category" name="categorie" required>
                                    <option value="" disabled selected>Choisir categorie</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat['Categorie']); ?>"><?php echo htmlspecialchars($cat['Categorie']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="pa-submit">Publier annonce</button>
                    </form>
                </section>
            </div>
            </main>
        <script src="/annonces_immobilier/assets/js/publier_ann.js"></script>
</body>
</html>


<?php include(__DIR__."/footer.php"); ?>
