
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Signin</title>
	</head>
	<body>
		<h1>Signin</h1>
		<form action="/authenticate" method="post">
			<label for="login">Login</label>      <input type="text"     id="login"    name="login"    required autofocus>
			<label for="password">Password</label><input type="password" id="password" name="password" required>
			<input type="submit" value="Signin">
		</form>
		<p>
			If you don't have an account, <a href="/signup">signup</a> first.
		</p>
<?php if ( !empty($_SESSION['message']) ) { ?>
		<section>
			<p><?= $_SESSION['message'] ?></p>
		</section>
<?php } ?>
	</body>
</html>
