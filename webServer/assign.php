<?php
include('functions.php');
?>

<?= printHeader() ?>
		<div class='pure-g'>
			<div align=center class="pure-u-1-3">
				<h2>Instructions:</h2>
				<p>TBD</p>
				<br>
				<button onclick='remove(-1)' class="pure-button button-error">Remove All Tickets</button>
				</div>
			<div class="pure-u-1-3">
				<form method=post action=functions.php class="pure-form pure-form-aligned">
					<fieldset>
						<div class="pure-control-group">
							<label for="name">Employee</label>
							<input name="name" id="name" type="text" placeholder="Employee">
						</div>

						<div class="pure-control-group">
							<label for="tks">Ticket Number</label>
							<input name="ticket" id="tks" type="number" placeholder="Ticket Number">
						</div>

						<div class="pure-control-group">
							<label for="status">Status</label>
							<input name="status" id="status" type="text" placeholder="Status">
						</div>

						<div class="pure-control-group">
							<label for="assigned">Assigned</label>
							<input name="assigned" id="assigned" type="text" placeholder="Epoch Time">
							<span class="pure-form-message">Current Time: <script>setInterval(document.write(Math.round((new Date()).getTime() / 1000)), 1000);</script></span>
						</div>

						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">Submit</button>
						</div>
					</fieldset>
				</form>
			</div>
		<div class="pure-u-1-3">
			<form action=functions.php method=post class="pure-form">
				<fieldset class="pure-group">
					<textarea name='csvData' style="line-height: 50px;" class="pure-input-1-2" placeholder="CSV String From Splunk"></textarea>
				</fieldset>
				<button type="submit" class="pure-button pure-input-1-2 pure-button-primary">Upload</button>
<!-- 
			</form><br><hr>
			<form action="upload.php" method="post" class=pure-form enctype="multipart/form-data">
				Select CSV to upload:
				<input type="file" name="fileToUpload" id="fileToUpload"><br><br>
				<button type="submit" class="pure-button pure-input-1-2 pure-button-primary" >Upload File</button>
			</form>
 -->
		</div>
	</div>
  </body>
</html>