<?php
session_start();
require("includes/db_inc.php");
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Nosh Cuisine</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="pic_collection/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class='all'>
        <div class="main_wrapper">
        <?php
        if (isset($_SESSION["user_id"])) {
            require("includes/header_inc.php");
            echo "<div class='ueberschrift'>Whoops!</div>";
            echo "<div class='fliesstext center_text'>You're already logged in, " . $_SESSION["username"] . ".<br>Log out first, so you can log in to another account.</div>";
            echo "<div class='bild_mittig_groß'><img src='pic_collection/family_cooking.jpg' alt='Kochende Familie' style='max-width:1400px'></div>";
            require("includes/footer_inc.php");
            die();
        }
        if(!isset($_POST["username"]) or !isset($_POST["password"])){
            require("includes/header_inc.php");
            echo "<div class='ueberschrift'>Whoops!</div>";
            echo "<div class='fliesstext center_text'>The username or password has not been entered! Please try <a href='login.php'>again</a>.</div>";
            echo "<div class='bild_mittig_groß'><img src='pic_collection/family_cooking.jpg' alt='Kochende Familie' style='max-width:1400px'></div>";
            require("includes/footer_inc.php");
            die();
        }
        #Überprüfung, dass in Feldern !=null ist, ist hier nicht nötig, da Überprüfung schon bei registrieren_do stattfindet und der user/ das passwort deshalb garnicht existieren kann.
        $statement = $pdo->prepare("SELECT * FROM user WHERE username=:username");
        $statement->bindParam(":username", $_POST["username"]);
        if($statement->execute()) {
            if ($row = $statement->fetch()) {
                if (password_verify($_POST["password"], $row["password"])) {
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["user_id"] = $row["id"];
                    require("includes/header_inc.php");
                    echo "<div class='ueberschrift'>Welcome back, " . $_SESSION["username"] . "!</div>";
                    echo "<div class='fliesstext center_text'>Good to see you back on Nosh Cuisine.<br>We hope you enjoy discovering our recipes!</div>";
                    echo "<div class='bild_mittig_groß'><img src='pic_collection/family_cooking.jpg' alt='Kochende Familie' style='max-width:1400px; padding: 100px 0px 400px'></div>";
                    require("includes/footer_inc.php");
                } else {
                    require("includes/header_inc.php");
                    echo "<div class='ueberschrift'>Upsala!</div>";
                    echo "<div class='fliesstext center_text'>This password is incorrect. Please try <a href='login.php'>again</a>.</div>";
                    echo "<div class='bild_mittig_groß'><img src='pic_collection/family_cooking.jpg' alt='Kochende Familie' style='max-width:1400px; padding: 100px 0px 400px'></div>";
                    require("includes/footer_inc.php");
                }
            } else {
                require("includes/header_inc.php");
                echo "<div class='ueberschrift'>Upsala!</div>";
                echo "<div class='fliesstext center_text'>This user does not exist. Please try <a href='login.php'>again</a>.</div>";
                echo "<div class='bild_mittig_groß'><img src='pic_collection/family_cooking.jpg' alt='Kochende Familie' style='max-width:1400px; padding: 100px 0px 400px'></div>";
                require("includes/footer_inc.php");
            }

        }else{
            require("includes/header_inc.php");
            echo "<div class='ueberschrift'>Database error!</div>";
            echo "<div class='fliesstext center_text'>Something must have gone wrong...</div>";
            echo "<div class='bild_mittig_groß'><img src='pic_collection/family_cooking.jpg' alt='Kochende Familie' style='max-width:1400px; padding: 100px 0px 400px'></div>";
            require("includes/footer_inc.php");
            die();
        }
        ?>
        </div>
    </div>
</body>
</html>