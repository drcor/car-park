document.querySelectorAll('.form-check-input').forEach(element => {
	element.addEventListener('click', function() {
		let value = 'Off';

		// Se o switch for ativado define o valor a enviar como ligado
		if (this.checked) {
			value = 'On';
		}
		// Envia o POST para atualizar o estado do atuador através da API
		fetch('api/api.php', {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded",
				},
				body: "nome=" + this.name + "&valor=" + value,
			})
			.then(() => window.location.reload())
			.catch(error => console.error(error));
	});
});