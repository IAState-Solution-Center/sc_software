function remove(ticketNumber){
	$.ajax({
		data: 'ticketRemove=' + ticketNumber,
		url: 'functions.php',
		method: 'POST',
		success: function(msg) {
// 			alert(msg);
			console.log(msg);
			// window.location = "/";
			setInterval(refreshPage(), 100);
		}
	});
// 	window.location = "/";
	setInterval(refreshPage(), 1);
}

function working(ticketNumber){
	$.ajax({
		data: 'ticketWorking=' + ticketNumber,
		url: 'functions.php',
		method: 'POST',
		success: function(msg) {
// 			alert(msg);
			console.log(msg);
			window.location = "/";
		}
	});
// 	window.location = "/";
	// setInterval(refreshPage(), 1);
}

function updateTime(){
	var x = document.getElementsByName("assigned");
	var time = new Date().valueOf();
	x.value = time;
}

function refreshPage(){
	window.location = "/";
}