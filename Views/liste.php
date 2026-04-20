<?php include(__DIR__."/header.php"); ?>
<?php
if (!isset($lesAnnonces) || !is_array($lesAnnonces)) {
	$lesAnnonces = [];
}
?>

<main class="page-shell">
	<div class="container listing-layout">
		<aside class="filters-panel" aria-label="Filtres de recherche">
			<div class="filter-head">
				<h2>Filtres</h2>
				<button type="button">Effacer</button>
			</div>

			<label class="search-box" for="recherche">
				<input id="recherche" type="text" placeholder="Que recherchez-vous ?">
			</label>

			<div class="filter-group">
				<p>Categorie</p>
				<select class="select-btn" name="categorie">
					<option value="">Toutes les categories</option>
					<?php foreach ($categories as $categorie): ?>
						<option value="<?php echo htmlspecialchars($categorie); ?>"><?php echo htmlspecialchars($categorie); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="filter-group">
				<p>Ville - Secteur</p>
				<button type="button" class="select-btn">Choisir ville - secteur</button>
			</div>

			<div class="filter-group">
				<p>Type d'appartement</p>
				<div class="chips">
					<button type="button" class="chip">Studio</button>
					<button type="button" class="chip">Duplex</button>
					<button type="button" class="chip">Penthouse</button>
				</div>
			</div>

			<div class="filter-group">
				<p>Prix</p>
				<div class="price-inputs">
					<input type="text" placeholder="Min">
					<input type="text" placeholder="Max">
				</div>
			</div>

			<a class="results-btn" href="#">Voir <?php echo count($lesAnnonces); ?> annonces</a>
		</aside>

		<section class="results-panel" aria-label="Resultats annonces">
			<div class="results-head">
				<div>
					<h1>Biens immobiliers disponibles</h1>
					<p>Selection soignee pour un confort de navigation optimal.</p>
				</div>
				<button type="button" class="sort-btn">Tri : Plus recents</button>
			</div>

			<div class="cards-grid">
				<?php if (count($lesAnnonces) === 0): ?>
					<article class="property-card">
						<div class="card-body">
							<p class="location">Aucune annonce trouvee</p>
							<h3>Ajoutez des annonces pour les afficher ici.</h3>
						</div>
					</article>
				<?php else: ?>
					<?php foreach ($lesAnnonces as $index => $annonce): ?>
						<?php
							$mediaIndex       = ($index % 4) + 1;
                                $titre           = $annonce['Titre'] ?? 'Bien immobilier';
                                $description     = $annonce['Description'] ?? 'Description non précisee';
                                $prix            = $annonce['Prix'] ?? 0;
                                $telephone       = $annonce['Telephone'] ?? 'Non renseigné';
                                $type            = $annonce['Type'] ?? 'Non précisé';
                                $datePublication = $annonce['Date_publication'] ?? 'Non précisée';
                                $NomVille         = $annonce['nom_ville'] ?? '-';
                                $NomCategorie     = $annonce['Categorie'] ?? '-';
                                $Nomuser         = $annonce['Nom'] ?? '-';
                                $Prenomuser      = $annonce['Prenom'] ?? '-';
							$dateAffiche = $datePublication;
							$timestamp = strtotime((string) $datePublication);
							if ($timestamp !== false) {
								$dateAffiche = date('d/m/Y H:i', $timestamp);
							}
							$ownerName = trim((string) $Nomuser.' '.(string) $Prenomuser);
							if ($ownerName === '') {
								$ownerName = 'Compte non renseigne';
							}
							$ownerInitial = strtoupper(substr($ownerName, 0, 1));
						?>
						<article class="property-card">
							<div class="card-media media-<?php echo $mediaIndex; ?>">
							</div>
							<div class="card-body">
								<div class="card-publish-row">
									<span class="publish-badge">Publiee le <?php echo htmlspecialchars((string) $dateAffiche); ?></span>
									<span class="type-badge"><?php echo htmlspecialchars((string) $type); ?></span>
								</div>
								<div class="owner-chip" aria-label="Compte annonceur">
									<span class="owner-avatar"><?php echo htmlspecialchars($ownerInitial); ?></span>
									<span class="owner-meta">
										<strong><?php echo htmlspecialchars($ownerName); ?></strong>
										<em>Compte annonceur</em>
									</span>
								</div>
								<h3><?php echo htmlspecialchars($titre); ?></h3>
								<p class="card-description"><?php echo htmlspecialchars($description); ?></p>
								<div class="meta">
									<span>Telephone: <?php echo htmlspecialchars((string) $telephone); ?></span>
									<span>Ville : <?php echo htmlspecialchars((string) $NomVille); ?></span>
									<span>Categorie: <?php echo htmlspecialchars((string) $NomCategorie); ?></span>
								</div>
								<p class="price"><?php echo htmlspecialchars((string) $prix); ?> DH</p>
							</div>
						</article>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</section>
	</div>
</main>

<?php include(__DIR__."/footer.php"); ?>
