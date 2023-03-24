<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout - Nosh Cuisine</title>
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
            if (!isset($_SESSION["user_id"])) {
                require("includes/header_inc.php");
                echo "<div class='ueberschrift'>Whoops!</div>";
                echo "<div class='fliesstext center_text'>You're already logged out.</div>";
                echo "<div class='bild_mittig_groß'><img src='pic_collection/couple_cooking.jpg' alt='Kochendes Pärchen' style='max-width:1400px; padding: 100px 0px 400px;'></div>";
                require("includes/footer_inc.php");
                die();

            }else{
                $username= $_SESSION['username'];
                session_destroy();
                require("includes/header_inc.php");
                echo "<div class='ueberschrift'>See you next time and bon appétit!</div>";
                echo "<div class='fliesstext center_text'>We hope to see you again soon, ".$username.".</div>";
                echo "<div class='bild_mittig_groß'><img src='pic_collection/couple_cooking.jpg' alt='Kochendes Pärchen' style='max-width:1400px; padding: 100px 0px 400px'></div>";
                require("includes/footer_inc.php");
            }
            ?>
        </div>
    </div>
</body>
</html>
