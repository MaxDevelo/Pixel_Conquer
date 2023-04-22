<?php
/******************************************************************************
 * Initialisation.
 */

unset($_SESSION['message']);

/******************************************************************************
 * Traitement des données de la requête
 */

// 2. On vérifie que les données attendues existent
if ( empty($_POST['login']) || empty($_POST['password']) )
{
	$_SESSION['message'] = "Some POST data are missing.";
	header('Location: /');
	exit();
}

// 3. On sécurise les données reçues
$login = htmlspecialchars($_POST['login']);
$password = htmlspecialchars($_POST['password']);

/******************************************************************************
 * Chargement du model
 */

use App\Models\MyUser;

/******************************************************************************
 * Authentification
 */

// 1. On crée l'utilisateur avec les identifiants passés en POST
$user = new MyUser($login,$password);

// 2. On vérifie qu'il existe dans la BDD
try {
	if ( !$user->exists() )
	{
		$_SESSION['message'] = 'Wrong login/password.';
		header('Location: /');
		exit();
	}
}
catch (PDOException $e) {
	// Si erreur lors de la création de l'objet PDO
	// (déclenchée par MyPDO::pdo())
	$_SESSION['message'] = $e->getMessage();
	header('Location: /');
	exit();
}
catch (Exception $e) {
	// Si erreur durant l'exécution de la requête
	// (déclenchée par le throw de $user->exists())
	$_SESSION['message'] = $e->getMessage();
	header('Location: /');
	exit();
}

// 3. On sauvegarde le login dans la session
$_SESSION['user'] = $login;

// 4. On sollicite une redirection vers la page du compte
header('Location: admin/account');
exit();
