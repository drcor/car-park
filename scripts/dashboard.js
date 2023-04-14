document.getElementById('sidebarCollapse').addEventListener('click', function() {
	let sidebar = document.getElementById('sidebar');
	sidebar.classList.toggle('active');
});

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
	secondsRemaining = (60 - time.getSeconds()) * 1000;

setTimeout(function() {
	setInterval(updateValues, 10000, 'temperatura');
	setInterval(updateValues, 10000, 'humidade');
	setInterval(updateValues, 10000, 'co2');
}, secondsRemaining);