<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favorisieren - Nosh Cuisine</title>
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
        if (!isset($_SESSION["user_id"])) {
            echo "<div class='ueberschrift'>Upsala!</div>";
            echo "<div class='fliesstext center_text'>Du bist nicht eingeloggt! Logge dich <a style='color:#FC7F16;' href='login.php'>hier</a> ein, um Rezepte zu deinen Favoriten hinzuzufügen und zu entfernen.</div>";
            echo "<div class='bild_mittig_groß'><img src='pic_collection/cutting.jpg' alt='Zubereitung von Essen' style='max-width:1400px'></div>";
            require("includes/footer_inc.php");
            die();
        }
        $statement = $pdo->prepare("INSERT INTO favorites(user_id, recipe_id) VALUES (?,?) ");
        if (isset($_GET["id"])){
            if ($statement->execute(array(htmlspecialchars($_SESSION["user_id"]), htmlspecialchars($_GET["id"])))) {
                echo "<script>
                        console.log('success');
                        let str=window.location.href;
                        window.location.href= str.substring(0, str.lastIndexOf('/'))+'/".$_GET["backUrl"]."?show_modal=success_add';
                        console.log(window.location.href);
                      </script>";
            } else {
                echo "<script>
                        console.log('failure');
                        let str=window.location.href;
                        window.location.href= str.substring(0, str.lastIndexOf('/'))+'/".$_GET["backUrl"]."?show_modal=failure_add';
                        console.log(window.location.href);
                      </script>";
                    #echo $statement->errorInfo()[2];
                    #echo $statement->queryString;
                    die("Datenbank-Fehler");
            }
        }else{
            echo "<div class='ueberschrift'>Upsala!</div>";
            echo "<div class='fliesstext center_text'>Es wurde kein Rezept ausgewählt.</div>";
            echo "<div class='bild_mittig_groß'><img src='pic_collection/cutting.jpg' alt='Zubereitung von Essen' style='max-width:1400px'></div>";
            require("includes/footer_inc.php");
            die();
        }
        ?>
</div>
</body>
</html>