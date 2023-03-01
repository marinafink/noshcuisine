<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>

<!DOCTYPE html>
<html lang="en">
<div class="all">
<head>
    <meta charset="UTF-8">
    <title>Newsletter - Nosh Cuisine</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="pic_collection/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="main_wrapper">
        <?php
        #ABFRAGE OB FORMULARE KORREKT GESENDET WURDEN
        if (!isset($_POST["newsletter"])) {
            echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'> Hier ist wohl etwas schiefgelaufen....</div>";
            echo "<div class=' fliesstext center_text'>Zur Startseite kommst du <a href='index.php'>hier!</a></div>";
            echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
            require("includes/footer_inc.php");
            die();
        }
        #ÜBERPRÜFUNG OB EINGABE + BEREITS ANGEMELDET
        $statement = $pdo->prepare("SELECT * FROM newsletter");
        if($_POST['newsletter']== NULL){
            require("includes/header_inc.php");
            echo "<div class='ueberschrift'>Ups, da ist was schiefgelaufen!</div><div class='fliesstext center_text'> Trage bitte eine E-Mail-Adresse ein um dich zum Newsletter anzumelden.</div>";
            echo "<div class=' fliesstext center_text'>Hier gehts zurück zur <a href='index.php'>Startseite</a>!</div>";
            echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
            require("includes/footer_inc.php");
        }else{
            if($statement->execute(array())){
                while($row=$statement->fetch()){
                    if ($row['mail'] == $_POST["newsletter"]){
                        require("includes/header_inc.php");
                        echo "<div class='ueberschrift'>Ups!</div><div class='fliesstext center_text'> Du bist bereits zum Newsletter angemeldet.</div>";
                        echo "<div class=' fliesstext center_text'>Gib uns doch gerne über unser <a href='kontakt.php'>Kontaktformular</a> Feedback zu unserem Newsletter!</div>";
                        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                        require("includes/footer_inc.php");
                        die();
                    }else{
                        echo " ";
                    }
                }
            }
        }
        ?>

        <!--MAIL IN NEWSLETTERTABELLE-->
        <?php
        $newsletter = $_POST["newsletter"];
        if(($_POST['newsletter'] != NULL)) {
            $statement = $pdo->prepare("INSERT INTO newsletter (mail) VALUES (?)");
            if ($statement->execute(array(htmlspecialchars($_POST['newsletter'])))) {
                require("includes/header_inc.php");
                echo "<div class='ueberschrift'>Yeah! Willkommen!</div><div class='fliesstext center_text'>Freut uns, dass du dich zum Newsletter angemeldet hast!</div>";
                echo "<div class=' fliesstext center_text'>Entdecke jetzt viele weitere Rezepte auf <b>Nosh Cuisine</b> - <a href='search.php'> Jetzt stöbern!</a></div>";
                echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                require("includes/footer_inc.php");
            } else {
                die("Datenbank-Fehler");
            }
        }
        ?>
    </div>
</body>
</div>
</html>





