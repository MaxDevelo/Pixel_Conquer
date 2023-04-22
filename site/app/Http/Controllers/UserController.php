<?php

namespace App\Http\Controllers;

use App\Models\Pixel;
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
    // Créer 900 instances de modèle Pixel
    // for ($i = 0; $i < 30; $i++) {
    //   for ($j = 0; $j < 30; $j++) {
    //     $pixel = new Pixel([
    //       'coordinate_x' => $i,
    //       'coordinate_y' => $j,
    //       'color' => '#FFFFFF',
    //       'user_id' => null
    //     ]);

    //     $pixel->save();
    //   }
    // }
    return view('home', ['message' => $request->session()->get('message')]);
  }
  public function signin(Request $request)
  {
    return view('signin', ['message' => $request->session()->get('message')]);
  }

  public function account(Request $request)
  {
    return view('account', ["UserEloquent" => $request->session()->get("UserEloquent") ?? null]);
  }
  public function signup(Request $request)
  {
    return view('signup', ['message', $request->session()->get('message')]);
  }
  public function formpassword(Request $request)
  {
    return view('formpassword', ['message', $request->session()->get('message'), 'UserEloquent' => $request->session()->get("UserEloquent")]);
  }

  public function signout(Request $request)
  {
    $request->session()->flush();
    return redirect()->route('signin');
  }

  public function authenticate(Request $request)
  {

    $request->session()->forget('message');

    if (!$request->input('login') || !$request->input('password')) {
      return redirect()->route('signin');
      exit();
    }

    // 3. On sécurise les données reçues
    $login = (string) htmlentities($_POST['login']);
    $password = (string) htmlentities($_POST['password']);


    try {
      $user = UserEloquent::where('user', $login)->firstOrFail();
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
      $request->session()->flash('message', $e->getMessage());
      return redirect()->route('signin');
      exit();
    }
    if (Hash::check($password, $user->password)) {
      $request->session()->put('UserEloquent', $user);
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

    if (!$request->hasAny(['login', 'password', 'confirm'])) {
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
    try {
      $userEloquent = new UserEloquent;

      $userEloquent->user = $login;
      $userEloquent->password = Hash::make($password);
      $userEloquent->color = $color;
      $userEloquent->mail = $color;

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

    $user = $request->input('UserEloquent');
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

    $user = $request->session()->get('UserEloquent');
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
}