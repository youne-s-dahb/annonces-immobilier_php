const publishForm = document.getElementById('publish-form');
const imageInput = document.getElementById('publish-images');
const imagesFeedback = document.getElementById('images-feedback');
const imagesPreview = document.getElementById('images-preview');
const descriptionInput = document.getElementById('publish-description');
const descriptionCount = document.getElementById('description-count');
const phoneInput = document.getElementById('publish-phone');

if (publishForm) {
	let selectedImageFiles = [];

	const updateDescriptionCount = () => {
		if (!descriptionInput || !descriptionCount) {
			return;
		}
		const currentLength = descriptionInput.value.length;
		descriptionCount.textContent = `${currentLength} / 500`;
	};

	const syncInputFiles = () => {
		if (!imageInput) {
			return;
		}

		const transfer = new DataTransfer();
		selectedImageFiles.forEach((file) => transfer.items.add(file));
		imageInput.files = transfer.files;
	};

	const renderImagePreviews = () => {
		if (!imagesPreview) {
			return;
		}

		imagesPreview.innerHTML = '';

		selectedImageFiles.forEach((file, index) => {
			const previewItem = document.createElement('div');
			previewItem.className = 'pa-preview-item';

			const image = document.createElement('img');
			image.src = URL.createObjectURL(file);
			image.alt = `Apercu ${index + 1}`;
			image.addEventListener('load', () => URL.revokeObjectURL(image.src));

			const removeBtn = document.createElement('button');
			removeBtn.type = 'button';
			removeBtn.className = 'pa-preview-remove';
			removeBtn.setAttribute('aria-label', `Supprimer image ${index + 1}`);
			removeBtn.textContent = 'x';
			removeBtn.addEventListener('click', () => {
				selectedImageFiles = selectedImageFiles.filter((_, fileIndex) => fileIndex !== index);
				syncInputFiles();
				renderImagePreviews();
				updateImageFeedback();
			});

			previewItem.append(image, removeBtn);
			imagesPreview.append(previewItem);
		});
	};

	const updateImageFeedback = () => {
		if (!imageInput || !imagesFeedback) {
			return;
		}

		const fileCount = selectedImageFiles.length;
		if (fileCount === 0) {
			imagesFeedback.textContent = 'Aucun fichier selectionne';
			return;
		}

		imagesFeedback.textContent = `${fileCount} image${fileCount > 1 ? 's' : ''} selectionnee${fileCount > 1 ? 's' : ''}`;
	};

	imageInput?.addEventListener('change', () => {
		const newFiles = Array.from(imageInput.files || []);
		if (newFiles.length === 0) {
			return;
		}

		selectedImageFiles = [...selectedImageFiles, ...newFiles];
		syncInputFiles();
		renderImagePreviews();
		updateImageFeedback();
	});
	descriptionInput?.addEventListener('input', updateDescriptionCount);

	phoneInput?.addEventListener('input', () => {
		phoneInput.value = phoneInput.value.replace(/[^\d+]/g, '').slice(0, 13);
	});

        publishForm.addEventListener('submit', (event) => {
            
            event.preventDefault();

            const normalizedPhone = phoneInput.value.replace(/\s+/g, '');
            const isPhoneValid = /^\+?\d{10,13}$/.test(normalizedPhone);

            if (!isPhoneValid) {
                imagesFeedback.classList.add('is-error');
                imagesFeedback.textContent = 'Telephone invalide. Utilise 10 a 13 chiffres.';
                phoneInput.focus();
                return; 
            }

           
            
            publishForm.submit(); 
        });

	updateDescriptionCount();
	renderImagePreviews();
	updateImageFeedback();
}
