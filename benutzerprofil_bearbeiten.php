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
    <div class="main_wrapper">
    <!--HEADER-->
    <?php
    require("includes/header_inc.php");
    ?>
        <div class="ueberschrift">Profil bearbeiten</div>
        <?php
        if(!isset($_SESSION["user_id"])) {
            echo "<div class='ueberschrift'>Ups!</div><div class='fliesstext center_text'> Du musst dich zuerst einloggen.</div>";
            echo "<div class=' fliesstext center_text'>Hier gehts zum <a href='login.php'>Login</a>!</div>";
            echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
            require("includes/footer_inc.php");
            die();
        }
        $statement = $pdo->prepare("SELECT * FROM user WHERE id = :id");
        $statement->bindParam(":id", $_SESSION["user_id"]);
        if($statement->execute($_GET["id"])){
            if($row=$statement->fetch()){
                ?>
            <div class="center_text">
                <div class="positioning">
                    <?php
                    if ($row["profilepicture"]!=NULL){
                        echo "<br>". "<div class='deletebutton'><form action='delete_profilepicture_do.php' method='post' enctype='multipart/form-data'><button class='subscribe-btn' type='submit'>Profilbild löschen</button></form></div><br>";
                    }
                    ?>
                    <form action="benutzerprofil_bearbeiten_do.php?id=<?php echo $row["id"];?>" method="post" enctype="multipart/form-data">
                        <?php
                        $statement = $pdo-> prepare ("SELECT * FROM user WHERE id = :id"); //nur eingeloggter User
                        $statement->bindParam(":id", $_SESSION["user_id"]);
                        if($statement -> execute($_GET["id"])) {
                            if($row= $statement->fetch()) {
                                if(($row["profilepicture"])== NULL){
                                    echo  "<img src=pic_collection/user_nopicture.png>"."<br><br>";
                                    echo "<div class='mb-2'><label for='formFileSm' class='form-label'><div class='kleintext'>Profilbild hochladen</div></label><input class='form-control form-control-sm' id='formFileSm' type='file' name='file' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;box-shadow: none'></div>";
                                }else{
                                    echo "<img class='rund_xl' src='files/" . $row["profilepicture"] . "'alt='Profilbild von" . $row["username"] . "'>" ."<br><br>";
                                    echo "<div class='mb-2'><label for='formFileSm' class='form-label'><div class='kleintext'> Neues Profilbild hochladen</div></label><input class='form-control form-control-sm' id='formFileSm' type='file' name='file' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;border-color: transparent;box-shadow: none'></div>";

                                }
                                echo "</div>";
                                $username=$row["username"];
                                $mail=$row["mail"];
                                $membership_since=$row["membership_since"];
                                echo "<div class='form-outline mb-2'><label for='username' class='form-label'><div class='kleintext'>Benutzername</div></label><input type='text' name='username' placeholder='Dein Benutzername' value='$username' class='form-control form-control-lg' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;text-align: center;border-color: transparent;box-shadow: none'></div>";
                                echo "<div class='form-outline mb-2'><label for='password' class='form-label'><div class='kleintext'>Passwort</div></label><input type='text' name='password' placeholder='Dein Passwort' value='********' class='form-control form-control-lg' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;text-align: center;border-color: transparent;box-shadow: none'></div>"; #so viele Sternchen wie PW zeichen hat? variable vor hash? die dann verwenden?
                                echo "<div class='form-outline mb-2'><label for='mail' class='form-label'><div class='kleintext'>E-Mail-Adresse</div></label><input type='text' name='mail' placeholder='Deine E-Mail' value='$mail' class='form-control form-control-lg' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;text-align: center;border-color: transparent;box-shadow: none'></div>";
                                echo "<div class='form-outline mb-2'><label for='membership_since' class='form-label'><div class='kleintext'>Mitglied seit</div></label><input type='text' value='$membership_since' readonly='readonly' class='form-control form-control-lg' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;text-align: center;background-color: #E8E7E1;border-color: lightgrey;box-shadow: none'></div>";

                            }}
                        ?>
                        <input type="image" src="pic_collection/icons/icon_aenderungspeichern.png" border="0" alt="Submit" />
                    </form>
                </div>
            </div>
                <?php
            }else{
                echo("Formular-Fehler");
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