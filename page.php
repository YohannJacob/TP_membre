<?php
session_start();
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

        <h1>Bonjour, <?php echo $_SESSION['pseudo'] . ' vous êtes connecté !' ?></h1>

        <div class="content">
        <p><a href="deconnexion.php">Vous déconnecter</a></p>
        </div>
    </body>
