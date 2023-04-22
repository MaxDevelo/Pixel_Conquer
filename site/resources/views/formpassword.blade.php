@extends('layouts.app')

@section('title', 'Change password')
@section('container')
@parent
<form action="/admin/changepassword" method="post">
	@csrf
	<label for="newpassword">New password</label>         <input type="password" id="newpassword"	 name="newpassword"	 required></br>
	<label for="confirmpassword">Confirm password</label><input type="password" id="confirmpassword" name="confirmpassword" required></br>
  <input type="submit" value="Change my password"></br>
</form>
<p>
	Go back to <a href="{{route('account')}}">Home</a>.
</p>
@endsection
