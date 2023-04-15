function updateValues(name) {
	fetch("/api/api.php?nome=" + name)
		.then(response => response.json())
		.then(data => document.getElementById(name).textContent = data)
		.catch(error => console.error(error));

	var currentdate = new Date();
	var datetime = 'Atualizado ' + name + ' - ' + currentdate.getHours() + ":" +
		currentdate.getMinutes() + ":" +
		currentdate.getSeconds();
	console.log(datetime);
}

var time = new Date(),
	secondsRemaining = (20 - time.getSeconds()) * 1000;

setTimeout(function() {
	setInterval(updateValues, 20000, 'temperatura');
	setInterval(updateValues, 20000, 'humidade');
	setInterval(updateValues, 20000, 'co2');
	setInterval(updateValues, 20000, 'cancelaEnt');
	setInterval(updateValues, 20000, 'cancelaSai');
	setInterval(updateValues, 20000, 'luzes');
}, secondsRemaining);