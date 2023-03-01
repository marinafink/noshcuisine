<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kontakt - Nosh Cuisine</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="pic_collection/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="all">
    <div class="main_wrapper">
        <?php
        if(!isset($_POST["name"]) or !isset($_POST["surname"]) or !isset($_POST["mail"]) or!isset($_POST["betreff"]) or !isset($_POST["nachricht"])){
            require("includes/header_inc.php");
            echo "<div class='ueberschrift'>Upsala!</div>";
            echo "<div class='fliesstext center_text'>Da wurde wohl etwas nicht korrekt mitgeschickt! Probiere es bitte <a href='kontakt.php'>nochmal</a>.</div>";
            echo "<div class='bild_mittig'><img src='pic_collection/family_meal.jpg' alt='Familie am Esstisch' style='max-width:1400px'></div>";
            require("includes/footer_inc.php");
            die();
        }

        if($_POST['name']== NULL or $_POST['surname']== NULL or $_POST['mail']== NULL or $_POST['betreff']== NULL or $_POST['nachricht']== NULL) {
            echo "<script> alert('Bitte trage alle Felder ein um eine Nachricht an uns zu verschicken.')</script>";
            echo require ("kontakt.php");
        }else{
            $statement = $pdo->prepare("INSERT INTO kontakt (name, surname, mail, subject, message) VALUES (?,?,?,?,?)");
            if ($statement->execute(array(htmlspecialchars($_POST['name']), htmlspecialchars($_POST['surname']), htmlspecialchars($_POST['mail']), htmlspecialchars($_POST['betreff']), htmlspecialchars($_POST['nachricht'])))) {
                require("includes/header_inc.php");
                echo "<div class='ueberschrift'>Danke für deine Nachricht!</div><div class='fliesstext center_text'>Wir melden uns bald bei dir zurück!</div>";
                echo "<div class=' fliesstext center_text'>Entdecke jetzt viele weitere Rezepte auf <b>Nosh Cuisine</b> - <a href='search.php'> Jetzt stöbern!</a></div>";
                echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                require("includes/footer_inc.php");
            } else {
                #Datenbankfehler
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
</div>
</html>

