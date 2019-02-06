<?php
include('functions.php');

?>

<?= printHeader() ?>
			<div class='pure-g'>
				<div class="pure-u-1-3"></div>
				<div class="pure-u-1-3">
					<form method=POST action="functions.php?logout=1" class="pure-form pure-form-aligned">
						<fieldset>
							<div class="pure-controls">
								<button type="submit" class="pure-button pure-button-primary">Logout</button>
							</div>
						</fieldset>
					</form>
					<h3><? echo $_SESSION['loginMessage']; ?></h3>
				</div>
			<div class="pure-u-1-3"></div>
		</div>
	</body>
</html>