
@extends('layouts.app')

@section('title', 'Pixel Conquer - Account')
@section('container')
@parent
<p>
	Hello {{$UserEloquent->value('user')}} !<br>
	Welcome on your account.
</p>
<ul>
	<li><a href="/admin/formpassword">Change password.</a></li>
</ul>
<p><a href="/admin/signout">Sign out</a></p>

@endsection
