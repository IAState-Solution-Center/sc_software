function remove(ticketNumber){
	$.ajax({
		data: 'ticketRemove=' + ticketNumber,
		url: 'functions.php',
		method: 'POST',
		success: function(msg) {
// 			alert(msg);
			console.log(msg);
		}
	});
	window.location = "/";

}

function working(ticketNumber){
	$.ajax({
		data: 'ticketWorking=' + ticketNumber,
		url: 'functions.php',
		method: 'POST',
		success: function(msg) {
// 			alert(msg);
			console.log(msg);
		}
	});
	window.location = "/";

}