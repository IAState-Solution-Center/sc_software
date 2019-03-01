<?php
include('functions.php');

if($_SESSION['login'] == "0") header("Location: /login");
?>

<?= printHeader() ?>
		<div class='pure-g'>
			<div align=center class="pure-u-1-3">
				<h3>Instructions:</h3>
				<p><a href="https://wiki.its.iastate.edu/display/ITHANDBOOK/Ticket+Assigning+Website" target="_handbook">Link to ITS Handbook</a></p>
				<br>
				<button onclick='remove(-1)' type=button class="pure-button button-error">Remove All Tickets</button><br><hr width='80%'>
				<form action=functions.php method=POST class="pure-form pure-form-aligned">
					<fieldset>
						<h3> Set your color Preferences </h3>
						<div class="pure-control-group">
							<label for="name">Your working color</label>
							<input type="color" name="color1" value="#63c8ff">
						</div>

						<div class="pure-control-group">
							<label for="password">Other working colors</label>
							<input type="color" name="color2" value="#63c8ff">
						</div>

						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">Submit</button>
						</div>
					</fieldset>
				</form>
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
							<label for="status">Change Gear Link</label>
							<input name="uniqueID" id="uniqueID" type="text" placeholder="Link">
						</div>

						<div class="pure-control-group">
							<label for="assigned">Assigned</label>
							<input name="assigned" id="assigned" type="text" placeholder="Epoch Time">
							<span class="pure-form-message">Current Time: <script>setInterval(document.write(Math.round((new Date()).getTime() / 1000)), 1000);</script></span>
							<span class="pure-form-message-inline"><button onclick="updateTime()" type=button style='position:relative;left:375px;top:-50px;' class=pure-button>Update</button></span>
						</div>

						<div class="pure-controls">
							<button type="submit" class="pure-button pure-button-primary">Submit</button>
						</div>
					</fieldset>
				</form>
			</div>
		<div class="pure-u-1-3">
		<!-- This is for future uploading of a csv file, but it isnt working so its commented out. -->
<!-- 
			<form action=functions.php method=post class="pure-form pure-form-stacked">
				<fieldset>
					<div class="pure-g">
						<div class="pure-u-1-4">
							<textarea name='csvData' style="line-height: 50px;" class="pure-input-1-2" placeholder="CSV String From Splunk"></textarea>
						</div>
						<div class="pure-u-1-4">					
							<label for="option-one" class="pure-checkbox">
								<input id="option-one" type="checkbox" value="">
								Select Tier 5s
							</label>
						</div>
				</fieldset>
				<button type="submit" class="pure-button pure-input-1-2 pure-button-primary">Upload</button>
 -->
<!-- 
			</form><br><hr>
			<form action="upload.php" method="post" class=pure-form enctype="multipart/form-data">
				Select CSV to upload:
				<input type="file" name="fileToUpload" id="fileToUpload"><br><br>
				<button type="submit" class="pure-button pure-input-1-2 pure-button-primary" >Upload File</button>
			</form>
 -->
 
			<form action=functions.php method=post class="pure-form pure-form-stacked">
				<fieldset>
					<legend>Upload Tickets</legend>

					<div class="pure-g">
						<div class="pure-u-1 pure-u-md-1-3">
							<textarea name='csvData' style="line-height: 50px;" class="pure-input-1-2" placeholder="CSV String From Splunk"></textarea>
						</div>
					</div>
					<p>Select People on Appointments - Works, but Experimental.</p>
					<style scoped>
					.text-area {
						padding: .5em .6em;
						display: inline-block;
						border: 1px solid #ccc;
						box-shadow: inset 0 1px 3px #ddd;
						border-radius: 4px;
						vertical-align: middle;
						box-sizing: border-box;
						width:50%;
						height: 100px;
						overflow-y: scroll;
					}					
					</style>
					<div class="text-area" style="height:200px">
						<?= getOfflineEmployees() ?>
					</div>

					<label for="option" class="pure-checkbox">
						<input id="option" name="option-one" type="checkbox"> Select Tier 5s
					</label>

					<button type="submit" class="pure-button pure-input-1-2 pure-button-primary">Upload</button>
				</fieldset>
			</form>
		</div>
	</div>
  </body>
</html>