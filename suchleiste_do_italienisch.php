<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Suchleiste</title>
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
            window.location.href= str.substring(0, str.lastIndexOf("/"))+"/add_favorite.php?id="+row_id+"&backUrl=suchleiste_italienisch.php";
        }

        function deleteFromFavorites(row_id){
            console.log("Deleting from favorites "+row_id);
            let str=window.location.href;
            window.location.href= str.substring(0, str.lastIndexOf("/"))+"/delete_favorite.php?id="+row_id+"&backUrl=suchleiste_italienisch.php";
        }
    </script>

    <?php
    if (isset($_GET["show_modal"])){
        echo "<script type=\"text/javascript\">
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
        #ABFRAGE OB FORMULARE KORREKT GESENDET WURDEN
        if (!isset($_POST["search"])) {
            echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'> Hier ist wohl etwas schiefgelaufen....</div>";
            echo "<div class=' fliesstext center_text'>Zur Startseite kommst du <a href='index.php'>hier!</a></div>";
            echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
            require("includes/footer_inc.php");
            die();
        }
        #ANZAHL ERGEBNISSE#
        $search = $_POST["search"];
        $searchnew = "%$search%";
        $anzahl = $pdo->prepare("SELECT COUNT(*) FROM recipe, recipe_cuisine WHERE title LIKE :searchnew AND recipe_cuisine.cuisine_id='1' AND recipe.id=recipe_id ORDER BY RAND()");
        $anzahl->bindParam(":searchnew", $searchnew);
        if ($anzahl -> execute()){
            while($row=$anzahl->fetch()){
                if ($row["COUNT(*)"]==1){
                    echo "<div class='ueberschrift'>".$row["COUNT(*)"]. " Ergebnis wurde für "."\"$search\""." gefunden."."<br>"."</div>";
                }else{
                    echo "<div class='ueberschrift'>".$row["COUNT(*)"]. " Ergebnisse wurden für "."\"$search\""." gefunden."."<br>"."</div>";
                }}}
        ?>
        <div class="container_mittig">
            <div class="weite_suchleiste">
                <form action="suchleiste_do_italienisch.php" method="post" enctype="multipart/form-data">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 53px;margin-top: 1px;color: #FC7F16">italienisch</button>
                            <div class="dropdown-menu" style="border-radius: 0.3rem 0.3rem 1rem 1rem;">
                                <div class="container_klein">
                                    <div class="dropdown-menu-categories">
                                        <p class="kleintext center_text">Menüart</p>
                                        <a class="dropdown-item" href="suchleiste_beilage.php">Beilage</a>
                                        <a class="dropdown-item" href="suchleiste_dessert.php">Dessert</a>
                                        <a class="dropdown-item" href="suchleiste_dinner.php">Dinner</a>
                                        <a class="dropdown-item" href="suchleiste_frühstück.php">Frühstück</a>
                                        <a class="dropdown-item" href="suchleiste_getränke.php">Getränke</a>
                                        <a class="dropdown-item" href="suchleiste_hauptspeise.php">Hauptspeise</a>
                                        <a class="dropdown-item" href="suchleiste_salat.php">Salat</a>
                                        <a class="dropdown-item" href="suchleiste_suppe.php">Suppe</a>
                                        <a class="dropdown-item" href="suchleiste_vorspeise.php">Vorspeise</a>
                                    </div>
                                    <div class="dropdown-menu-categories">
                                        <p class="kleintext center_text">Ernährungsweise</p>
                                        <a class="dropdown-item" href="suchleiste_glutenfrei.php">glutenfrei</a>
                                        <a class="dropdown-item" href="suchleiste_histaminfrei.php">histaminfrei</a>
                                        <a class="dropdown-item" href="suchleiste_laktosefrei.php">laktosefrei</a>
                                        <a class="dropdown-item" href="suchleiste_lowcarb.php">low-carb</a>
                                        <a class="dropdown-item" href="suchleiste_vegan.php">vegan</a>
                                        <a class="dropdown-item" href="suchleiste_vegetarisch.php">vegetarisch</a>
                                    </div>
                                    <div class="dropdown-menu-categories">
                                        <p class="kleintext center_text">Küche(Region)</p>
                                        <a class="dropdown-item" href="suchleiste_britisch.php">britisch</a>
                                        <a class="dropdown-item" href="suchleiste_chinesisch.php">chinesisch</a>
                                        <a class="dropdown-item" href="suchleiste_deutsch.php">deutsch</a>
                                        <a class="dropdown-item" href="suchleiste_indisch.php">indisch</a>
                                        <a class="dropdown-item" href="suchleiste_italienisch.php">italienisch</a>
                                        <a class="dropdown-item" href="suchleiste_mexikanisch.php">mexikanisch</a>
                                        <a class="dropdown-item" href="suchleiste_vietnamesisch.php">vietnamesisch</a>
                                    </div>
                                    <div class="dropdown-menu-categories">
                                        <p class="kleintext center_text">Sonderkategorien</p>
                                        <a class="dropdown-item" href="suchleiste_babynahrung.php">Babynahrung</a>
                                        <a class="dropdown-item" href="suchleiste_camping.php">Camping</a>
                                        <a class="dropdown-item" href="suchleiste_fingerfood.php">Fingerfood</a>
                                        <a class="dropdown-item" href="suchleiste_kinder.php">Kinder</a>
                                        <a class="dropdown-item" href="suchleiste_studentenküche.php">Studentenküche</a>
                                        <br><br><br><br>
                                        <a class="dropdown-item" href="index.php">ohne Filter suchen ➤</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="search" id="search" class="form-control" aria-label="Search input with dropdown button" placeholder="Suche nach deinen Lieblingsrezepten..." style="height: 55px; border-color: transparent;box-shadow: none;">
                        <div class="input-group-append">
                            <input class="search_button" type="image" src="pic_collection/icons/icon_search.png" style="margin-top: 1px; alt="Submit" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--AUSGABE ERGEBNISSE-->
        <?php
        $statement=$pdo->prepare("SELECT recipe.id, title, description, level_name, timespan, filename, cuisine_id FROM recipe,level,timespan, picture, recipe_cuisine, cuisine WHERE recipe_cuisine.cuisine_id='1' AND title LIKE :searchnew AND recipe_cuisine.cuisine_id=cuisine.id AND recipe_cuisine.recipe_id=recipe.id  AND recipe.level_id=level.id AND recipe.timespan_id=timespan.id AND picture.recipe_id=recipe.id GROUP BY recipe.id ORDER BY recipe.id DESC");
        $statement->bindParam(":searchnew", $searchnew);
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
        #FOOTER#
        require("includes/footer_inc.php");
        ?>
    </div>
</div>
</body>
</html>
