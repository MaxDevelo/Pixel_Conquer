<?php
/******************************************************************************
 * Initialisation.
 */

unset($_SESSION['message']);

/******************************************************************************
 * Traitement des données de la requête
 */


// 2. On vérifie que les données attendues existent
if ( empty($_POST['login']) || empty($_POST['password']) || empty($_POST['confirm']) )
{
	$_SESSION['message'] = "Some POST data are missing.";
	header('Location: /signup');
	exit();
}

// 3. On sécurise les données reçues
$login = htmlspecialchars($_POST['login']);
$password = htmlspecialchars($_POST['password']);
$confirm = htmlspecialchars($_POST['confirm']);

// 4. On vérifie que les deux mots de passe correspondent
if ( $password !== $confirm )
{
	$_SESSION['message'] = "The two passwords differ.";
	header('Location: /signup');
	exit();
}

/******************************************************************************
 * Chargement du model
 */

use App\Models\MyUser;

/******************************************************************************
 * Ajout de l'utilisateur
 */

// 1. On crée l'utilisateur avec les identifiants passés en POST
$user = new MyUser($login,$password);

// 2. On crée l'utilisateur dans la BDD
try {
	$user->create();
}
catch (PDOException $e) {
	// Si erreur lors de la création de l'objet PDO
	// (déclenchée par MyPDO::pdo())
	$_SESSION['message'] = $e->getMessage();
	header('Location: /signup');
	exit();
}
catch (Exception $e) {
	// Si erreur durant l'exécution de la requête
	// (déclenchée par le throw de $user->create())
	$_SESSION['message'] = $e->getMessage();
	header('Location: /signup');
	exit();
}

// 3. On indique que le compte a bien été créé
$_SESSION['message'] = "Account created! Now, signin.";

// 4. On sollicite une redirection vers la page d'accueil
header('Location: /');
exit();
