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
