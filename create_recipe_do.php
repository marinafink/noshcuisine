<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rezept erstellen - Nosh Cuisine</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="icon" type="image/png" sizes="32x32" href="pic_collection/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<!--HEADER-->
<?php
require("includes/header_inc.php");

#prüfen, ob der User eingeloggt ist
if(!isset($_SESSION["user_id"])){
    echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'> Du bist noch nicht eingeloggt.</div>";
    echo "<div class=' fliesstext center_text'>Zum Login kommst du <a href='login.php'>hier!</a></div>";
    echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
    require("includes/footer_inc.php");
    die();
}

#die Arrays aus der Formularseite (für die erste !isset-Prüfung UND für die Übergabe aus der for-Schleife für recipe_ingredient)
$amountArray = array("amount1", "amount2", "amount3", "amount4", "amount5", "amount6", "amount7", "amount8", "amount9", "amount10", "amount11", "amount12", "amount13", "amount14", "amount15", "amount16", "amount17", "amount18", "amount19", "amount20");
$unitArray = array("unit1", "unit2", "unit3", "unit4", "unit5", "unit6", "unit7", "unit8", "unit9", "unit10", "unit11", "unit12", "unit13", "unit14", "unit15", "unit16", "unit17", "unit18", "unit19", "unit20");
$ingredientArray = array("ingredient1", "ingredient2", "ingredient3", "ingredient4", "ingredient5", "ingredient6", "ingredient7", "ingredient8", "ingredient9", "ingredient10", "ingredient11", "ingredient12", "ingredient13", "ingredient14", "ingredient15", "ingredient16", "ingredient17", "ingredient18", "ingredient19", "ingredient20");

#prüfen, ob das Formular korrekt gesendet wurde: ingredients
for ($i=0; $i <=5; $i++) {
    if(!isset($_POST[$amountArray[$i]]) or  !isset($_POST[$unitArray[$i]]) or !isset($_POST[$ingredientArray[$i]])){
        echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'> Hier ist wohl etwas schiefgelaufen....</div>";
        echo "<div class=' fliesstext center_text'>Zur Startseite kommst du <a href='index.php'>hier!</a></div>";
        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
        require("includes/footer_inc.php");
        die();
    }
}

#prüfen, ob das Formular korrekt gesendet wurde und die Dinge mit * eingetragen sind : "normale" names
if (empty($_POST["title"]) or !isset($_POST["description"]) or empty($_POST["amount_servings"]) or empty($_POST["instruction"]) or !isset($_POST["cooking_time_hours"]) or empty($_POST["cooking_time_mins"]) or empty($_POST["level"]) or !isset($_POST["calories"]) or !isset($_FILES["file"])) {
    echo "<div class='ueberschrift'>Upsalaaaaaa!</div><div class='fliesstext center_text'> Hier ist wohl etwas schiefgelaufen....</div>";
    echo "<div class=' fliesstext center_text'>Zur Startseite kommst du <a href='index.php'>hier!</a></div>";
    echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
    require("includes/footer_inc.php");
    die();
}

#überprüfen, ob mindestens eine Zutat eingegeben wurde -> für jeden Durchlauf der for-Schleife des Formulars
$valid = false;
for($i = 0; $i <= 19; $i++){
    if(($_POST[$unitArray[$i]] != null) and ($_POST[$ingredientArray[$i]] != null)){ #Einheit und Zutat sind ausgefüllt -> valide!
        $valid = true;
    }elseif(($_POST[$amountArray[$i]] != "") or ($_POST[$unitArray[$i]] != null) or ($_POST[$ingredientArray[$i]] != null)){ #wenn Zeile nicht vollständig: ein Eintrag der Zeile fehlt -> Fehlermeldung
        echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'>Die Zutat konnte nicht gespeichert werden! Bitte achte darauf, dass jede Zutat eine Einheit besitzt.</div>";
        echo "<div class=' fliesstext center_text'>Zurück zum Formular kommst du <a href='create_recipe.php'>hier!</a></div>";
        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
        require("includes/footer_inc.php");
        die();
    }
    if($i == 7 and !$valid){ #wenn nach letzter Zeile immernoch keine Zutat eingetragen wurde: Fehlermeldung (true and true ist true!)
        echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'>Du musst mindestens eine Zutat eingeben!</div>";
        echo "<div class=' fliesstext center_text'>Zurück zum Formular kommst du <a href='create_recipe.php'>hier!</a></div>";
        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
        require("includes/footer_inc.php");
        die();
    }
}

