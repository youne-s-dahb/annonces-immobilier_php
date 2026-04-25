const topbarInner = document.querySelector('.topbar-inner');
const menuToggle = document.querySelector('.menu-toggle');

if (topbarInner && menuToggle) {
	menuToggle.addEventListener('click', () => {
		const isOpen = topbarInner.classList.toggle('is-open');
		menuToggle.setAttribute('aria-expanded', String(isOpen));
		menuToggle.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
	});

	window.addEventListener('resize', () => {
		if (window.innerWidth > 680) {
			topbarInner.classList.remove('is-open');
			menuToggle.setAttribute('aria-expanded', 'false');
			menuToggle.setAttribute('aria-label', 'Ouvrir le menu');
		}
	});
}

const customSelects = Array.from(document.querySelectorAll('.js-city-select'));

customSelects.forEach((customSelect) => {
	const trigger = customSelect.querySelector('.city-select-trigger');
	const panel = customSelect.querySelector('.city-select-panel');
	const valueLabel = customSelect.querySelector('.city-select-value');
	const searchInput = customSelect.querySelector('.city-select-search');
	const options = Array.from(customSelect.querySelectorAll('.city-option'));
	const nativeSelectId = customSelect.getAttribute('data-native-id') || '';
	const nativeSelect = nativeSelectId ? document.getElementById(nativeSelectId) : null;
	const defaultLabel = valueLabel?.textContent?.trim() || 'Choisir';

	const closePanel = () => {
		customSelect.classList.remove('is-open');
		trigger?.setAttribute('aria-expanded', 'false');
		if (panel) {
			panel.hidden = true;
		}
	};

	const openPanel = () => {
		customSelect.classList.add('is-open');
		trigger?.setAttribute('aria-expanded', 'true');
		if (panel) {
			panel.hidden = false;
		}
		if (searchInput) {
			searchInput.focus();
			searchInput.select();
		}
	};

	const filterOptions = (query) => {
		const normalized = query.trim().toLowerCase();

		options.forEach((option) => {
			const text = (option.textContent || '').toLowerCase();
			const isVisible = normalized === '' || text.includes(normalized);
			option.classList.toggle('is-hidden', !isVisible);
		});
	};

	options.forEach((option) => {
		option.addEventListener('click', () => {
			const selectedValue = option.getAttribute('data-value') || '';
			const selectedText = (option.textContent || '').trim();

			options.forEach((item) => item.classList.remove('is-active'));
			option.classList.add('is-active');

			if (valueLabel) {
				valueLabel.textContent = selectedText || defaultLabel;
			}

			if (nativeSelect) {
				nativeSelect.value = selectedValue;
			}

			if (searchInput) {
				searchInput.value = '';
				filterOptions('');
			}

			closePanel();
		});
	});

	trigger?.addEventListener('click', () => {
		if (customSelect.classList.contains('is-open')) {
			closePanel();
		} else {
			customSelects.forEach((item) => {
				if (item !== customSelect) {
					item.classList.remove('is-open');
					const itemTrigger = item.querySelector('.city-select-trigger');
					const itemPanel = item.querySelector('.city-select-panel');
					itemTrigger?.setAttribute('aria-expanded', 'false');
					if (itemPanel) {
						itemPanel.hidden = true;
					}
				}
			});
			openPanel();
		}
	});

	searchInput?.addEventListener('input', () => {
		filterOptions(searchInput.value);
	});

	document.addEventListener('click', (event) => {
		if (!customSelect.contains(event.target)) {
			closePanel();
		}
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && customSelect.classList.contains('is-open')) {
			closePanel();
			trigger?.focus();
		}
	});
});

const filtersPanel = document.querySelector('.filters-panel');
const listingLayout = document.querySelector('.listing-layout');
const filtersForm = document.getElementById('filters-form');
const filtersClearBtn = document.getElementById('filters-clear-btn');
const filtersShowBtn = document.getElementById('filters-show-btn');
const filterFeedback = document.getElementById('filter-feedback');
const resultsCount = document.getElementById('results-count');

