
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Signup</title>
	</head>
	<body>
		<h1>Signup</h1>
		<form action="/adduser" method="POST">
			<label for="login">Login</label>             <input type="text"     id="login"    name="login"    required autofocus>
			<label for="password">Password</label>       <input type="password" id="password" name="password" required>
			<label for="confirm">Confirm password</label><input type="password" id="confirm"  name="confirm"  required>
			<label for="color">Choose your team:</label>
			<select name="color" id="color">
				<option value="red">Red</option>
				<option value="green">Green</option>
				<option value="blue">Blue</option>
				<option value="yellow">Black</option>
				<option value="orange">Orange</option>
				<option value="purple">Purple</option>
			</select>
			<input type="submit" value="Signup">
		</form>
		<p>
			If you already have an account, <a href="/">signin</a>.
		</p>
<?php if ( !empty($_SESSION['message']) ) { ?>
		<section>
			<p><?= $_SESSION['message'] ?></p>
		</section>
<?php } ?>
	</body>
</html>