#Zeitpunkt des Rezepteintrags ermitteln:
$time_posted = date('Y-m-d');

#Ermitteln der Zeitspanne der Rezeptzubereitung:
if(($_POST["cooking_time_hours"])==0 and ($_POST["cooking_time_mins"])<15){
    $timespan_id = 1;
}elseif (($_POST["cooking_time_hours"])==0 and ($_POST["cooking_time_mins"])<30){
    $timespan_id = 2;
}elseif (($_POST["cooking_time_hours"])==0 and ($_POST["cooking_time_mins"])<60){
    $timespan_id = 3;
}elseif (($_POST["cooking_time_hours"])>0){
    $timespan_id = 4;
}

#bei calories brauchts das, bei description irgendwie nicht... aber so funktionert es, dass nicht unbedingt Kalorien eingetragen werden müssen!
if($_POST["calories"]==NULL){
    $calorieseintrag = null;
}else{
    $calorieseintrag = htmlspecialchars($_POST["calories"]);
}

#Eintrag in die recipe Tabelle
$statement = $pdo->prepare("INSERT INTO recipe (title, description, amount_servings, instruction, cooking_time_hours, cooking_time_mins, level_id, calories, time_posted, author, timespan_id) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
if($statement->execute(array(htmlspecialchars($_POST["title"]), htmlspecialchars($_POST["description"]),htmlspecialchars($_POST["amount_servings"]),htmlspecialchars($_POST["instruction"]),htmlspecialchars($_POST["cooking_time_hours"]),htmlspecialchars($_POST["cooking_time_mins"]),htmlspecialchars($_POST["level"]),$calorieseintrag,htmlspecialchars($time_posted),htmlspecialchars($_SESSION["username"]), htmlspecialchars($timespan_id)))){
    echo "";
}else{
    echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext center_text'> Hier ist wohl etwas schiefgelaufen....</div>";
    echo "<div class=' fliesstext center_text'>Zurück zum Rezept kommst du <a href='create_recipe.php'>hier!</a></div>";
    echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
    require("includes/footer_inc.php");
    die();
}

#die id des neuen Rezepts ermitteln für den zusammengesetzten Pimärschlüssel
$statement = $pdo->prepare("SELECT id FROM recipe ORDER BY id DESC LIMIT 1");
if($statement->execute()){
    while($row=$statement->fetch()){
    $recipe_id = $row["id"]; #$recipe_id ist jetzt die id des neuesten Rezepts
    }
}

#Eintrag in die recipe_ingredient Tabelle

if($_POST["calories"]==NULL){
    $calorieseintrag = null;
}else{
    $calorieseintrag = htmlspecialchars($_POST["calories"]);
}

for ($i=0; $i <=19; $i++) {
    if(($_POST[$unitArray[$i]] != null) and ($_POST[$ingredientArray[$i]] != null)) { #Abfrage, ob Einheit und Zutat einer Zeile befüllt sind -> sonst überspringen
        if($_POST[$amountArray[$i]]==NULL){
            $amounteintrag = null;
        }else{
            $amounteintrag= htmlspecialchars($_POST[$amountArray[$i]]);
        }
        $statement = $pdo->prepare("INSERT INTO recipe_ingredient (amount, recipe_id,ingredient_id, unit_id) VALUES (?, ?, ?, ?)");
        if ($statement->execute(array($amounteintrag,htmlspecialchars($recipe_id),htmlspecialchars($_POST[$ingredientArray[$i]]),htmlspecialchars($_POST[$unitArray[$i]])))) {
            echo "";
        } else {
            die("Datenbank-Fehler2");
        }
    }
}

#Eintrag in die recipe_dish_type Tabelle

if(($_POST["dish_type"]) == null){ #ist keine Option ausgewählt, wird dieser Part übersprungen und es wird nichts ausgegeben
    echo "";
}else{
    foreach ($_POST['dish_type'] as $dishtype) { #da name="dish_type" aus dem Formular in diesem Fall (checkbox) ein Array ist, kann er mit einer foreach-Schleife ausgelesen werden! Im Array sind alle getickten Checkboxen indexiert und diese Werte werden jeweils in der Variable gespeichert!

        $statement = $pdo->prepare("SELECT id FROM dish_type WHERE dish_type_name = :dish_type_name"); #passende id für Fremdschlüssel rausfinden
        $statement->bindParam(":dish_type_name", $dishtype);
        if($statement->execute()){
            while($row=$statement->fetch()){
                $dish_type_id = $row["id"]; #dish _type_id für den Fremdschlüssel
            }}

        $statement = $pdo->prepare("INSERT INTO recipe_dish_type (recipe_id, dish_type_id) VALUES (?,?)");
        if($statement->execute(array(htmlspecialchars($recipe_id),htmlspecialchars($dish_type_id)))){
            echo "";
        }else{
            die("Datenbank-Fehler3");
        }
    }
}

#Eintrag in die recipe_restriction Tabelle

if(($_POST["restriction"]) == null){
    echo "";
}else{
    foreach ($_POST['restriction'] as $restriction) { #da name="dish_type" aus dem Formular in diesem Fall (checkbox) ein Array ist, kann er mit einer foreach-Schleife ausgelesen werden! Im Array sind alle getickten Checkboxen indexiert und diese Werte werden jeweils in der Variable gespeichert!

        $statement = $pdo->prepare("SELECT id FROM restriction WHERE restriction_name = :restriction_name"); #passende id für Fremdschlüssel rausfinden
        $statement->bindParam(":restriction_name", $restriction);
        if($statement->execute()){
            while($row=$statement->fetch()){
                $restriction_id = $row["id"]; #dish _type_id für den Fremdschlüssel
            }}

        $statement = $pdo->prepare("INSERT INTO recipe_restriction (recipe_id, restriction_id) VALUES (?,?)");
        if($statement->execute(array(htmlspecialchars($recipe_id),htmlspecialchars($restriction_id)))){
            echo "";
        }else{
            die("Datenbank-Fehler4");
        }
    }
}

#Eintrag in die recipe_cuisine Tabelle

if(($_POST["cuisine"]) ==  null){
    echo "";
}else{
    foreach ($_POST['cuisine'] as $cuisine) { #da name="dish_type" aus dem Formular in diesem Fall (checkbox) ein Array ist, kann er mit einer foreach-Schleife ausgelesen werden! Im Array sind alle getickten Checkboxen indexiert und diese Werte werden jeweils in der Variable gespeichert!

        $statement = $pdo->prepare("SELECT id FROM cuisine WHERE cuisine_name = :cuisine_name"); #passende id für Fremdschlüssel rausfinden
        $statement->bindParam(":cuisine_name", $cuisine);
        if($statement->execute()){
            while($row=$statement->fetch()){
                $cuisine_id = $row["id"]; #dish _type_id für den Fremdschlüssel
            }}

        $statement = $pdo->prepare("INSERT INTO recipe_cuisine (recipe_id, cuisine_id) VALUES (?,?)");
        if($statement->execute(array(htmlspecialchars($recipe_id),htmlspecialchars($cuisine_id)))){
            echo "";
        }else{
            die("Datenbank-Fehler5");
        }
    }
}

#Eintrag in die recipe_category Tabelle

if(($_POST["category"]) == null){
    echo "";
}else{
    foreach ($_POST['category'] as $category) { #da name="dish_type" aus dem Formular in diesem Fall (checkbox) ein Array ist, kann er mit einer foreach-Schleife ausgelesen werden! Im Array sind alle getickten Checkboxen indexiert und diese Werte werden jeweils in der Variable gespeichert!

        $statement = $pdo->prepare("SELECT id FROM category WHERE category_name = :category_name"); #passende id für Fremdschlüssel rausfinden
        $statement->bindParam(":category_name", $category);
        if($statement->execute()){
            while($row=$statement->fetch()){
                $category_id = $row["id"]; #dish _type_id für den Fremdschlüssel
            }}

        $statement = $pdo->prepare("INSERT INTO recipe_category (recipe_id, category_id) VALUES (?,?)");
        if($statement->execute(array(htmlspecialchars($recipe_id),htmlspecialchars($category_id)))){
            echo "";
        }else{
            die("Datenbank-Fehler6");
        }
    }
}

#random string funktion
mt_srand((double) microtime() * 1000000);  #Zufallsgenerator "schütteln"
$zeichen = "ABCDEFGHIKLMNPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";   #Zeichenpool
$pin = "";
for ($i=1;$i<=15;$i++){ #15 stelligen PIN aus dem Zeichenpool erzeugen
    $pin = $pin.$zeichen[mt_rand(0,(strlen($zeichen)-1))];
}

#Vorbereitungen für Datei Upload
if(!isset($_FILES["file"]["tmp_name"]) || !isset($_FILES["file"]["name"])){
    die("Fehler im Upload");
}
if($_FILES["file"]["name"]!= null){
    $fileName = $_FILES["file"]["name"];
    $fileType = pathinfo($fileName,PATHINFO_EXTENSION);

    if($fileType == "jpg" OR $fileType == "png" OR $fileType == "pdf" OR $fileType == "HEIC" OR $fileType == "jpeg" OR $fileType == "JPG"){
        echo "";
    }else{
        die("<div class='textinmiddle'>Nicht zugelassene Dateiart. Folgende Dateiarten sind zugelassen: jpg, png, pdf, HEIC.<br>");
    }

    if($_FILES["file"]["size"] > 80000000){
        die("<div>Diese Datei ist leider zu groß.</div> ");
    }

    $fileNameNew = $pin.".".$fileType;

    if(!move_uploaded_file($_FILES["file"]["tmp_name"],"/home/mf177/public_html/files/".$fileNameNew )){
        die ("<div>Upload fehlgeschlagen.</div>");
    }

    #Eintrag in die picture Tabelle

    $statement = $pdo->prepare("INSERT INTO picture (filename, recipe_id, user_id) VALUES (?,?,?)");
    if($statement->execute(array(htmlspecialchars($fileNameNew),htmlspecialchars($recipe_id),htmlspecialchars($_SESSION["user_id"])))){
        echo "";
    }else{
        die("Datenbank-Fehler7");
    }

}else{
    $placeholder = "placeholder_recipepicture.jpeg";
    $statement = $pdo->prepare("INSERT INTO picture (filename, recipe_id, user_id) VALUES (?,?,?)");
    if($statement->execute(array(htmlspecialchars($placeholder),htmlspecialchars($recipe_id),htmlspecialchars($_SESSION["user_id"])))){
        echo "";
    }else{
        die("Datenbank-Fehler7.1");
    }
}

#wenn der obige Code erfolgreich ausgeführt wurde, kommt diese Meldung. (ansonsten wird das Skript ja vorher schon mit die() unterbrochen)
echo "<div class='ueberschrift'>Juhu!</div><div class='fliesstext center_text'> Dein Rezept ist eingetragen!</div>";
echo "<div class=' fliesstext center_text'>Zurück zur Startseite kommst du <a href='index.php'>hier!</a></div>";
echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";

#FOOTER
require("includes/footer_inc.php")
?>
</body>
</html>
