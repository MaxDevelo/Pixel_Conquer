@extends('layouts.app')

@section('title', 'Pixel Conquer - Signin')
@section('container')
	@parent
	<form action="/authenticate" method="post">
		@csrf
		<label for="mail">Email</label>       <input type="text"     id="mail"    name="mail"    required autofocus></br>
		<label for="password">Password</label> <input type="password" id="password" name="password" required></br>
 	<input type="submit" value="Signin"></br>
	</form>
	<p>
		If you don't have an account, <a href="{{route('signup')}}">signup</a> first.
	</p>
@endsection
