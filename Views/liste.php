<?php include(__DIR__."/header.php"); ?>


<main class="page-shell">
	<div class="container listing-layout">
		<aside class="filters-panel" aria-label="Filtres de recherche">
			<div class="filter-head">
				<h2>Filtrer</h2>
				<div class="filter-head-actions">
					<button type="button" class="filter-clear-btn" id="filters-clear-btn">Cacher</button>
				</div>
			</div>
			<p class="filter-feedback" id="filter-feedback" aria-live="polite"></p>

			<label class="search-box" for="recherche">
				<input id="recherche" type="text" placeholder="Que recherchez-vous ?">
			</label>

			<div class="filter-group">
				<p>Categorie</p>
				<select class="select-btn select-fancy" name="categorie" id="categorie-select-native">
					<option value="" disabled selected>Choisir categorie</option>
					<?php foreach ($categories as $categorie): ?>
						<option value="<?php echo htmlspecialchars($categorie["Categorie"]); ?>"><?php echo htmlspecialchars($categorie["Categorie"]); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="filter-group">
				<p>Ville - Secteur</p>
				<select name="ville" id="ville-select-native" class="select-btn select-fancy">
					<option value="" disabled selected>Choisir ville</option>
					<?php foreach ($ville as $vil): ?>
						<option value="<?php echo htmlspecialchars($vil["nom_ville"]); ?>"><?php echo htmlspecialchars($vil["nom_ville"]); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="filter-group">
				<p>Type </p>
				<div class="chips">
					<button type="button" class="chip js-type-chip" data-type="location" aria-pressed="false">Location</button>
					<button type="button" class="chip js-type-chip" data-type="vente" aria-pressed="false">Vente</button>
				</div>
			</div>

			<div class="filter-group">
				<p>Prix</p>
				<div class="price-inputs">
					<input id="prix-min" type="text" inputmode="numeric" placeholder="Min">
					<input id="prix-max" type="text" inputmode="numeric" placeholder="Max">
				</div>
			</div>

			<a class="results-btn" href="#">Voir <span id="results-count"><?php echo count($lesAnnonces); ?></span> annonces</a>
		</aside>

		<section class="results-panel" aria-label="Resultats annonces">
			<div class="results-head">
				<div>
					<h1>Biens immobiliers disponibles</h1>
					<p>Selection soignee pour un confort de navigation optimal.</p>
				</div>
				<div class="results-head-actions">
					<button type="button" class="show-filters-btn" id="filters-show-btn" hidden>Afficher filtres</button>
					<button type="button" class="sort-btn">Tri : Plus recents</button>
				</div>
			</div>

			<div class="cards-grid">
				<?php if (count($lesAnnonces) === 0): ?>
					<article class="property-card">
						<div class="card-body">
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
								$urlImage			 =$annonce["url_image"];
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
						<article
							class="property-card"
							data-title="<?php echo htmlspecialchars(strtolower((string) $titre)); ?>"
							data-description="<?php echo htmlspecialchars(strtolower((string) $description)); ?>"
							data-ville="<?php echo htmlspecialchars(strtolower((string) $NomVille)); ?>"
							data-categorie="<?php echo htmlspecialchars(strtolower((string) $NomCategorie)); ?>"
							data-type="<?php echo htmlspecialchars(strtolower((string) $type)); ?>"
							data-prix="<?php echo htmlspecialchars((string) $prix); ?>"
						>
							<div class="card-media media-<?php echo $mediaIndex; ?>">
								<img src="<?=$urlImage?>" alt="">
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
