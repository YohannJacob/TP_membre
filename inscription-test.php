<!-- Ici commence le php -->
<?php
// Appel vers la base de donnée
try {
    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
// Gérer les erreurs
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Déclaration des variables vide pour le fonctionnement de la page
$erreurPseudo = false;
$erreurMail = false;
$erreurMdp = false;
$erreurMailForm = false;
$confirmationInscription = false;

// Vérification si une session est ouverte
session_start();
if (!empty($_SESSION)) {
    header('location: page.php');
}

// Vérification de la validité des informations

if (isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['pass']) && isset($_POST['passVerif'])) {
    // vérification du pseudo dans la base de donneés
    $reqPseudo = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo = ? ');
    $reqPseudo->execute(array(htmlspecialchars($_POST['pseudo'])));
    $verifPseudo = $reqPseudo->fetch();
    $reqPseudo->closeCursor();

    // vérification du mail dans la base de donneés
    $reqMail = $bdd->prepare('SELECT email FROM membres WHERE email = ? ');
    $reqMail->execute(array(htmlspecialchars($_POST['email'])));
    $verifMail = $reqMail->fetch();
    $reqMail->closeCursor();

    include 'erreur.php';
    
    if ((strtolower($_POST['pseudo']) != strtolower($verifPseudo['pseudo'])) && (strtolower($_POST['email']) != $verifMail['email']) && ($_POST['pass'] == $_POST['passVerif'])) {

        $_POST['email'] = htmlspecialchars($_POST['email']); // On rend inoffensives les balises HTML que le visiteur a pu rentrer
        $pass_hache = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $req = $bdd->prepare('INSERT INTO membres(pseudo, pass, email, date_inscription) VALUES(:pseudo, :pass, :email, CURDATE())');
        $req->execute(array(
            'pseudo' => $_POST['pseudo'],
            'pass' => $pass_hache,
            'email' => $_POST['email'],
        ));

        $_SESSION['pseudo'] = $_POST['pseudo'];
        header('location: page.php');
    }
}

?>

<!-- ici on commence le HTML -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">

<head>
    <title>Espace membre</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>

    <h1>Bonjour. Sur cette page vous pouvez créer votre compte.</h1>

    <div class="content">
        <p>Veuillez choisir votre identifiant et mot de passe.</p>

        <form action="inscription-test.php" method="POST">

            <p><label>Pseudo</label> : <input type="text" id="pseudo" name="pseudo" /></p>
            <p><label>Mot de passe</label> : <input type="password" id="pass" name="pass" required/></p>
            <p><label>Confirmation mot de passe</label> : <input type="password" id="passVerif" name="passVerif" required/></p>
            <p><label>Email</label> : <input type="text" id="email" name="email" /></p>

            <p><input type="submit" value="Valider" id="bt_post" /></p>

            <?php echo $erreurPseudo; ?>
            <?php echo $erreurMail; ?>
            <?php echo $erreurMdp; ?>
            <?php echo $erreurMailForm; ?>

        </form>
    </div>
</body>