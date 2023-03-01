<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Benutzerprofil bearbeiten - Nosh Cuisine</title>
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
<!--HEADER-->
    <?php
    require("includes/header_inc.php");
    ?>

    <!-- Content -->
    <div id="contentWrapper">

        <?php
        if (!isset($_POST["username"]) or !isset($_POST["password"]) or !isset($_POST["mail"]) or !isset($_FILES["file"])) {
            echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'> Hier ist wohl etwas schiefgelaufen....</div>";
            echo "<div class=' fliesstext center_text'>Zur Startseite kommst du <a href='index.php'>hier!</a></div>";
            echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
            require("includes/footer_inc.php");
            die();
        }


        if(!isset($_SESSION["user_id"])){
            echo "<div class='ueberschrift'>Ups!</div><div class='fliesstext center_text'> Du musst dich zuerst einloggen.</div>";
            echo "<div class=' fliesstext center_text'>Hier gehts zum <a href='login.php'>Login</a>!</div>";
            echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
            require("includes/footer_inc.php");
            die();
        }

        #random string funktion
        mt_srand((double) microtime() * 1000000);  #Zufallsgenerator "schütteln"
        $zeichen = "ABCDEFGHIKLMNPQRSTUVWXYZ0123456789";   #Zeichenpool
        $pin = "";
        for ($i=1;$i<=15;$i++){ #15 stelligen PIN aus dem Zeichenpool erzeugen
            $pin = $pin.$zeichen[mt_rand(0,(strlen($zeichen)-1))];
        }
        #Vorbereitungen für Datei Upload
        if(!isset($_FILES["file"]["tmp_name"]) and !isset($_FILES["file"]["name"])){
            die("Fehler im Upload");
        }
        if($_FILES["file"]["name"]!= null) {
            $fileName = $_FILES["file"]["name"];
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileNameNew = $pin . "." . $fileType;

            if ($fileType == "jpg" or $fileType == "png" or $fileType == "HEIC" or $fileType == "jpeg" or $fileType == "JPG") {
                echo "";
            } else {
                echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'>Diese Dateiart ist nicht zugelassen.<br>Zugelassene Dateiarten sind: jpg, png und HEIC.</div>";
                echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                require("includes/footer_inc.php");
                die();
            }

            if ($_FILES["file"]["size"] > 80000000) {
                echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'>Diese Dateiart ist leider zu groß,<br>versuch es noch einmal!</div>";
                echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                require("includes/footer_inc.php");
                die();
            }
            if (!move_uploaded_file($_FILES["file"]["tmp_name"], "/home/mf177/public_html/files/" . $fileNameNew)) {
                echo "<div class='ueberschrift'>Oh, nein!</div><div class='fliesstext center_text'>Da ist was beim Upload schief gelaufen.<br>Probier's nochmal!</div>";
                echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                require("includes/footer_inc.php");
                die();
            }
        }


        #Eintrag in die user Tabelle
        $statement = $pdo->prepare("UPDATE user SET username=?, password=?, mail=?, profilepicture=? WHERE id=?");
        $statement2 = $pdo->prepare("SELECT * FROM user WHERE id=:id");
        $statement2->bindParam(":id", $_GET["id"]);
        if (($_POST["username"]) == null AND ($_POST["password"]) == null AND ($_POST["mail"]) == null) {
            echo "<div class='ueberschrift'>Oh, was vergessen?</div><div class='fliesstext center_text'>Fülle bitte alle Felder aus!</div>";
            echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
            echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
            require("includes/footer_inc.php");
            die();
        }else{
            echo "";
        }
        if($statement2->execute()) {
            while ($row = $statement2->fetch()) {
                if ($_FILES["file"]["name"]== NULL AND $_POST["password"] != "********"){
                    $oldfile = $row["profilepicture"];
                    if ($statement->execute(array(htmlspecialchars($_POST["username"]), htmlspecialchars(password_hash($_POST["password"], PASSWORD_BCRYPT)), htmlspecialchars($_POST["mail"]), htmlspecialchars($oldfile), $_GET["id"]))){
                        echo "<div class='ueberschrift'>Yeaaaaaaah!</div><div class='fliesstext center_text'>Dein Profil wurde erfolgreich aktualisiert.</div>";
                        echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                    } else {
                        echo "<div class='ueberschrift'>Da heißt wohl jemand schon wie du!</div><div class='fliesstext center_text'>Benutzername oder E-Mail existiert bereits.<br>Bitte versuche es erneut!</div>";
                        echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                        require("includes/footer_inc.php");
                        die();
                    }
                }elseif ($_FILES["file"]["name"]== NULL AND $_POST["password"] == "********") {
                    $oldpassword = $row["password"];
                    $oldfile = $row["profilepicture"];
                            if ($statement->execute(array(htmlspecialchars($_POST["username"]), htmlspecialchars($oldpassword), htmlspecialchars($_POST["mail"]), htmlspecialchars($oldfile), $_GET["id"]))){
                                echo "<div class='ueberschrift'>Yeaaaaaaah!</div><div class='fliesstext center_text'>Dein Profil wurde erfolgreich aktualisiert.</div>";
                                echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                                echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                            } else {
                                echo "<div class='ueberschrift'>Da heißt wohl jemand schon wie du!</div><div class='fliesstext center_text'>Benutzername oder E-Mail existiert bereits.<br>Bitte versuche es erneut!</div>";
                                echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                                echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                                require("includes/footer_inc.php");
                                die();
                            }
                }elseif ($_POST["password"] == "********") {
                    $oldpassword = $row["password"];
                    if ($statement->execute(array(htmlspecialchars($_POST["username"]), htmlspecialchars($oldpassword), htmlspecialchars($_POST["mail"]), htmlspecialchars($fileNameNew), $_GET["id"]))){
                        echo "<div class='ueberschrift'>Yeaaaaaaah!</div><div class='fliesstext center_text'>Dein Profil wurde erfolgreich aktualisiert.</div>";
                        echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                    } else {
                        echo "<div class='ueberschrift'>Da heißt wohl jemand schon wie du!</div><div class='fliesstext center_text'>Benutzername oder E-Mail existiert bereits.<br>Bitte versuche es erneut!</div>";
                        echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                        require("includes/footer_inc.php");
                        die();
                    }
                }else{
                    if ($statement->execute(array(htmlspecialchars($_POST["username"]), htmlspecialchars(password_hash($_POST["password"], PASSWORD_BCRYPT)), htmlspecialchars($_POST["mail"]), htmlspecialchars($fileNameNew), $_GET["id"]))){
                        echo "<div class='ueberschrift'>Yeaaaaaaah!</div><div class='fliesstext center_text'>Dein Profil wurde erfolgreich aktualisiert.</div>";
                        echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                    } else {
                        echo "<div class='ueberschrift'>Da heißt wohl jemand schon wie du!</div><div class='fliesstext center_text'>Benutzername oder E-Mail existiert bereits.<br>Bitte versuche es erneut!</div>";
                        echo "<div class=' fliesstext center_text'>Hier gehts zurück zum <a href='benutzerprofil.php'>Benutzerprofil</a>!</div>";
                        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                        require("includes/footer_inc.php");
                        die();
                    }
                }
            }
        }else{
            die("Datenbank-Fehler");
        }


        ?>

<!--FOOTER-->
<?php
require("includes/footer_inc.php");
?>
    </div>
</div>
</body>
</html>

