function remove(ticketNumber){
	$.ajax({
		data: 'ticketRemove=' + ticketNumber,
		url: 'functions.php',
		method: 'POST',
		success: function(msg) {
// 			alert(msg);
			console.log("Removed: " + msg);
			window.location = "/";
		}
	});
}

function working(ticketNumber){
	$.ajax({
		data: 'ticketWorking=' + ticketNumber,
		url: 'functions.php',
		method: 'POST',
		success: function(msg) {
// 			alert(msg);
			console.log("Working: " + msg);
			window.location = "/";
		}
	});
}

function updateTime(){
	var x = document.getElementsByName("assigned");
	var time = new Date().valueOf();
	x.value = time;
}

function refreshPage(){
	window.location = "/";
}