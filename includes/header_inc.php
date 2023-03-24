<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="index.php"><img src="pic_collection/logo/logo.png" alt="Nosh Cuisine" height="100px" style="margin-top: 40px"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="search.php">Browse Recipes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="ueberuns.php">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="kontakt.php">Contact</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="impressum.php">Impressum</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <?php
            # Unterschied bei Eingeloggt / Ausgeloggt
            if (!isset($_SESSION["user_id"])){
                echo "<element onclick='checklogin();'>"."<a href='index.php'><img src='pic_collection/icons/icon_bearbeiten_offline.png' alt='Rezept erstellen' height='45px' width='45px'></a>"."</element>";
                echo "<a class=navbar_item href='login.php' style='color: #FC7F16'>"."Sign in"."</a>";
                echo "<a class=navbar_item href='register.php' style='color: #FC7F16'>"."Register"."</a>";
            }else{
                echo "<a class='navbar_bild' href='create_recipe.php'><img class='rund_xs' src='pic_collection/icons/icon_bearbeiten.png' alt='Rezept erstellen' height='45px' width='45px'></a>";

                $statement = $pdo-> prepare ("SELECT profilepicture FROM user WHERE id= :id");
                $statement->bindParam(":id", $_SESSION["user_id"]);
                if($statement -> execute()) {
                    if($row= $statement->fetch()) {
                        if (($row["profilepicture"])== NULL or ""){
                            echo "<a class='navbar_bild' href='benutzerprofil.php'><img class='rund_xs' src='pic_collection/icons/icon_user.png' alt='Benutzer:in' height='45px' width='45px'></a>"; # allgemeines Bild, wenn kein Profilbild
                        }else{
                            echo "<a class='navbar_bild' href='benutzerprofil.php'>"."<img class='rund_xs' src='files/" . $row["profilepicture"] . "'alt='Profilbild von" . $row["username"] . "'>" ."</a>"; # persönliches Profilbild
                        }
                    }
                }
                echo "<a class='navbar_bild' href='logout.php'>"."<img class='rund_xs'src='pic_collection/icons/icon_logout.png' alt='Logout' height='30px' width='30px'>"."</a>";
            }
            ?>
        </form>
    </div>
</nav>