const searchInput = document.getElementById('recherche');
const categorySelect = document.getElementById('categorie-select-native');
const citySelect = document.getElementById('ville-select-native');
const minPriceInput = document.getElementById('prix-min');
const maxPriceInput = document.getElementById('prix-max');
const typeChips = Array.from(document.querySelectorAll('.js-type-chip'));
const propertyCards = Array.from(document.querySelectorAll('.property-card[data-ville]'));

if (filtersForm && minPriceInput && maxPriceInput) {
	filtersForm.addEventListener('submit', (event) => {
		const minValue = Number.parseInt(minPriceInput.value || '', 10);
		const maxValue = Number.parseInt(maxPriceInput.value || '', 10);

		if (Number.isNaN(minValue) || Number.isNaN(maxValue)) {
			event.preventDefault();
			if (filterFeedback) {
				filterFeedback.classList.add('is-visible');
			}
			return;
		}

		if (maxValue < minValue) {
			event.preventDefault();
			if (filterFeedback) {
				filterFeedback.textContent = 'Prix max khaso ykoun akbar mn prix min.';
				filterFeedback.classList.add('is-visible');
			}
		}
	});
}

if (propertyCards.length > 0) {
	const normalize = (value) => (value || '').toString().trim().toLowerCase();
	const parsePrice = (value) => {
		const digits = (value || '').toString().replace(/[^\d]/g, '');
		if (digits === '') {
			return null;
		}
		return Number.parseInt(digits, 10);
	};

	const activeTypeValue = () => {
		const active = typeChips.find((chip) => chip.classList.contains('is-active'));
		return active ? normalize(active.getAttribute('data-type')) : '';
	};

	const showFeedback = (message) => {
		if (!filterFeedback) {
			return;
		}

		filterFeedback.textContent = message;
		filterFeedback.classList.add('is-visible');

		window.clearTimeout(showFeedback.timeoutId);
		showFeedback.timeoutId = window.setTimeout(() => {
			filterFeedback.classList.remove('is-visible');
		}, 1600);
	};

	const triggerPanelAnimation = () => {
		if (!filtersPanel) {
			return;
		}
		filtersPanel.classList.remove('is-resetting');
		void filtersPanel.offsetWidth;
		filtersPanel.classList.add('is-resetting');
	};

	const collapseFiltersPanel = () => {
		if (!listingLayout) {
			return;
		}
		listingLayout.classList.add('filters-collapsed');
		if (filtersShowBtn) {
			filtersShowBtn.hidden = false;
		}
	};

	const expandFiltersPanel = () => {
		if (!listingLayout) {
			return;
		}
		listingLayout.classList.remove('filters-collapsed');
		if (filtersShowBtn) {
			filtersShowBtn.hidden = true;
		}
	};

	const applyFilters = () => {
		const query = normalize(searchInput?.value);
		const selectedCategory = normalize(categorySelect?.value);
		const selectedCity = normalize(citySelect?.value);
		const selectedType = activeTypeValue();
		const minPrice = parsePrice(minPriceInput?.value);
		const maxPrice = parsePrice(maxPriceInput?.value);

		let visibleCount = 0;

		propertyCards.forEach((card) => {
			const cardTitle = normalize(card.getAttribute('data-title'));
			const cardDescription = normalize(card.getAttribute('data-description'));
			const cardCity = normalize(card.getAttribute('data-ville'));
			const cardCategory = normalize(card.getAttribute('data-categorie'));
			const cardType = normalize(card.getAttribute('data-type'));
			const cardPrice = parsePrice(card.getAttribute('data-prix'));

			const haystack = `${cardTitle} ${cardDescription} ${cardCity} ${cardCategory} ${cardType}`;

			const matchesQuery = query === '' || haystack.includes(query);
			const matchesCategory = selectedCategory === '' || cardCategory === selectedCategory;
			const matchesCity = selectedCity === '' || cardCity === selectedCity;
			const matchesType = selectedType === '' || cardType === selectedType;
			const matchesMinPrice = minPrice === null || (cardPrice !== null && cardPrice >= minPrice);
			const matchesMaxPrice = maxPrice === null || (cardPrice !== null && cardPrice <= maxPrice);

			const isVisible = matchesQuery && matchesCategory && matchesCity && matchesType && matchesMinPrice && matchesMaxPrice;

			if (isVisible) {
				if (card.hidden) {
					card.hidden = false;
					card.classList.add('card-appear');
					window.setTimeout(() => card.classList.remove('card-appear'), 280);
				}
				visibleCount += 1;
			} else {
				card.hidden = true;
			}
		});

		if (resultsCount) {
			resultsCount.textContent = String(visibleCount);
		}
	};

	typeChips.forEach((chip) => {
		chip.addEventListener('click', () => {
			const isAlreadyActive = chip.classList.contains('is-active');

			typeChips.forEach((item) => {
				item.classList.remove('is-active');
				item.setAttribute('aria-pressed', 'false');
			});

			if (!isAlreadyActive) {
				chip.classList.add('is-active');
				chip.setAttribute('aria-pressed', 'true');
			}

			applyFilters();
		});
	});

	[searchInput, categorySelect, citySelect, minPriceInput, maxPriceInput].forEach((element) => {
		element?.addEventListener('input', applyFilters);
		element?.addEventListener('change', applyFilters);
	});

	filtersClearBtn?.addEventListener('click', () => {
		collapseFiltersPanel();
		triggerPanelAnimation();
		
	});

	filtersShowBtn?.addEventListener('click', () => {
		expandFiltersPanel();
		triggerPanelAnimation();
		
	});

	const mediaNavButtons = Array.from(document.querySelectorAll('.js-media-prev, .js-media-next'));

	mediaNavButtons.forEach((button) => {
		button.addEventListener('click', () => {
			const galleryId = button.getAttribute('data-gallery-id') || '';
			if (galleryId === '') {
				return;
			}

			const gallery = document.getElementById(galleryId);
			if (!gallery) {
				return;
			}

			const direction = button.classList.contains('js-media-next') ? 1 : -1;
			const step = gallery.clientWidth;
			gallery.scrollBy({
				left: step * direction,
				behavior: 'smooth',
			});
		});
	});

	applyFilters();
}

