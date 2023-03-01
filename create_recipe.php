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
<div class="all">
<div class="main_wrapper">
<!--HEADER-->
<?php
require("includes/header_inc.php");
?>
<div class="marginleft_recipe"
    <h1 class="ueberschrift">Erstelle ein Rezept</h1>

    <?php
    #prüfen, ob der User eingeloggt ist
    if(!isset($_SESSION["user_id"])){
        echo "<div class='ueberschrift'>Upsala!</div><div class='fliesstext'> Du bist noch nicht eingeloggt.</div>";
        echo "<div class=' fliesstext'>Zum Login kommst du <a href='login.php'>hier!</a></div>";
        echo "<div class='bild_mittig'><img src='pic_collection/jummy.jpg' alt='leckeres Essen'></div>";
        require("includes/footer_inc.php");
        die();
        }
    ?>
    <!-- Script-Funktion für Hinzufügen von Zeilen -->
    <script>
        var counter = 6; //fängt da an zu zählen wo die Nummern der "festen Formularfelder" aufhören
        function moreFields() {
            if(counter <= 19){ //man soll nur 20 Zutaten eingeben können
                counter++;
            }else{
                return; //Script wird abgebrochen
            }
            var newFields = document.getElementById('template').cloneNode(true); //template holen und klonen
            newFields.id = ''; //id template entfernen
            newFields.style.display = 'flex'; //sichtbar machen
            var newField = newFields.childNodes; //childNodes hinzufügen
            for (var i=0;i<newField.length;i++) { //die childNodes des Klons durchgehen
                var theName = newField[i].name
                if (theName) // jedes name finden
                    newField[i].name = theName + counter; //name um den counter erweitern
            }
            var insertHere = document.getElementById('marker'); //marker holen (unten beim Button onclick)
            insertHere.parentNode.insertBefore(newFields,insertHere); //der Klon wird vor dem div mit der id marker eingesetzt
        }
        window.onload = moreFields; //ausführen & neues Feld sichtbar machen
    </script>

    <!-- Vorbereitung für Formular was später geklont wird, und der remove button. Dieses Div zählt als Template und hat deshalb auch display:none und der User sieht es nicht -->
    <div id="template" style="display: none">

        <input type="button" value="-" class="add_ingredient"
               onclick="this.parentNode.parentNode.removeChild(this.parentNode);" /><br /><br />
            <tr>
                <td><input type="number" name="amount" id="amount"></td>
                <td> <!-- dynamisches Dropdown-Menü mit Daten aus der Datenbank -->
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM unit order by unit_name ASC ");
                    if($statement->execute()){
                        echo "<select name='unit' class='form-select' aria-label='Default select example'>";
                        echo"<option value=>--- Select ---</option>";
                        while($row=$statement->fetch()){
                            echo '<option value="'. $row['id'] .'">'. $row['unit_name'].'</option>';
                        }
                        echo "</select>";
                    }else{
                        die("Datenbank-Fehler");
                    }
                    ?>
                </td>
                <td>
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM ingredient order by ingredient_name ASC ");
                    if($statement->execute()){
                        echo "<select name='ingredient' class='form-select' aria-label='Default select example'>";
                        echo"<option value=>--- Select ---</option>";
                        while($row=$statement->fetch()){
                            echo '<option value="'. $row['id'] .'">'. $row['ingredient_name'].'</option>';
                        }
                        echo "</select>";
                    }else{
                        die("Datenbank-Fehler");
                    }
                    ?>
                </td>
            </tr>
    </div>

    <!-- FORMULAR -->

    <form class="recipe_form" action="create_recipe_do.php" method="post" enctype="multipart/form-data">
        <div class="rezeptformular">
            <div class="unterüberschrift">Titel *</div>
            <div class='form-outline mb-2'>
                <input type="text" name="title" placeholder="z.B. Pfannkuchen" id="title" required>
            </div>

            <div class="unterüberschrift">Beschreibung</div>
            <div class='form-outline mb-2'>
             <input type="text" name="description" placeholder="Zusätliche Infos..." id="description">
            </div>

            <div class="unterüberschrift">Zutaten ︎</div>
            <div class='form-outline mb-2'>
                <p class="fliesstext">Mengenangaben für <input type="number" name="amount_servings" min="1" max="20" id="amount_servings" required> Portionen *︎</p>
            </div>

            <div class='form-outline mb-2'>
            <table>
                <tr>
                    <th class="kleintext">Menge </th>
                    <th class="kleintext">Einheit *</th>
                    <th class="kleintext">Zutat *</th>
                </tr>
                <?php
                #Arrays die für die names der "festen" Zutaten gebraucht werden
                $amountArray = array("amount1", "amount2", "amount3", "amount4", "amount5", "amount6", "amount7", "amount8", "amount9", "amount10", "amount11", "amount12", "amount13", "amount14", "amount15", "amount16", "amount17", "amount18", "amount19", "amount20" );
                $unitArray = array("unit1", "unit2", "unit3", "unit4", "unit5", "unit6", "unit7", "unit8", "unit9", "unit10", "unit11", "unit12", "unit13", "unit14", "unit15", "unit16", "unit17", "unit18", "unit19", "unit20");
                $ingredientArray = array("ingredient1", "ingredient2", "ingredient3", "ingredient4", "ingredient5", "ingredient6", "ingredient7", "ingredient8", "ingredient9", "ingredient10", "ingredient11", "ingredient12", "ingredient13", "ingredient14", "ingredient15", "ingredient16", "ingredient17", "ingredient18", "ingredient19", "ingredient20");

                for ($i=0; $i <= 5; $i++){
                    ?>
                    <tr>
                        <td><input type="number" name=<?php echo $amountArray[$i]?> id="amount"></td>
                        <td> <!-- dynamisches Dropdown-Menü mit Daten aus der Datenbank -->
                            <?php
                            $statement = $pdo->prepare("SELECT * FROM unit order by unit_name ASC ");
                            if($statement->execute()){
                                echo "<select name='$unitArray[$i]' class='form-select' aria-label='Default select example'>";
                                echo"<option value=>--- Select ---</option>";
                                while($row=$statement->fetch()){
                                    echo '<option value="'. $row['id'] .'">'. $row['unit_name'].'</option>';
                                }
                                echo "</select>";
                            }else{
                                die("Datenbank-Fehler");
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            $statement = $pdo->prepare("SELECT * FROM ingredient order by ingredient_name ASC ");
                            if($statement->execute()){
                                echo "<select name='$ingredientArray[$i]' class='form-select' aria-label='Default select example'>";
                                echo"<option value=>--- Select ---</option>";
                                while($row=$statement->fetch()){
                                    echo '<option value="'. $row['id'] .'">'. $row['ingredient_name'].'</option>';
                                }
                                echo "</select>";
                            }else{
                                die("Datenbank-Fehler");
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            </div>

        <!--hinzufügen einer Zeile-->
            <div class='form-outline mb-2'>
                <span id="marker"></span> <!--"Marker", hiervor sollen die neuen Felder eingefügt werden-->
                <input type="button" class="add_ingredient" onclick="moreFields()" value="+" />
            </div>

            <div class="unterüberschrift">Zubereitung *</div>
            <div class='form-outline mb-2'>
                 <textarea name="instruction" cols="60" rows="20" placeholder="Zubereitung" id="instruction" required></textarea>
            </div>


            <div class="unterüberschrift">Zubereitungsdauer *</div>
            <div class='form-outline mb-2'>
                <p class="fliesstext"><input type="number" name="cooking_time_hours" max="100" id="cooking_time_hours" value="0" required> h <input type="number" name="cooking_time_mins" max="69" id="cooking_time_mins" value="0" required>min</p>
            </div>

            <div class="unterüberschrift">Schwierigkeitsgrad *</div>
            <div class='form-outline mb-2'>
                <?php
                $statement = $pdo->prepare("SELECT * FROM level");
                if($statement->execute()){
                    echo "<select name='level' class='form-select' aria-label='Default select example' style='margin-left: 0px;' required>";
                    echo"<option value=>--- Select ---</option>";
                    while($row=$statement->fetch()){
                        echo '<option value="'. $row['id'] .'">'. $row['level_name'].'</option>';
                    }
                    echo "</select>";
                }else{
                    die("Datenbank-Fehler");
                }
                ?>
            </div>

            <div class="unterüberschrift">Kalorien</div>
            <div class='form-outline mb-2'>
                <p class="fliesstext"><input type="number" name="calories" id="calories"> kcal pro Portion</p>
            </div>

            <div class="unterüberschrift">Kategorien</div>
            <br>

            <div class="categories">
                <div class="dish_type">
                    <div class="kleintext">Menüart</div>
                    <div class="checkflex">
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM dish_type order by dish_type_name ASC ");
                        if($statement->execute()){
                            while($row=$statement->fetch()){
                                echo "<div class='check'>";
                                echo "<input class='form-check-input' type='checkbox' name='dish_type[]' value='".$row['dish_type_name']."'><div class='catmargin'>"." ".$row['dish_type_name']."</div></input>"; #das [] erzeugt einen Array mit allen getickten Boxen!
                                echo "</div>";
                            }
                        }else{
                            die("Datenbank-Fehler!");
                        }
                        ?>
                    </div>
                </div>
                <div class="restriction">
                    <div class="kleintext">Ernährungsweise</div>
                    <div class="checkflex">
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM restriction order by restriction_name ASC ");
                        if($statement->execute()){
                            while($row=$statement->fetch()){
                                echo "<div class='check'>";
                                echo "<input class='form-check-input' type='checkbox' name='restriction[]' value='".$row['restriction_name']."'><div class='catmargin'>".$row['restriction_name']." "."</div></input> "; #das [] erzeugt einen Array mit allen getickten Boxen!
                                echo "</div>";
                            }
                        }else{
                            die("Datenbank-Fehler!");
                        }
                        ?>
                    </div>
                </div>
                <div class="cuisine">
                    <div class="kleintext">Küche (Region)</div>
                    <div class="checkflex">
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM cuisine order by cuisine_name ASC ");
                        if($statement->execute()){
                            while($row=$statement->fetch()){
                                echo "<div class='check'>";
                                echo "<input class='form-check-input' type='checkbox' name='cuisine[]' value='".$row['cuisine_name']."'><div class='catmargin'>".$row['cuisine_name']." "."</div></input>"; #das [] erzeugt einen Array mit allen getickten Boxen!
                                echo "</div>";
                            }
                        }else{
                            die("Datenbank-Fehler");
                        }
                        ?>
                    </div>
                </div>
                <div class="sondercategories">
                    <div class="kleintext">Sonderkategorien</div>
                    <div class="checkflex">
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM category order by category_name ASC ");
                        if($statement->execute()){
                            while($row=$statement->fetch()){
                                echo "<div class='check'>";
                                echo "<input class='form-check-input' type='checkbox' name='category[]' value='".$row['category_name']."'><div class='catmargin'>".$row['category_name']." "."</div></input> "; #das [] erzeugt einen Array mit allen getickten Boxen!
                                echo "</div>";
                            }
                        }else{
                            die("Datenbank-Fehler");
                        }
                        ?>
                    </div>
                </div>
            </div>

            <div class="unterüberschrift">Bild hochladen</div> <!-- Kommt in die picture Tabelle! -->
            <div>
                <input type="file" name="file" class="form-control" style="width: 350px;"><br>
            </div>
            <div class='form-outline mb-2'>
                <button class="submitrecipe" type="submit" style="height: 40px; background-color: #FC7F16;">Absenden</button>
            </div>
            <br><b>Hinweis:</b> Felder mit * müssen ausgefüllt werden!
        </div>
    </form>
</div>
    <!--FOOTER-->
    <?php
    require("includes/footer_inc.php")
    ?>
</div>
</div>
</body>
</html>
