@extends('layouts.app')

@section('title', 'Pixel Conquer - Signup')
@section('container')
@parent
<form action="/adduser" method="POST">
	@csrf
	<label for="login">Login</label>             <input type="text"     id="login"    name="login"    required autofocus></br>
	<label for="email">Email</label>             <input type="email"    id="mail"    name="mail"    required></br>
	<label for="password">Password</label>       <input type="password" id="password" name="password" required></br>
	<label for="confirm">Confirm password</label><input type="password" id="confirm"  name="confirm"  required></br>
	<label for="color">Choose your color</label>
	<select name="color" id="color">
		<option value="red">Red</option>
		<option value="blue">Blue</option>
		<option value="green">Green</option>
		<option value="black">Black</option>
		<option value="purple">Purple</option>
		<option value="orange">Orange</option>
	</select></br>

	<input type="submit" value="Signup">
</form>
<p>
	If you already have an account, <a href="/">signin</a>.
</p>
@endsection
