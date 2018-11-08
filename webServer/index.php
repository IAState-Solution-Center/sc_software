<?php
include('functions.php');
?>

<?= printHeader() ?>
	</div>
		<div class="pure-u-1-5"></div>
		<div class="pure-u-3-5">
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
					<?= readTickets(); ?>
				</tbody>
			</table>
		<br><br><br>
		</div>
		<div class="pure-u-1-5"></div>
	</div>
  </body>
</html>