@extends('layouts.app')

@section('title', 'Pixel Conquer - Account')
@section('container')
    @parent
    <div>
        <h1>My Profile</h1>
        <hr style="width:40%;">
.
        <p style="font-size: 30px; text-align:center;">
            <span style="font-weight:bolder;">Name: </span> {{ $User->user }} <br>
            <span style="font-weight:bolder;">Email: </span> {{ $User->mail }}<br>
            <span style="font-weight:bolder;">You are : </span> {{ $User->pixels }} pixel(s)<br>
        </p>
        <a class="btn" href="/admin/formpassword">Change password.</a>
        <a class="btn quit" href="/admin/signout">Sign out</a>
    </div>

@endsection
