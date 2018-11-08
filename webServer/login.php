<?php
include('functions.php');
?>

<?= printHeader() ?>
			<div class='pure-g'>
				<div class="pure-u-1-3"></div>
				<div class="pure-u-1-3">
					<form class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="name">Login</label>
								<input id="name" type="text" placeholder="sc_username">
<!-- 								<span class="pure-form-message-inline">This is a required field.</span> -->
							</div>

							<div class="pure-control-group">
								<label for="password">Password</label>
								<input id="password" type="password" placeholder="Password">
							</div>

							<div class="pure-controls">
								<button type="submit" class="pure-button pure-button-primary">Submit</button>
							</div>
						</fieldset>
					</form>
				</div>
			<div class="pure-u-1-3"></div>
		</div>
	</body>
</html>