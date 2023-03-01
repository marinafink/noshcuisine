<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mein Profil - Nosh Cuisine</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="pic_collection/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function addToFavorites(row_id){
            console.log("Adding to favorites "+row_id);
            let str=window.location.href;
            window.location.href= str.substring(0, str.lastIndexOf("/"))+"/add_favorite.php?id="+row_id+"&backUrl=benutzerprofil.php";
        }
        function deleteFromFavorites(row_id){
            console.log("Deleting from favorites "+row_id);
            let str=window.location.href;
            window.location.href= str.substring(0, str.lastIndexOf("/"))+"/delete_favorite.php?id="+row_id+"&backUrl=benutzerprofil.php";
        }
        </script>
    <?php
    if (isset($_GET["show_modal"])){
        echo "
                <script type=\"text/javascript\">
                    $(document).ready(function () {
                        $('#myModal').modal('toggle');
                    });
                </script>
            ";
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            if (localStorage.getItem("nosh-quote-scroll") != null) {
                $(window).scrollTop(localStorage.getItem("nosh-quote-scroll"));
            }
            $(window).on("scroll", function() {
                localStorage.setItem("nosh-quote-scroll", $(window).scrollTop());
            });
          });
        </script>
</head>
<body>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?php
                if (isset($_GET["show_modal"])){
                    if ($_GET["show_modal"]=="success_add"){
                        echo "<div class='modal-header'><h5 class='modal-title' id='exampleModalLabel'>Du hast ein Rezept zu deinen Favoriten hinzugefügt!</h5></div><div class='modal-body'>Du findest deine Favoritenliste in deinem Profil.<br> Viel Spaß beim Nachkochen!</div>";
                    }elseif($_GET["show_modal"]=="success_delete"){
                        echo "<div class='modal-header'><h5 class='modal-title' id='exampleModalLabel'>Du hast ein Rezept aus deinen Favoriten entfernt!</h5></div><div class='modal-body'>Du findest deine Favoritenliste in deinem Profil.</div>";
                    }else{
                        echo "<div class='modal-header'><h5 class='modal-title' id='exampleModalLabel'>Das hat nicht geklappt!</h5><div class='modal-body'>Versuche es später nochmal!</div>";
                    }
                }
                ?>
                <div class="modal-footer">
                    <button type="button" onclick="$('#myModal').modal('hide');window.history.replaceState({}, '', window.location.pathname);" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
                </div>
            </div>
        </div>
    </div>
    <div class="all">
        <div class="main_wrapper">
            <!--HEADER-->
            <?php
            require("includes/header_inc.php");
            ?>
        <div class="ueberschrift">Dein Profil</div>
            <div class="center_text">
            <!--ÜBERPRÜFUNG OB EINGELOGGT-->
            <?php
            if(!isset($_SESSION["user_id"])) {
                echo "<div class='ueberschrift'>Ups!</div><div class='fliesstext center_text'> Du musst dich zuerst einloggen.</div>";
                echo "<div class=' fliesstext center_text'>Hier gehts zum <a href='login.php'>Login</a>!</div>";
                echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
                require("includes/footer_inc.php");
                die();
            }
            $statement = $pdo-> prepare ("SELECT * FROM user WHERE id= :id"); #Daten von eingeloggtem User:in
            $statement->bindParam(":id", $_SESSION["user_id"]);
            if($statement -> execute()) {
                if($row= $statement->fetch()) {
                    if (($row["profilepicture"])== NULL or ""){
                        echo "<img src=pic_collection/user_nopicture.png>"; #Placeholderbild
                    }else{
                        echo "<img class='rund_xl' src='files/" . $row["profilepicture"] . "'alt='Profilbild von" . $row["username"] . "'>" ."<br><br>";
                    }
                    echo "<div class='unterüberschrift' style='letter-spacing: 1px'><b>".$row["username"]."</b></div>"."<br>";
                    echo "<div class='form-outline mb-2'><label for='password' class='form-label'><div class='kleintext'>Passwort</div></label><input type='text' name='password' value='********' readonly='readonly' class='form-control form-control-lg' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;text-align: center;background-color: #E8E7E1;border-color: lightgrey;'></div>";
                    echo "<div class='form-outline mb-2'><label for='mail' class='form-label'><div class='kleintext'>E-Mail-Adresse</div></label><input type='text' name='mail' value='$row[mail]' readonly='readonly' class='form-control form-control-lg' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;text-align: center;background-color: #E8E7E1;border-color: lightgrey;'></div>";
                    echo "<div class='form-outline mb-2'><label for='membership_since' class='form-label'><div class='kleintext'>Mitglied seit</div></label><input type='text' name='membership_since' value='$row[membership_since]' readonly='readonly' class='form-control form-control-lg' style='border-radius: 5rem;font-size: 1rem;max-width: 30%;text-align: center;background-color: #E8E7E1;border-color: lightgrey;'></div>";
                }
            }
            ?>
                <form action="benutzerprofil_bearbeiten.php?id=<?php echo $row["user_id"];?>" method="post" enctype="multipart/form-data">
                    <a href="benutzerprofil_bearbeiten.php">
                        <img src="pic_collection/icons/icon_benutzerprofilbearbeiten.png" alt="Profil bearbeiten">
                    </a>
                </form>
            </div>
            <div class="container_xl">
                <div class="unterüberschrift">Deine Favoriten</div>
                <!--ANZAHL FAVORITEN-->
                <?php
                $anzahl = $pdo->prepare("SELECT COUNT(*) FROM favorites,recipe WHERE user_id= :user_id AND recipe_id=id");
                $anzahl->bindParam(":user_id", $_SESSION["user_id"]);

                if ($anzahl -> execute()){
                    while($row=$anzahl->fetch()){
                        if ($row["COUNT(*)"]==1){
                            echo "<div class='kleintext'>"."Du hast ".$row["COUNT(*)"]. " Favorit:"."</div>"."<br>";
                        }elseif ($row["COUNT(*)"]>1){
                            echo "<div class='kleintext'>"."Du hast ".$row["COUNT(*)"]. " Favoriten:"."</div>"."<br>";
                        }else{
                            #Placeholder Rezeptcard, wenn keine Favoriten
                            echo "<div class='cards'>";
                            echo "<a href='search.php'>";
                            echo "<img class='cards_img' src='files/placeholder_recipepicture.jpeg' alt='Platzhalter Rezeptbild'>";
                            echo "</a>";
                            echo "<div class='cards_body'>";
                            echo "<h5 class='cards_title'>Du hast noch keine Favoriten</h5>";
                            echo "Stöbere in unseren Rezepten und finde deinen Favorit!";
                            echo "</div>";
                            echo "<div class='pill_container'>";
                            echo "<a href='search.php'><button class='subscribe-btn' type='submit'>Jetzt stöbern!</button></a>";
                            echo "</div><br><br>";
                            echo "</div>";
                            }
                        }
                    }
                ?>
                <!--FAVORITEN ANZEIGEN + ENTFERNEN -->
                <?php
                $statement=$pdo->prepare("SELECT recipe.id, title, description, instruction, level_name, timespan, filename, favorites.recipe_id FROM recipe,level,timespan, picture, favorites WHERE favorites.user_id=:user_id AND favorites.recipe_id=recipe.id AND recipe.level_id=level.id AND recipe.timespan_id=timespan.id AND picture.recipe_id=recipe.id GROUP BY recipe.id ORDER BY RAND ()");
                $statement->bindParam(":user_id", $_SESSION["user_id"]);
                if($statement->execute()){
                    echo "<div class='container_cards_left'>";
                    while($row=$statement->fetch()) {
                        $recipe_id = $row["id"];
                        $user_id = $_SESSION["user_id"];
                        $title = $row["title"];
                        $description = $row["description"];
                        $level_name = $row["level_name"];
                        $timespan = $row["timespan"];
                        $stars = $pdo->prepare("SELECT AVG(stars), COUNT(*) FROM review, recipe WHERE recipe_id=:recipeID AND recipe.id=recipe_id");
                        $stars->bindParam(":recipeID", $recipe_id);
                        if ($stars->execute()) {
                            while ($row_stars = $stars->fetch()) {
                                $review_amount =$row_stars["COUNT(*)"];
                                $rounded_stars = round($row_stars["AVG(stars)"],1);
                                if (isset($_SESSION["user_id"])) {
                                    $statement2 = $pdo->prepare('SELECT * from favorites, recipe WHERE recipe_id=recipe.id AND recipe_id=:recipe_id AND user_id=:user_id ORDER BY recipe_id');
                                    $statement2->bindParam(":recipe_id", $recipe_id);
                                    $statement2->bindParam(":user_id", $user_id);
                                    if ($statement2->execute()) {
                                        if ($statement2->fetch()) {
                                            echo "<div class='cards'><a href='recipe_detailview.php?id=" . $recipe_id . "'><img class='cards_img' src='files/" . $row["filename"] . "'alt='Rezeptbild'></a><div class='cards_body'><h5 class='cards_title'>" . $title . "</h5><p class='cards_text'>" . $description . "</p><div class='pill_container'><div class='pill'>" . $level_name . "</div><div class='pill'>" . $timespan . "</div><div class='pill'>".$rounded_stars."★"."(".$review_amount.")</div></div><a class='favorite' onclick='deleteFromFavorites(" . $row['id'] . ")' style='float: right;'><img src='pic_collection/icons/icon_herz.png' alt='Rezept entfavorisieren' height='30px' width='30px'></a></div></div><br>";
                                        } else {
                                            echo "<div class='cards'><a href='recipe_detailview.php?id=" . $recipe_id . "'><img class='cards_img' src='files/" . $row["filename"] . "'alt='Rezeptbild'></a><div class='cards_body'><h5 class='cards_title'>" . $row["title"] . "</h5><p class='cards_text'>" . $row["description"] . "</p><div class='pill_container'><div class='pill'>" . $row["level_name"] . "</div><div class='pill'>" . $row["timespan"] . "</div><div class='pill'>".$rounded_stars."★"."(".$review_amount.")</div></div><a class='favorite' style='float: right;' onclick='addToFavorites(" . $row['id'] . ")'><img src='pic_collection/icons/icon_herz_leer.png' alt='Rezept favorisieren' height='30px' width='30px'></a></div></div><br>";
                                        }
                                    }
                                } else {
                                    echo "<div class='cards'><a href='recipe_detailview.php?id=" . $recipe_id . "'><img class='cards_img' src='files/" . $row["filename"] . "'alt='Rezeptbild'></a><div class='cards_body'><h5 class='cards_title'>" . $row["title"] . "</h5><p class='cards_text'>" . $row["description"] . "</p><div class='pill_container'><div class='pill'>" . $row["level_name"] . "</div><div class='pill'>" . $row["timespan"] . "</div><div class='pill'>".$rounded_stars."★"."(".$review_amount.")</div></div><a class='favorite' style='float: right;' onclick='addToFavorites(" . $row['id'] . ")'><img src='pic_collection/icons/icon_herz_leer.png' alt='Rezept favorisieren' height='30px' width='30px'></a></div></div><br>";
                                }
                            }
                        }
                    }
                }else{
                    die("Datenbank-Fehler");
                }
                echo "</div>";
                ?>
            </div>
            <!--ANZAHL ERSTELLTE REZEPTE-->
            <div class="container_xl">
                <div class="unterüberschrift">Deine Rezepte</div>
                <?php
                $anzahl = $pdo->prepare("SELECT COUNT(*) FROM recipe WHERE author= :author ");
                $anzahl->bindParam(":author", $_SESSION["username"]);

                if ($anzahl -> execute()){
                    while($row=$anzahl->fetch()){
                        if ($row["COUNT(*)"]==1){
                            echo "<div class='kleintext'>"."Du hast ".$row["COUNT(*)"]. " Rezept erstellt:"."</div>"."<br>";
                        }elseif ($row["COUNT(*)"]>1){
                            echo "<div class='kleintext'>"."Du hast ".$row["COUNT(*)"]. " Rezepte erstellt:"."</div>"."<br>";
                        }else{
                            #Placeholder Rezeptcard, wenn keine erstellten Rezepte
                            echo "<div class='cards'>";
                            echo "<a href='search.php'>";
                            echo "<img class='cards_img' src='files/placeholder_recipepicture.jpeg' alt='Platzhalter Rezeptbild'>";
                            echo "</a>";
                            echo "<div class='cards_body'>";
                            echo "<h5 class='cards_title'>Du hast noch kein Rezept erstellt</h5>";
                            echo "Erstelle jetzt dein erstes eigenes Rezept!";
                            echo "</div>";
                            echo "<div class='pill_container'>";
                            echo "<a href='create_recipe.php'><button class='subscribe-btn' type='submit'>Rezept erstellen</button></a>";
                            echo "</div><br><br>";
                            echo "</div>";
                        }
                    }
                }
                ?>
                <!--ERSTELLTE REZEPTE ANZEIGEN-->
                <?php
                $statement=$pdo->prepare("SELECT recipe.id, title, description, instruction, level_name, timespan, author, filename FROM recipe,level,timespan, picture WHERE author= :author AND recipe.level_id=level.id AND recipe.timespan_id=timespan.id AND picture.recipe_id=recipe.id GROUP BY recipe.id ORDER BY RAND ()");
                $statement->bindParam(":author", $_SESSION["username"]);
                if($statement->execute()){
                    echo "<div class='container_cards_left'>";
                    while($row=$statement->fetch()) {
                        $recipe_id = $row["id"];
                        $user_id = $_SESSION["user_id"];
                        $title = $row["title"];
                        $description = $row["description"];
                        $level_name = $row["level_name"];
                        $timespan = $row["timespan"];
                        $stars = $pdo->prepare("SELECT AVG(stars), COUNT(*) FROM review, recipe WHERE recipe_id=:recipeID AND recipe.id=recipe_id");
                        $stars->bindParam(":recipeID", $recipe_id);
                        if ($stars->execute()) {
                            while ($row_stars = $stars->fetch()) {
                                $review_amount =$row_stars["COUNT(*)"];
                                $rounded_stars = round($row_stars["AVG(stars)"],1);
                                if (isset($_SESSION["user_id"])) {
                                    $statement2 = $pdo->prepare('SELECT * from favorites, recipe WHERE recipe_id=recipe.id AND recipe_id=:recipe_id AND user_id=:user_id ORDER BY recipe_id');
                                    $statement2->bindParam(":recipe_id", $recipe_id);
                                    $statement2->bindParam(":user_id", $user_id);
                                    if ($statement2->execute()) {
                                        if ($statement2->fetch()) {
                                            echo "<div class='cards'><a href='recipe_detailview.php?id=" . $recipe_id . "'><img class='cards_img' src='files/" . $row["filename"] . "'alt='Rezeptbild'></a><div class='cards_body'><h5 class='cards_title'>" . $title . "</h5><p class='cards_text'>" . $description . "</p><div class='pill_container'><div class='pill'>" . $level_name . "</div><div class='pill'>" . $timespan . "</div><div class='pill'>".$rounded_stars."★"."(".$review_amount.")</div></div><a class='favorite' onclick='deleteFromFavorites(" . $row['id'] . ")' style='float: right;'><img src='pic_collection/icons/icon_herz.png' alt='Rezept entfavorisieren' height='30px' width='30px'></a></div></div><br>";
                                        } else {
                                            echo "<div class='cards'><a href='recipe_detailview.php?id=" . $recipe_id . "'><img class='cards_img' src='files/" . $row["filename"] . "'alt='Rezeptbild'></a><div class='cards_body'><h5 class='cards_title'>" . $row["title"] . "</h5><p class='cards_text'>" . $row["description"] . "</p><div class='pill_container'><div class='pill'>" . $row["level_name"] . "</div><div class='pill'>" . $row["timespan"] . "</div><div class='pill'>".$rounded_stars."★"."(".$review_amount.")</div></div><a class='favorite' style='float: right;' onclick='addToFavorites(" . $row['id'] . ")'><img src='pic_collection/icons/icon_herz_leer.png' alt='Rezept favorisieren' height='30px' width='30px'></a></div></div><br>";
                                        }
                                    }
                                } else {
                                    echo "<div class='cards'><a href='recipe_detailview.php?id=" . $recipe_id . "'><img class='cards_img' src='files/" . $row["filename"] . "'alt='Rezeptbild'></a><div class='cards_body'><h5 class='cards_title'>" . $row["title"] . "</h5><p class='cards_text'>" . $row["description"] . "</p><div class='pill_container'><div class='pill'>" . $row["level_name"] . "</div><div class='pill'>" . $row["timespan"] . "</div><div class='pill'>".$rounded_stars."★"."(".$review_amount.")</div></div><a class='favorite' style='float: right;' onclick='addToFavorites(" . $row['id'] . ")'><img src='pic_collection/icons/icon_herz_leer.png' alt='Rezept favorisieren' height='30px' width='30px'></a></div></div><br>";
                                }
                            }
                        }
                    }
                }else{
                    die("Datenbank-Fehler");
                }
                echo "</div>";
                ?>
            </div>
            <!--FOOTER-->
            <?php
            require("includes/footer_inc.php");
            ?>
        </div>
    </div>
</body>
</html>
