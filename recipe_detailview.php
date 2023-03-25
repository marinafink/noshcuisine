<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rezept - Nosh Cuisine</title>
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
                window.location.href= str.substring(0, str.lastIndexOf("/"))+"/add_favorite_detail.php?id="+row_id+"&backUrl=recipe_detailview.php?id="+row_id;
            }

        function deleteFromFavorites(row_id){
            console.log("Deleting from favorites "+row_id);
            let str=window.location.href;
            window.location.href= str.substring(0, str.lastIndexOf("/"))+"/delete_favorite_detail.php?id="+row_id+"&backUrl=recipe_detailview.php?id="+row_id;
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

    #id muss übergeben sein
    if(!isset($_GET["id"])){
        echo "<div class='ueberschrift'>Whoops!</div>";
        echo "<div class='fliesstext center_text'>Something went wrong here.</div>";
        echo "<div class='bild_mittig_groß'><img src='pic_collection/jummy.jpg' alt='Zubereitung von Essen' style='max-width:1400px'></div>";
        require("includes/footer_inc.php");
        die();
    }

    #Statement + Variablendefinition für die recipe-Ausgaben der Detailseite
    $statement = $pdo->prepare("SELECT * FROM recipe, level, timespan WHERE recipe.id = ? AND recipe.level_id = level.id AND recipe.timespan_id = timespan.id");
    if($statement->execute(array($_GET["id"]))){
        while($row=$statement->fetch()) {
            $title = $row["title"];
            $description = $row["description"];
            $instruction = nl2br($row["instruction"]); #nl2br() stellt die urspr. Zeilenumbrüche usw. wieder her
            $author = $row["author"];
            $calories = $row["calories"]." kcal";
            $time_posted = $row["time_posted"];
            $cooking_hours = $row["cooking_time_hours"];
            $cooking_time_hours = $row["cooking_time_hours"]."h ";
            $cooking_time_mins = $row["cooking_time_mins"]."min";
            $amount_servings = $row["amount_servings"];
            $level = $row["level_name"];
            $timespan = $row["timespan"];
        }
    }else{
        die("Datenbank-Fehler1");
    }

    $statement6 = $pdo->prepare("SELECT profilepicture FROM user, recipe WHERE recipe.id = ? AND recipe.author = user.username");
    if($statement6->execute(array($_GET["id"]))){
        while($row=$statement6->fetch()) {
            if($row["profilepicture"] == NULL){
                $profilepicture = "user_nopicture.png";
            }else{
                $profilepicture = $row["profilepicture"];
            }

        }
    }else{
        die("Datenbank-Fehler6");
    }

    #Durchschnitt, Menge der Sternebewertungen berechnen
    $statement4 = $pdo->prepare("SELECT AVG(stars), COUNT(*) FROM review, recipe WHERE recipe.id=? AND review.recipe_id=recipe.id");
    if($statement4->execute(array($_GET["id"]))){
        while($row=$statement4->fetch()) {
            $avg_stars_precise = round($row["AVG(stars)"],1);
            if($avg_stars_precise >=0 and $avg_stars_precise < 0.25){
                $avg_stars = "icon_stern_leer.png";
            }elseif($avg_stars_precise >=0.25 and $avg_stars_precise < 0.75){
                    $avg_stars = "icon_stern_halb.png";
            }elseif($avg_stars_precise >=0.75 and $avg_stars_precise < 1.25){
                $avg_stars = "icon_stern_eins.png";
            }elseif($avg_stars_precise >=1.25 and $avg_stars_precise < 1.75){
                $avg_stars = "icon_stern_einshalb.png";
            }elseif($avg_stars_precise >=1.75 and $avg_stars_precise < 2.25){
                $avg_stars = "icon_stern_zwei.png";
            }elseif($avg_stars_precise >=2.25 and $avg_stars_precise < 2.75){
                $avg_stars = "icon_stern_zweihalb.png";
            }elseif($avg_stars_precise >=2.75 and $avg_stars_precise < 3.25){
                $avg_stars = "icon_stern_drei.png";
            }elseif($avg_stars_precise >=3.25 and $avg_stars_precise < 3.75){
                $avg_stars = "icon_stern_dreihalb.png";
            }elseif($avg_stars_precise >=3.75 and $avg_stars_precise < 4.25){
                $avg_stars = "icon_stern_vier.png";
            }elseif($avg_stars_precise >=4.25 and $avg_stars_precise < 4.75){
                $avg_stars = "icon_stern_vierhalb.png";
            }elseif($avg_stars_precise >=4.75 and $avg_stars_precise <= 5){
                $avg_stars = "icon_stern_fünf.png";
            }
            $review_amount =$row["COUNT(*)"];
        }
    }else{
        die("Datenbank-Fehler4");
    }
    #zählen, wie viele Bilder eingetragen sind für die Slideshow
    $statement5 = $pdo->prepare("SELECT COUNT(picture.filename) FROM picture, recipe WHERE recipe.id = ? AND picture.recipe_id = recipe.id ");
    if($statement5->execute(array($_GET["id"]))){
        while($row=$statement5->fetch()) {
            $anzahl_bilder = $row["COUNT(picture.filename)"];
        }
    }else{
        die("Datenbank-Fehler5");
    }

    ?>

    <div class="recipe_title"><?php  echo $title; ?></div>
    <div class="container_detailview_top">
        <?php
        #Slideshow für Rezeptbilder
        $statement2 = $pdo->prepare("SELECT picture.filename, picture.user_id, user.username FROM picture, recipe, user WHERE recipe.id = ? AND picture.recipe_id = recipe.id AND picture.user_id = user.id");
        echo "<div class='slideshow-container'>";
        if($statement2->execute(array($_GET["id"]))){
            $i=1;
            while($row=$statement2->fetch()) { #Bild für Bild kommt in die Slides
                echo "<div class='slider_slides fade'>"."<div class='numbertext'>".$i."/".$anzahl_bilder."</div>"."<img class='slidesimg' src='files/".$row["filename"]."' alt='Bild eines Rezepts'>"."<div class='textslider'>von ".$row["username"]."</div></div>";
                $i+=1;
            }
        }else{
            die("Datenbank-Fehler2");
        }
        echo "<div class='prev' onclick='addSlide(-1)'><img src='pic_collection/icons/icon_zurück.png' alt='back'/></div>"; #buttons für vor und zurück
        echo "<div class='next' onclick='addSlide(1)'><img src='pic_collection/icons/icon_weiter.png' alt='next'/></div>";
        echo "</div>"
        ?>

        <script>
            let slideIndex = 1;
            showSlides(slideIndex);

            //vor und zurück
            function addSlide(n) {
                showSlides(slideIndex += n);
            }

            function showSlides(n) {
                let i;
                let slides = document.getElementsByClassName("slider_slides");
                if (n > slides.length) {slideIndex = 1}
                if (n < 1) {slideIndex = slides.length}
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                slides[slideIndex-1].style.display = "block";
                slides[slideIndex-1].style.opacity = "1";
            }
        </script>

        <div class="container_detailview_top_right">
            <?php
            echo "<div class='container_profiledata'>";
            echo "<img class='profilepicture_small' src='files/" . $profilepicture. "'alt='Profilbild von " . $author . "'>"."<br>";
            echo "<div class='author_date'>"."<span>".$author."</span>";
            echo $time_posted."</div></div>";
            if($cooking_hours > 0){
                echo "<div class='cooking_data'><span>Arbeitszeit: </span>".$cooking_time_hours.$cooking_time_mins."<br>";
            }else{
                echo "<div class='cooking_data'><span>Arbeitszeit: </span>".$cooking_time_mins."<br>";
            }
            echo "<span>Level: </span>".$level."<br>";
            echo "<span>Kalorien (pro Portion): </span>".$calories."<br>";
            if($review_amount == 1){
                echo "<img class='commentstars' src='pic_collection/icons/".$avg_stars."'> (".$review_amount." Bewertung)<br></div>";
            }else{
                echo "<img class='commentstars' src='pic_collection/icons/".$avg_stars."'> (".$review_amount." Bewertungen)<br></div>";
            }

            ?>

            <div class="download_save_rec">
                    <!-- hier haben wir uns an der API probiert...
                    <div class='favorite_pill'>
                        <a href="API/marinasrezepte.pdf" download="rezept.jpg"><img src="pic_collection/icons/icon_download.png" alt="download" height='30px' width='auto'></a>
                    </div>
                    -->
                    <?php
                    if (isset($_SESSION["user_id"])) {
                        $statement_fav = $pdo->prepare('SELECT * from favorites, recipe WHERE recipe_id=recipe.id AND recipe_id=:recipe_id AND user_id=:user_id ORDER BY recipe_id');
                        $statement_fav->bindParam(":recipe_id", $_GET["id"]);
                        $statement_fav->bindParam(":user_id", $_SESSION["user_id"]);
                        if ($statement_fav->execute()) {
                            if ($statement_fav->fetch()) {
                                echo "<div class='favorite_pill'><a class='favorite' style='position: static' onclick='deleteFromFavorites(" . $_GET["id"] . ")' style='float: right; margin-left: 0%;'><img src='pic_collection/icons/icon_herz.png' alt='Rezept entfavorisieren' height='30px' width='30px'></a></div><br>";
                            } else {
                                echo "<div class='favorite_pill'><a class='favorite' style='float: right; margin-left: 0%; position: static' onclick='addToFavorites(" . $_GET["id"] . ")'><img src='pic_collection/icons/icon_herz_leer.png' alt='Rezept favorisieren' height='30px' width='30px'></a></div><br>";
                            }
                        }
                    } else {
                        echo "<div class='favorite_pill'><a class='favorite' style='float: right; position: static' onclick='addToFavorites(" . $_GET["id"] . ")'><img src='pic_collection/icons/icon_herz_leer.png' alt='Rezept favorisieren' height='30px' width='30px'></a></div><br>";
                    }
                    ?>
            </div>
        </div>
    </div>

    <div class="recipe_description"><?php echo $description; ?></div>

    <?php
    if(isset($_POST["chosenamount"])){ #wenn gewünschte Portionen eingegeben hat ist die Bedingung erfüllt --> Unterschied liegt im Value!
        ?><div class="amountservings"><span>Zutaten für</span><form action="recipe_detailview.php?id=<?php echo $_GET["id"]; ?>" method="post"> <input type="number" name="chosenamount" min="1" max="600" value="<?php echo$_POST["chosenamount"];?>" required><button type="submit"><img src="pic_collection/icons/icon_reload_weiß.png" alt="reload"/>Portionen</button></form></div><?php
    }else{
        ?><div class="amountservings"><span>Zutaten für</span><form action="recipe_detailview.php?id=<?php echo $_GET["id"]; ?>" method="post"> <input type="number" name="chosenamount" min="1" max="600" value="<?php echo$amount_servings;?>" required><button type="submit"><img src="pic_collection/icons/icon_reload_weiß.png" alt="reload"/>Portionen</button></form></div><?php
    }
    ?>
    <div class="ingredients_all">
    <?php
    $statement3 = $pdo->prepare("SELECT amount, unit_name, ingredient_name FROM recipe, recipe_ingredient, unit, ingredient WHERE recipe.id = ? AND recipe_ingredient.recipe_id = recipe.id AND recipe_ingredient.ingredient_id = ingredient.id AND recipe_ingredient.unit_id = unit.id ");
    if($statement3->execute(array($_GET["id"]))){
        if(isset($_POST["chosenamount"])){ #wenn gewünschte Portionen eingegeben hat ist die Bedingung erfüllt
            while($row=$statement3->fetch()){
                $calculate = round($row["amount"]*($_POST["chosenamount"] / $amount_servings), 1); #Menge der Zutaten ausrechnen je nach Portionsangabe
                if($calculate == 0){
                    $calculate = " ";
                }
                echo "<div class='ingredient'><div class='amount'>".$calculate."</div><div class='unit'>".$row["unit_name"]."</div><div class='ingname'>".$row["ingredient_name"]."</div></div>";
            }
        }else{
            while($row=$statement3->fetch()){
                echo "<div class='ingredient'><div class='amount'>".$row["amount"]."</div><div class='unit'>".$row["unit_name"]."</div><div class='ingname'>".$row["ingredient_name"]."</div></div>"; #normale Daten aus der DB anzeigen
            }
        }
    }else {
        die("Datenbank-Fehler3");
    }
    ?>
    </div>

    <div class="instruction_title">Zubereitung</div><div class="recipe_instruction"><?php echo $instruction; ?></div>

    <div class="container_detailview_bottom">
        <?php
        if(isset($_SESSION["user_id"])){
        ?>
            <div class="recipe_review">
                <form action="recipe_review_do.php?id=<?php echo $_GET["id"]; ?>" method="post" enctype="multipart/form-data">
                    <div class="review_left">
                        <div class="sternebewertung">
                            <div class="bewertung" title="Keine Bewertung">
                                <label><input type="radio" name="stars" value="0" checked="checked"> Bewertung</label>
                            </div>
                            <input type="radio" id="stern5" name="stars" value="5"><label for="stern5">5</label>
                            <input type="radio" id="stern4" name="stars" value="4"><label for="stern4">4</label>
                            <input type="radio" id="stern3" name="stars" value="3"><label for="stern3">3</label>
                            <input type="radio" id="stern2" name="stars" value="2"><label for="stern2">2</label>
                            <input type="radio" id="stern1" name="stars" value="1"><label for="stern1">1</label>
                        </div>
                        <div class="bewertungfile">
                            <div>Bild hochladen</div>
                            <input class="form-control form-control-sm" type="file" name="file" style="border-radius: 5rem;font-size: 15px;width: 260px;box-shadow: none"><br>
                        </div>
                    </div>
                    <div class="comment-box">
                        <textarea name="comment" cols="35" rows="2" placeholder="Gebe einen Kommentar zu diesem Rezept ein." id="comment"></textarea>
                        <button type="submit">Absenden</button>
                    </div>
                </form>
            </div>
            <?php
        }else{
        ?>
            <div class="nocomments"><a href="login.php">Logge dich ein</a> um einen Kommentar zu verfassen oder eine Bewertung abzugeben.</div>
        <?php
        }
        ?>

    <div class="comments">
    <?php
    #alle Kommentare ausgeben
    $statement = $pdo->prepare("SELECT * FROM review, user WHERE review.recipe_id = ? AND review.user_id=user.id ");
    if($statement->execute(array($_GET["id"]))){
        while($row=$statement->fetch()) {
            #ermitteln der Sternebewertungen
            if($row["stars"] == 1){
                $sterne = "icon_stern_eins.png";
            }elseif ($row["stars"] == 2){
                $sterne = "icon_stern_zwei.png";
            }elseif ($row["stars"] == 3){
                $sterne = "icon_stern_drei.png";
            }elseif ($row["stars"] == 4){
                $sterne = "icon_stern_vier.png";
            }elseif ($row["stars"] == 5){
                $sterne = "icon_stern_fünf.png";
            }elseif ($row["stars"] == 0){
                $sterne = "icon_stern_leer.png";
            }


            echo "<div class='single_comment'>";
            if (($row["profilepicture"])== NULL or ""){
                echo "<img class='profilepicture_small' src='pic_collection/user_nopicture.png' alt='Benutzer:in' height='45px' width='45px'>"; # allgemeines Bild, wenn kein Profilbild
            }else{
                echo "<img class='profilepicture_small' src='files/" . $row["profilepicture"] . "'alt='Profilbild von" . $row["username"] . "'>" ."<br>"; # persönliches Profilbild
            }
            echo "<div class='commentright'><div class='commentrighttop'><div class='commentauthor'>".$row["username"]."</div>";
            echo "<div class='commenttime'>".$row["when_posted"]."</div></div>";
            echo "<div class='commentstars'>"."<img src='pic_collection/icons/".$sterne."'>"."</div>";
            echo $row["comment"]."</div>";
            echo "</div>";
        }
    }
    ?>
    </div>
    </div>
    <?php
    require("includes/footer_inc.php")
    ?>
    </div>
</div>
</body>
</html>


