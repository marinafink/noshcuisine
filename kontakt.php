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
    <!--HEADER-->
    <?php
    require("includes/header_inc.php");
    ?>
    <!--CONTENT-->
    <div class="ueberschrift">Kontakt</div><br>
    <p class="center_text fliesstext">Du hast Fragen zu <b>Nosh Cuisine</b>, Anregungen oder Anlass zur Kritik? Wir freuen uns über eine Nachricht von dir!</p>
    <br>
    <form action="kontakt_do.php" method="post" enctype="multipart/form-data">
        <div class="kontakt">
            <div class='form-outline mb-2'>
                <label for='username' class='form-label'><div class='kleintext'>Vorname</div></label>
                <input type='text' placeholder='Max' name="name" class='form-control form-control-lg'>
            </div>
            <div class='form-outline mb-2'>
                <label for='username' class='form-label'><div class='kleintext'>Nachname</div></label>
                <input type='text' placeholder='Mustermann' name="surname" class='form-control form-control-lg'>
            </div>
            <div class='form-outline mb-2'>
                <label for='username' class='form-label'><div class='kleintext'>E-Mail-Adresse</div></label>
                <input type='email' placeholder='max.mustermann@gmail.com' name="mail" class='form-control form-control-lg'>
            </div>
            <div class='form-outline mb-2'>
                <label for='username' class='form-label'><div class='kleintext'>Betreff</div></label>
                <input type='text' placeholder='Um was geht es bei deiner Kontaktaufnahme?' class='form-control form-control-lg' name='betreff'>
            </div>
            <br>
            <div class='kleintext center_text'>Nachricht</div>
            <div class="mb-3 nachricht_zentrieren">
                <textarea class="form-control"  id="exampleFormControlTextarea1" name="nachricht" placeholder='Alles rein was du uns mitteilen und/oder fragen willst!' rows="12"></textarea>
            </div>
            <div class="container_mittig">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="updates" id="updates" value="1">
                    <label for="updates" class="form-check-label fliesstext">Ich bin damit einverstanden, dass meine abgesendeten Daten zum Zweck der Bearbeitung des Anliegens verarbeitet werden.</label>
                </div>
            </div>
            <br><br>
            <div class="container_mittig">
                <button class="subscribe-btn" type="submit" style="align-items: center">Absenden</button>
            </div>
        </div>
    </form>
    <br><br><br>
    <div class="center_text unterüberschrift">In der Regel melden wir uns innerhalb von 24 Stunden bei dir!</div>
    <br><br><br>
    <!--FOOTER-->
    <?php
    require("includes/footer_inc.php");
    ?>
</div>
</div>
</body>
</html>
