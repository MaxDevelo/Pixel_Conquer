
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Change password</title>
	</head>
	<body>
		<h1>Change password</h1>
		<form action="/admin/changepassword" m/ethod="post">
			<label for="newpassword">New password</label>        <input type="password" id="newpassword"	 name="newpassword"	 required>
			<label for="confirmpassword">Confirm password</label><input type="password" id="confirmpassword" name="confirmpassword" required>
			<input type="submit" value="Change my password">
		</form>
		<p>
			Go back to <a href="/admin/account">Home</a>.
		</p>
<?php if ( !empty($_SESSION['message']) ) { ?>
		<section>
			<p><?= $_SESSION['message'] ?></p>
		</section>
<?php } ?>
	</body>
</html>