const profileModal = document.getElementById('profile-modal');
const profileModalTitle = document.getElementById('profile-modal-title');
const profileModalBody = document.getElementById('profile-modal-body');
const profileModalConfirm = document.getElementById('profile-modal-confirm');

if (profileModal && profileModalTitle && profileModalBody && profileModalConfirm) {
	let currentMode = '';
	let currentCard = null;
	let lastTrigger = null;

	const escapeHtml = (value) => {
		const text = (value ?? '').toString();
		return text
			.replaceAll('&', '&amp;')
			.replaceAll('<', '&lt;')
			.replaceAll('>', '&gt;')
			.replaceAll('"', '&quot;')
			.replaceAll("'", '&#039;');
	};

	const getCardData = (card) => ({
		title: card?.dataset.annonceTitle || 'Annonce',
		description: card?.dataset.annonceDescription || '',
		price: card?.dataset.annoncePrice || '',
		type: card?.dataset.annonceType || '',
		ville: card?.dataset.annonceVille || '',
		categorie: card?.dataset.annonceCategorie || '',
	});

	const renderModal = () => {
		if (!profileModalBody || !profileModalTitle) {
			return;
		}

		const data = getCardData(currentCard);

		if (currentMode === 'edit-profile') {
			profileModalTitle.textContent = 'Modifier le profil';
			profileModalBody.innerHTML = `
				<p>Change tes infos personnelles avec une interface rapide. Cette action reste front-end pour le moment.</p>
				<div class="profile-modal-form">
					<div class="field">
						<label for="profile-name">Nom complet</label>
						<input id="profile-name" type="text" placeholder="Nom et prénom" />
					</div>
					<div class="field">
						<label for="profile-phone">Téléphone</label>
						<input id="profile-phone" type="text" placeholder="06XXXXXXXX" />
					</div>
					<div class="field full">
						<label for="profile-email">Email</label>
						<input id="profile-email" type="email" placeholder="nom@email.com" />
					</div>
				</div>
				<div class="profile-modal-note">Preview only: backend dyal update profil mazal ma connectach.</div>
			`;
			profileModalConfirm.textContent = 'Enregistrer';
			profileModalConfirm.classList.remove('danger');
			return;
		}

		if (currentMode === 'manage-annonces') {
			profileModalTitle.textContent = 'Gestion des annonces';
			profileModalBody.innerHTML = `
				<p>Mn hna t9der tdir tri, édition, suppression b workflow n9i. L'actions d backend mazal placeholder.</p>
				<div class="profile-modal-note">Tu peux lancer l'édition/suppression depuis chaque card مباشرة.</div>
			`;
			profileModalConfirm.textContent = 'Compris';
			profileModalConfirm.classList.remove('danger');
			return;
		}

		if (currentMode === 'favorites') {
			profileModalTitle.textContent = 'Favoris et suivis';
			profileModalBody.innerHTML = `
				<p>UI de favoris prête pour extension. Nqder nزيد section cards/filtres mخصصين ila bghiti.</p>
				<div class="profile-modal-note">Feature front-end scaffolded, ready for backend data.</div>
			`;
			profileModalConfirm.textContent = 'Fermer';
			profileModalConfirm.classList.remove('danger');
			return;
		}

		if (currentMode === 'edit-annonce') {
			profileModalTitle.textContent = 'Modifier l\'annonce';
			profileModalBody.innerHTML = `
				<p>Édite l'annonce: <strong>${escapeHtml(data.title)}</strong></p>
				<div class="profile-modal-form">
					<div class="field full">
						<label for="annonce-title">Titre</label>
						<input id="annonce-title" type="text" value="${escapeHtml(data.title)}" />
					</div>
					<div class="field full">
						<label for="annonce-description">Description</label>
						<textarea id="annonce-description">${escapeHtml(data.description)}</textarea>
					</div>
					<div class="field">
						<label for="annonce-price">Prix (DH)</label>
						<input id="annonce-price" type="number" value="${escapeHtml(data.price)}" />
					</div>
					<div class="field">
						<label for="annonce-type">Type</label>
						<input id="annonce-type" type="text" value="${escapeHtml(data.type)}" />
					</div>
					<div class="field">
						<label for="annonce-ville">Ville</label>
						<input id="annonce-ville" type="text" value="${escapeHtml(data.ville)}" />
					</div>
					<div class="field">
						<label for="annonce-categorie">Categorie</label>
						<input id="annonce-categorie" type="text" value="${escapeHtml(data.categorie)}" />
					</div>
				</div>
				<div class="profile-modal-note">Preview only: ghi UI update, ma kaynch save backend daba.</div>
			`;
			profileModalConfirm.textContent = 'Appliquer';
			profileModalConfirm.classList.remove('danger');
			return;
		}

		profileModalTitle.textContent = 'Supprimer l\'annonce';
		profileModalBody.innerHTML = `
			<p>Kathemm b supprimer had l'annonce:</p>
			<p><strong>${escapeHtml(data.title)}</strong></p>
			<div class="profile-modal-note warn">Front-end only: ghadi nmaski card locally, sans suppression DB.</div>
		`;
		profileModalConfirm.textContent = 'Supprimer';
		profileModalConfirm.classList.add('danger');
	};

	const renderFallbackModal = () => {
		profileModalTitle.textContent = 'Action profil';
		profileModalBody.innerHTML = `
			<p>Hadi action front-end preview.</p>
			<div class="profile-modal-note">Ikhtar "Modifier" wla "Supprimer" mn cards bach tban details dyal l\'action.</div>
		`;
		profileModalConfirm.textContent = 'Fermer';
		profileModalConfirm.classList.remove('danger');
	};

	const openModal = (mode, trigger) => {
		currentMode = mode;
		lastTrigger = trigger || null;
		currentCard = trigger?.closest('.profile-card') || null;

		if (!currentMode) {
			renderFallbackModal();
		} else {
			renderModal();
		}

		profileModal.hidden = false;
		profileModal.setAttribute('aria-hidden', 'false');
		document.body.classList.add('has-profile-modal');
	};

	const closeModal = () => {
		profileModal.setAttribute('aria-hidden', 'true');
		profileModal.hidden = true;
		document.body.classList.remove('has-profile-modal');
		if (lastTrigger && typeof lastTrigger.focus === 'function') {
			lastTrigger.focus();
		}
	};

	document.addEventListener('click', (event) => {
		const target = event.target;
		if (!(target instanceof Element)) {
			return;
		}

		const trigger = target.closest('[data-profile-open]');
		if (!trigger) {
			return;
		}

		event.preventDefault();
		const mode = trigger.getAttribute('data-profile-open') || '';
		openModal(mode, trigger);
	});

	profileModal.addEventListener('click', (event) => {
		const target = event.target;
		if (!(target instanceof Element)) {
			return;
		}

		if (target.closest('[data-profile-close="true"]')) {
			closeModal();
		}
	});

	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape' && !profileModal.hidden) {
			closeModal();
		}
	});

	profileModalConfirm.addEventListener('click', () => {
		if (currentMode === '' || currentMode === 'favorites' || currentMode === 'manage-annonces' || currentMode === 'edit-profile') {
			closeModal();
			return;
		}

		if (currentMode === 'delete-annonce' && currentCard) {
			currentCard.classList.add('is-pending-delete');
		}

		if (currentMode === 'edit-annonce' && currentCard) {
			const titleInput = document.getElementById('annonce-title');
			const descriptionInput = document.getElementById('annonce-description');
			const priceInput = document.getElementById('annonce-price');
			const typeInput = document.getElementById('annonce-type');
			const villeInput = document.getElementById('annonce-ville');
			const categorieInput = document.getElementById('annonce-categorie');

			const title = titleInput?.value?.trim() || currentCard.dataset.annonceTitle || '';
			const description = descriptionInput?.value?.trim() || currentCard.dataset.annonceDescription || '';
			const price = priceInput?.value?.trim() || currentCard.dataset.annoncePrice || '';
			const type = typeInput?.value?.trim() || currentCard.dataset.annonceType || '';
			const ville = villeInput?.value?.trim() || currentCard.dataset.annonceVille || '';
			const categorie = categorieInput?.value?.trim() || currentCard.dataset.annonceCategorie || '';

			currentCard.dataset.annonceTitle = title;
			currentCard.dataset.annonceDescription = description;
			currentCard.dataset.annoncePrice = price;
			currentCard.dataset.annonceType = type;
			currentCard.dataset.annonceVille = ville;
			currentCard.dataset.annonceCategorie = categorie;

			const cardTitle = currentCard.querySelector('.card-body h3');
			const cardDescription = currentCard.querySelector('.card-description');
			const cardPrice = currentCard.querySelector('.profile-price-pill');
			const cardType = currentCard.querySelector('.type-badge');
			const meta = currentCard.querySelectorAll('.profile-meta span');

			if (cardTitle) {
				cardTitle.textContent = title || 'Annonce';
			}
			if (cardDescription) {
				cardDescription.textContent = description;
			}
			if (cardPrice) {
				cardPrice.textContent = `${price || '0'} DH`;
			}
			if (cardType) {
				cardType.textContent = type;
			}
			if (meta[0]) {
				meta[0].textContent = `Ville : ${ville}`;
			}
			if (meta[1]) {
				meta[1].textContent = `Categorie : ${categorie}`;
			}
		}

		closeModal();
	});
}


