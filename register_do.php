<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Registrieren - Nosh Cuisine</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" type="image/png" sizes="32x32" href="pic_collection/favicon.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class='all'
            <div class="main_wrapper">
                <?php
                if (isset($_SESSION["user_id"])){
                    require("includes/header_inc.php");
                    echo "<div class='ueberschrift'>Upsala!</div>";
                    echo "<div class='fliesstext center_text'>Du bist schon eingeloggt, " . $_SESSION["username"] . ".<br>Logge dich wieder aus, damit du ein anderes Konto registrieren kannst!</div>";
                    echo "<div class='bild_mittig'><img src='pic_collection/family_meal.jpg' alt='Familie am Esstisch' style='max-width:1400px'></div>";
                    require("includes/footer_inc.php");
                    die();
                }
                if(!isset($_POST["username"]) or !isset($_POST["password"]) or!isset($_POST["mail"])){
                    require("includes/header_inc.php");
                    echo "<div class='ueberschrift'>Upsala!</div>";
                    echo "<div class='fliesstext center_text'>Da wurde wohl etwas nicht korrekt mitgeschickt! Probiere es bitte <a href='register.php'>nochmal</a>.</div>";
                    echo "<div class='bild_mittig'><img src='pic_collection/family_meal.jpg' alt='Familie am Esstisch' style='max-width:1400px'></div>";
                    require("includes/footer_inc.php");
                    die();
                }
                $membership_since = date('Y-m-d');
                $statement = $pdo->prepare("INSERT INTO user(username, password, mail, membership_since) VALUES (?,?,?,?) ");

                if (($_POST["username"]=="")or($_POST["password"]=="")or($_POST["mail"]=="")){
                    require("includes/header_inc.php");
                    echo "<div class='ueberschrift'>Upsala!</div>";
                    echo "<div class='fliesstext center_text'>Du hast wohl vergessen einen Usernamen, eine E-Mail-Addresse oder ein Passwort einzugeben.<br>Probiere es bitte <a href='register.php'>nochmal</a>.</div>";
                    echo "<div class='bild_mittig_groß'><img src='pic_collection/family_meal.jpg' alt='Familie am Esstisch' style='max-width:1400px'></div>";
                    require("includes/footer_inc.php");
                    die ();
                }else{
                    if($statement->execute(array(htmlspecialchars($_POST["username"]), htmlspecialchars(password_hash($_POST["password"], PASSWORD_BCRYPT)), htmlspecialchars($_POST["mail"]), htmlspecialchars($membership_since)))){
                        #ID ABSPEICHERN KANN MAN HIER NICHT
                        $_SESSION["username"]=$_POST["username"];
                        require("includes/header_inc.php");
                        echo "<div class='ueberschrift'>Willkommen auf Nosh Cuisine, " . $_SESSION["username"] . "!</div>";
                        echo "<div class='fliesstext center_text'>Logge dich <a href='login.php'>hier</a> ein und entdecke leckere Rezepte!<br><br>Auf deiner Profilseite kannst du dein individuelles Profilbild hochladen und deine Favoriten, sowie von dir erstellte Rezepte sehen!</div>";
                        echo "<div class='bild_mittig_groß'><img src='pic_collection/family_cooking.jpg' alt='Familie am Esstisch' style='max-width:1400px'></div>";
                        require("includes/footer_inc.php");
                    }else{
                        #DURCH UNIQUE EINSCHRÄNKUNGUNG ALS INDIZE IN DER DB, WIRD SICHERGESTELLT, DAS JEDER NUTZERNAME UND JEDE MAIL NUR EINMAL VERGEBEN WIRD
                        require("includes/header_inc.php");
                        echo "<div class='ueberschrift'>Upsala!</div>";
                        echo "<div class='fliesstext center_text'>Da ist wohl etwas schief gelaufen. Der angegebene Username wurde bereits vergeben oder für diese Mail besteht schon ein anderer Account. <br>Probiere es bitte <a href='register.php'>nochmal</a>.</div>";
                        echo "<div class='bild_mittig_groß'><img src='pic_collection/family_meal.jpg' alt='Familie am Esstisch' style='max-width:1400px'></div>";
                        require("includes/footer_inc.php");
                        die();
                    }
                }
                ?>
            </div>
        </div>
    </body>
    </html>