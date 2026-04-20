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
		showFeedback('Filtres tkhbaw.');
	});

	filtersShowBtn?.addEventListener('click', () => {
		expandFiltersPanel();
		triggerPanelAnimation();
		showFeedback('Filtres rj3o.');
	});

	applyFilters();
}
