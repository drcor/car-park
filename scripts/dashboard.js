document.querySelectorAll('.form-check-input').forEach(element => {
	element.addEventListener('click', function() {
		let value = 'Off';

		// Se o switch for ativado define o valor a enviar como ligado
		if (this.checked) {
			value = 'On';
		}
		console.log(this.id);
		fetch('/api/api.php', {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded",
				},
				body: "nome=" + this.id + "&valor=" + value,
			})
			.then(() => window.location.reload())
			.catch(error => console.error(error));
	});
});