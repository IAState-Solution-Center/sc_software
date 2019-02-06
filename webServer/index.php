<?php
include('functions.php');
$message = false;
if($_SESSION['login'] == "0"){
 $message = true;
};
?>

<?= printHeader() ?>
	</div>
		<div class="pure-u-3-24"></div>
		<div class="pure-u-18-24">
			<br>
		    <table style="width:100%" class="pure-table pure-table-horizontal">
				<thead>
					<tr>
						<th>Employee</th>
						<th>Ticket #</th>
						<th>Status</th>
						<th>Assigned</th>
						<th>Edit</th>
						<th>Open Ticket</th>
					</tr>
				</thead>

				<tbody>
					<?php if($message){
						 echo "Please login before viewing tickets<br><br>";
						 echo "<a class='pure-button pure-button-primary' href='/login'>Login</a><br><br>";
					 } else {
					 	readTickets(); 
					 }?>
				</tbody>
			</table>
		<br><br><br>
		</div>
		<div class="pure-u-3-24"></div>
	</div>
  </body>
</html>