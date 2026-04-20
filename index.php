<?php include(__DIR__."/Views/header.php");  ?>

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
					<button type="button" class="select-btn">Appartement a vendre</button>
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

				<div class="filter-group">
					<p>Champ VIP</p>
					<label class="vip-field" for="vip-only">
						<input id="vip-only" type="checkbox">
						<span>Afficher uniquement les annonces VIP</span>
					</label>
				</div>

				<a class="results-btn" href="#">Voir 4 235 annonces</a>
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
					<article class="property-card vip-card">
						<div class="card-media media-1">
							<span class="vip-badge">VIP</span>
							<span class="vip-glow" aria-hidden="true"></span>
						</div>
						<div class="card-body">
							<p class="vip-kicker">Selection signature</p>
							<p class="location">Rabat, Centre ville</p>
							<h3>Appartement a vendre 125 m2</h3>
							<div class="meta">
								<span>3 chambres</span><span>2 sdb</span><span>Etage 4</span>
							</div>
							<div class="vip-row">
								<span class="vip-pill">Conciergerie dediee</span>
								<a href="#" class="vip-link">Visite prioritaire</a>
							</div>
							<p class="price">1 300 000 DH</p>
						</div>
					</article>

					<article class="property-card">
						<div class="card-media media-2"></div>
						<div class="card-body">
							<p class="location">Casablanca, Maarif</p>
							<h3>Residence neuve avec balcon 134 m2</h3>
							<div class="meta">
								<span>3 chambres</span><span>3 sdb</span><span>Parking</span>
							</div>
							<p class="price">1 189 000 DH</p>
						</div>
					</article>

					<article class="property-card vip-card">
						<div class="card-media media-3">
							<span class="vip-badge">VIP</span>
							<span class="vip-glow" aria-hidden="true"></span>
						</div>
						<div class="card-body">
							<p class="vip-kicker">Selection signature</p>
							<p class="location">Essaouira, Centre</p>
							<h3>Appartement lumineux 75 m2</h3>
							<div class="meta">
								<span>2 chambres</span><span>2 sdb</span><span>Etage 1</span>
							</div>
							<div class="vip-row">
								<span class="vip-pill">Acces prive au dossier</span>
								<a href="#" class="vip-link">Demander rappel VIP</a>
							</div>
							<p class="price">1 100 000 DH</p>
						</div>
					</article>

					<article class="property-card">
						<div class="card-media media-4"></div>
						<div class="card-body">
							<p class="location">Marrakech, Gueliz</p>
							<h3>Studio premium proche commodites</h3>
							<div class="meta">
								<span>1 chambre</span><span>1 sdb</span><span>Ascenseur</span>
							</div>
							<p class="price">760 000 DH</p>
						</div>
					</article>
				</div>
			</section>
		</div>
	</main>
	<?php include(__DIR__."/Views/footer.php");  ?>
</body>
</html>
