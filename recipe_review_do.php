<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rezept bewerten - Nosh Cuisine</title>
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
    require("includes/header_inc.php");

    #id übergeben?
    if(!isset($_GET["id"])){
    die("<div>Hier ist wohl ein Fehler aufgetreten!</div>");
    }
    #User eingeloggt?
    if(!isset($_SESSION["user_id"])){
        echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'> Du bist noch nicht eingeloggt.</div>";
        echo "<div class=' fliesstext center_text'>Zum Login kommst du <a href='login.php'>hier!</a></div>";
        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
        require("includes/footer_inc.php");
        die();
    }
    #prüfen, ob alles Nötige korrekt gesendet wurde)
    if (!isset($_POST["stars"]) or !isset($_POST["comment"]) or !isset($_FILES["file"])) {
        echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'> Hier ist wohl etwas schiefgelaufen....</div>";
        echo "<div class=' fliesstext center_text'>Zur Startseite kommst du <a href='index.php'>hier!</a></div>";
        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
        require("includes/footer_inc.php");
        die();
    }

    #Zeitpunkt des Eintrags ermitteln:
    $time_posted = date('Y-m-d');

    #Eintrag in die review Tabelle
    $statement = $pdo->prepare("INSERT INTO review (comment, stars, recipe_id, when_posted, user_id) VALUES (?,?,?,?,?)");

    if($statement->execute(array(htmlspecialchars($_POST["comment"]), htmlspecialchars($_POST["stars"]),htmlspecialchars($_GET["id"]),htmlspecialchars($time_posted),htmlspecialchars($_SESSION["user_id"])))){
        echo "";
    }else{
        die("Datenbank-Fehler1");
    }

    #Eintrag in die picture Tabelle:
    #random string funktion
    mt_srand((double) microtime() * 1000000);  #Zufallsgenerator "schütteln"
    $zeichen = "ABCDEFGHIKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";   #Zeichenpool
    $pin = "";
    for ($i=1;$i<=15;$i++){ #15 stelligen PIN aus dem Zeichenpool erzeugen
        $pin = $pin.$zeichen[mt_rand(0,(strlen($zeichen)-1))];
    }

    #Vorbereitungen für Datei Upload
    if(!isset($_FILES["file"]["tmp_name"]) || !isset($_FILES["file"]["name"])){
        die("Fehler im Upload");
    }
    if($_FILES["file"]["name"]!= null){
        $fileName = $_FILES["file"]["name"];
        $fileType = pathinfo($fileName,PATHINFO_EXTENSION);

        if($fileType == "jpg" OR $fileType == "png" OR $fileType == "pdf" OR $fileType == "HEIC" OR $fileType == "jpeg" OR $fileType == "JPG"){
            echo "";
        }else{
            die("<div class='textinmiddle'>Nicht zugelassene Dateiart. Folgende Dateiarten sind zugelassen: jpg, png, pdf, HEIC.<br></div>");
        }

        if($_FILES["file"]["size"] > 80000000){
            die("<div>Diese Datei ist leider zu groß.</div> ");
        }

        $fileNameNew = $pin.".".$fileType;

        if(!move_uploaded_file($_FILES["file"]["tmp_name"],"/home/mf177/public_html/files/".$fileNameNew )){
            die ("<div>Upload fehlgeschlagen.</div>");
        }

        #Eintrag in die picture Tabelle

        $statement = $pdo->prepare("INSERT INTO picture (filename, recipe_id, user_id) VALUES (?,?,?)");
        if($statement->execute(array(htmlspecialchars($fileNameNew),htmlspecialchars($_GET["id"]),htmlspecialchars($_SESSION["user_id"])))){
            echo "";
        }else{
            die("Datenbank-Fehler2");
        }
    }
    #wenn der obige Code erfolgreich ausgeführt wurde, kommt diese Meldung. (ansonsten wird das Skript ja vorher schon mit die() unterbrochen)
    echo "<div class='ueberschrift'>Juhu!</div><div class='fliesstext center_text'> Dein Kommentar ist eingetragen!</div>";
    echo "<div class=' fliesstext center_text'>Zurück zum Rezept kommst du <a href='recipe_detailview.php?id=".$_GET['id']."'>hier!</a></div>";
    echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";


    ?>
    <?php
    require("includes/footer_inc.php")
    ?>
</div>
</div>
</body>
</html>