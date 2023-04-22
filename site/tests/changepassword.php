<?php
/******************************************************************************
 * Initialisation.
 */

unset($_SESSION['message']);

/******************************************************************************
 * Vérification de la session
 */



// 2. On récupère le login dans une variable
$login = $_SESSION['user'];

/******************************************************************************
 * Traitement des données de la requête
 */

. On vérifie que les données attendues existent
if ( empty($_POST['newpassword']) || empty($_POST['confirmpassword']) )
{
	$_SESSION['message'] = "Some POST data are missing.";
	header('Location: admin/formpassword');
	exit();
}

// 3. On sécurise les données reçues
$newpassword     = htmlspecialchars($_POST['newpassword']);
$confirmpassword = htmlspecialchars($_POST['confirmpassword']);

// 4. On s'assure que les 2 mots de passes sont identiques
if ( $newpassword != $confirmpassword )
{
	$_SESSION['message'] = "Error: passwords are different.";
	header('Location: admin/formpassword');
	exit();
}

/******************************************************************************
 * Chargement du model
 */

use App\Models\MyUser;

/******************************************************************************
 * Changement du mot de passe
 */

// 1. On crée l'utilisateur avec les identifiants passés en POST
$user = new MyUser($login);

// 2. On change le mot de passe de l'utilisateur
try {
	$user->changePassword($newpassword);
}
catch (PDOException $e) {
	// Si erreur lors de la création de l'objet PDO
	// (déclenchée par MyPDO::pdo())
	$_SESSION['message'] = $e->getMessage();
	header('Location: admin/formpassword');
	exit();
}
catch (Exception $e) {
	// Si erreur durant l'exécution de la requête
	// (déclenchée par le throw de $user->changePassword())
	$_SESSION['message'] = $e->getMessage();
	header('Location: admin/formpassword');
	exit();
}

// 3. On indique que le mot de passe a bien été modifié
$_SESSION['message'] = "Password successfully updated.";

// 4. On sollicite une redirection vers la page du compte
header('Location: admin/account');
exit();
