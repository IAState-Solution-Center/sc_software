<?php
include('functions.php');

?>

<?= printHeader() ?>
			<div class='pure-g'>
				<div class="pure-u-1-3"></div>
				<div class="pure-u-1-3">
					<h2 align=center> Solution Center Tickets </h2>
					<form method=POST action=functions.php class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-control-group">
								<label for="name">Login</label>
								<input name=username id="name" type="text" placeholder="Phone Login">
								<span class="pure-form-message-inline">e.g. sc48t5</span>
							</div>

							<div class="pure-control-group">
								<label for="password">Password</label>
								<input name=password id="password" type="password" placeholder="Password">
							</div>

							<div class="pure-controls">
								<button type="submit" class="pure-button pure-button-primary">Submit</button>
							</div>
						</fieldset>
					</form>
					<h3><? echo $_SESSION['loginMessage']; ?></h3>
				</div>
			<div class="pure-u-1-3"></div>
		</div>
	</body>
</html>