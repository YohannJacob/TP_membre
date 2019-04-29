<?php

// Appel vers la base de donnée
try
{
   $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
// Gérer les erreurs
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

session_start();
if (!empty($_SESSION)){
    header('location: page.php');
}

//  Récupération de l'utilisateur et de son pass hashé
if (!empty($_POST)) {
    $req = $bdd->prepare('SELECT id, pass FROM membres WHERE pseudo = :pseudo');
    $req->execute(array(
        'pseudo' => $_POST['pseudo']
    ));

    $resultat = $req->fetch();

    $isPasswordCorrect = password_verify($_POST['pass'], $resultat['pass']);

if (!$resultat){
    echo 'Mauvais identifiant ou mot de passe !';
}
else
{
    if ($isPasswordCorrect) {
        session_start();
        $_SESSION['id'] = $resultat['id'];
         $_SESSION['pseudo'] = $_POST['pseudo'];
        // $_SESSION['pseudo'] = $pseudo;
        header('location: page.php');
    }
    else {
        echo $_POST['pseudo']. 'Mauvais identifiant ou mot de passe !';
    }
}
}

// Comparaison du pass envoyé via le formulaire avec la base


?>

<!-- ici on commence le HTML -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >

    <head>
        <title>Espace membre</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="stylesheet" href="style.css" />
    </head>

    <body>

        <h1>Bonjour. Merci de vous identifier.</h1>

        <div class="content">
            <form action="connexion.php" method="POST">

                <p><label>Pseudo</label> : <input type="text" id="pseudo" name="pseudo"/></p>
                <p><label>Mot de passe</label> : <input type="password" id="pass" name="pass" /></p>
                <p><input type="submit" value="Valider" id="bt_post"/></p>
                <p><a href="inscription-test.php">Pas encore inscrit ? Créez votre compte.</a></p>

            </form>
        </div>
    </body>