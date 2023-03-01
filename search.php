<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Rezepte Stöbern - Nosh Cuisine</title>
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
                window.location.href= str.substring(0, str.lastIndexOf("/"))+"/add_favorite.php?id="+row_id+"&backUrl=search.php";
            }

            function deleteFromFavorites(row_id){
                console.log("Deleting from favorites "+row_id);
                let str=window.location.href;
                window.location.href= str.substring(0, str.lastIndexOf("/"))+"/delete_favorite.php?id="+row_id+"&backUrl=search.php";
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
        <!--Cookie setzen wo ich mich auf der Seite befinde (scrollTop = Window-Scroll-Position, wird ausgeführt sobald das Dokument geladen hat:
        Gibt es diesen cookie? War ich schon mal geladen? Wenn nicht, also null, dann mach nix, wenn ja dann geh zurück zu vorheriger Scroll-Position)-->
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
        <?php
        require("includes/header_inc.php");

        echo "<div class='ueberschrift'>Inspiration gefällig?</div> <div class='fliesstext center_text'>Hier findest du alle Nosh Cuisine Rezepte.</br>";

                if(isset($_SESSION["user_id"])){
                    echo"<div class='fliesstext center_text'>Nutze den Herzbutton, um Rezepte zu deiner persönlichen Sammlung hinzuzufügen!<br><br></div></div>";
                }else{
                    echo"<div class='fliesstext center_text'>Du willst Rezepte oder deine eigene Sammlung erstellen?<br>Dann <a href='login.php'>logge dich ein</a> oder <a href='register.php'>registriere</a> dich bei Nosh Cuisine.<br><br></div></div>";
                }
                echo "<div class='zentrieren_search'>";
                require("suchleiste_inc.php");
                echo "</div>";
                $statement3=$pdo->prepare("SELECT COUNT(*) FROM recipe,level,timespan WHERE recipe.level_id=level.id AND recipe.timespan_id=timespan.id");
                if($statement3->execute()){
                    while($row=$statement3->fetch()) {
                        if ($row["COUNT(*)"]==1){
                            echo "<div class='kleintext center_text'>".$row["COUNT(*)"]. " Ergebnis</div>";
                        }else{
                            echo "<div class='kleintext center_text'>".$row["COUNT(*)"]. " Ergebnisse</div>";
                        }
                    }
                }

                $statement=$pdo->prepare("SELECT recipe.id, title, description, level_name, timespan, filename from recipe,level,timespan, picture WHERE recipe.level_id=level.id AND recipe.timespan_id=timespan.id AND picture.recipe_id=recipe.id GROUP BY recipe.id ORDER BY recipe.id DESC");
                if($statement->execute()){
                    echo "<div class='container_cards'>";
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
                            }#stars while-schleife
                        }# if ($stars-->execute())
                    }
                }else{
                    die("Datenbank-Fehler");
                }
                require("includes/footer_inc.php");
                ?>
            </div>
        </div>
    </body>
    </html>
