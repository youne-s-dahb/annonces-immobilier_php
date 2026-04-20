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

const vipOnlyCheckbox = document.querySelector('#vip-only');
const propertyCards = Array.from(document.querySelectorAll('.property-card'));
const resultsButton = document.querySelector('.results-btn');

if (vipOnlyCheckbox && propertyCards.length > 0) {
	const updateListingState = () => {
		const vipOnly = vipOnlyCheckbox.checked;
		let visibleCount = 0;

		propertyCards.forEach((card) => {
			const isVip = card.classList.contains('vip-card');
			const shouldShow = !vipOnly || isVip;
			card.style.display = shouldShow ? '' : 'none';
			if (shouldShow) {
				visibleCount += 1;
			}
		});

		if (resultsButton) {
			resultsButton.textContent = `Voir ${visibleCount} annonces`;
		}
	};

	vipOnlyCheckbox.addEventListener('change', updateListingState);
	updateListingState();
}
