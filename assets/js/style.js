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
