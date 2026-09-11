document.querySelectorAll('input[type="file"][data-max-files]').forEach((input) => {
	const maxFiles = Number(input.dataset.maxFiles);

	input.addEventListener('change', () => {
		const tooManyFiles = input.files.length > maxFiles;

		input.setCustomValidity(
			tooManyFiles ? `Selecteer maximaal ${maxFiles} foto's.` : ''
		);
	});
});
