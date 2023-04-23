<?php

namespace App\Http\Controllers;

use App\Models\Pixel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MyUser;
use App\Models\UserEloquent;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  /**
   * Show the signin page
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function home(Request $request)
  {
    // Créer 2500 instances de modèle Pixel
    // for ($i = 0; $i < 50; $i++) {
    //   for ($j = 0; $j < 50; $j++) {
    //     $pixel = new Pixel([
    //       'coordinate_x' => $i,
    //       'coordinate_y' => $j,
    //       'color' => '#FFFFFF',
    //       'user_id' => null
    //     ]);

    //     $pixel->save();
    //   }
    // }
    if ($request->session()->get("User")) {
      $this->givepixel($request);
      $user = $request->session()->get("User");
      $user = UserEloquent::where('user_id', $user->user_id)->firstOrFail();
      $request->session()->put('User', $user);
      return view('home', ['message' => $request->session()->get('message'), 'User' => $request->session()->get("User")]);
    } else {
      return view('home', ['message' => $request->session()->get('message')]);
    }
  }
  public function signin(Request $request)
  {
    return view('signin', ['message' => $request->session()->get('message')]);
  }

  public function account(Request $request)
  {
    $user = $request->session()->get("User");
    $user = UserEloquent::where('user_id', $user->user_id)->firstOrFail();
    $request->session()->put('User', $user);
    $this->givepixel($request);
    return view('account', ["User" => $request->session()->get("User") ?? null]);
  }
  public function signup(Request $request)
  {
    return view('signup', ['message', $request->session()->get('message')]);
  }
  public function formpassword(Request $request)
  {
    return view('formpassword', ['message', $request->session()->get('message'), 'User' => $request->session()->get("User")]);
  }

  public function signout(Request $request)
  {
    $request->session()->flush();
    return redirect()->route('signin');
  }

  public function authenticate(Request $request)
  {
    $request->session()->put('User', null);
    $request->session()->forget('message');

    if (!$request->input('mail') || !$request->input('password')) {
      return redirect()->route('signin');
      exit();
    }

    // 3. On sécurise les données reçues
    $mail = (string) htmlentities($_POST['mail']);
    $password = (string) htmlentities($_POST['password']);


    try {
      $user = UserEloquent::where('mail', $mail)->firstOrFail();
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      $request->session()->flash('message', $e->getMessage());
      return redirect()->route('signin');
      exit();
    }
    if (Hash::check($password, $user->password)) {
      $request->session()->put('User', $user);
      return redirect()->route('account');
      exit();
    } else {
      $request->session()->flash('message', 'problème');
      return redirect()->route('signin');
      exit();
    }
    exit();
  }

  public function adduser(Request $request)
  {

    $request->session()->forget('message');

    if (!$request->hasAny(['login', 'mail', 'password', 'confirm'])) {
      $request->session()->flash('message', 'Some POST data are missing.');
      return redirect()->route('signin');
      exit();
    }


    // 3. On sécurise les données reçues
    $login = htmlspecialchars($request->input('login'));
    $password = htmlspecialchars($request->input('password'));
    $mail = htmlspecialchars($request->input('mail'));
    $color = htmlspecialchars($request->input('color'));
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      $request->session()->flash('message', "Error mail :");
      return redirect()->route('signup');
      exit();
    }


    $existingUser = UserEloquent::where('mail', $mail)->first();
    if ($existingUser) {
      $request->session()->flash('message', 'This email is already in use');
      return redirect()->route('signup');
    }


    try {
      $userEloquent = new UserEloquent;

      $userEloquent->user = $login;
      $userEloquent->password = Hash::make($password);
      $userEloquent->color = $color;
      $userEloquent->mail = $mail;

      $userEloquent->save();

      return redirect()->route("signin");
      exit();
    } catch (QueryException $e) {
      return redirect()->route("signup")->with('message', "Une erreur s'est produite ! " . $e->getMessage());
      exit();
    }
  }

  public function changepassword(Request $request)
  {

    $request->session()->forget('message');

    $user = $request->input('User');
    /******************************************************************************
     * Traitement des données de la requête
     */

    // On vérifie que les données attendues existent
    if (empty($_POST['newpassword']) || empty($_POST['confirmpassword'])) {
      $request->session()->flash('message', "Some POST data are missing.");
      redirect()->route('formpassword');
    }

    // 3. On sécurise les données reçues
    $password = htmlspecialchars($request->input('newpassword'));
    $confirmpassword = htmlspecialchars($request->input('confirmpassword'));


    // 4. On s'assure que les 2 mots de passes sont identiques
    if ($password != $confirmpassword) {
      $request->session()->flash('message', "Error: passwords are different.");
      return redirect()->route('formpassword');
    }

    try {
      $user->password = Hash::make($password);
      $user->save();
    } catch (QueryException $e) {
      return redirect()->route('formpassword')->with('message', "Une erreur s'est produite ! " . $e->getMessage());
    }


    // 3. On indique que le mot de passe a bien été modifié
    $request->session()->flash('message', "Password successfully updated.");
    // 4. On sollicite une redirection vers la page du compte
    return redirect()->route('account');
  }

  public function deleteuser(Request $request)
  {

    unset($_SESSION['message']);

    $user = $request->session()->get('User');
    try {
      $user->delete();
      $request->session()->flash('message',  $user);
    } catch (PDOException $e) {

      $request->session()->flash('message',  $e->getMessage());
      redirect()->route('account');
      exit();
    } catch (Exception $e) {

      $request->session()->flash('message',  $e->getMessage());
      redirect()->route('account');
      exit();
    }


    $request->session()->flush();

    $request->session()->flash('message',  "Account successfully deleted.");
    return redirect()->route('signin');
    exit();
  }
  // Give 10 pixel per day
  public function givepixel(Request $request)
  {
    $user = $request->session()->get('User');
    $user = UserEloquent::where('user_id', $user->user_id)->firstOrFail();
    $lastUpdate = Carbon::parse($user->last_update)->startOfDay();
    $today = Carbon::now()->startOfDay();
    $diffInDays = $today->diffInDays($lastUpdate);

    if ($diffInDays >= 1) {
      // Ajoute 10 pixels et met à jour la date de dernière mise à jour
      $user->pixel += 10;
      $user->last_update = Carbon::now();
      $user->save();
    }

    $request->session()->put('User', $user);
    return redirect()->route('account');
  }
}
