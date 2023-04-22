
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>
      @yield('title')
    </title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @if ( session('User') )
      <link rel="stylesheet" type="text/css" href="css/styles.css">
      <link rel="icon" type="image/png" href="img/logo.png" />
    @else
      <link rel="stylesheet" type="text/css" href="css/styles.css">
      <link rel="icon" type="image/png" href="img/logo.png" />
    @endif
    <meta name="description" content="Pixel Conquer is a platform for creating pixel art. Join us now and express your creativity with our user-friendly interface.">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	</head>
	<body>
    @section('container')
    <header>
      <nav>
          <img class="logo" width="100" height="100" src="img/logo.png" alt="logo Pixel Conquer">
          <div>
              <a href="home">Home</a>
              <a href="a-propos.php">Buy Pixel</a>
              @if (session('User'))
                <a href="{{route('account')}}">Account</a>
              @else
                <a href="{{route('signin')}}">Signin</a>
              @endif
          </div>
      </nav>
</header>

    @show
    @include('shared.message')
	</body>
</html>
