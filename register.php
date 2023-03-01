<?php
require("includes/db_inc.php");
session_start();
global $pdo;
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Registrieren - Nosh Cuisine</title>
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
            if (isset($_SESSION["username"])){
                echo "<div class='ueberschrift'>Upsala!</div>";
                echo "<div class='fliesstext center_text'>Du bist schon eingeloggt, " . $_SESSION["username"] . ".<br>Logge dich wieder aus, damit du ein anderes Konto registrieren kannst!</div>";
                echo "<div class='bild_mittig_groß'><img src='pic_collection/family_cooking.jpg' alt='Kochende Familie' style='max-width:1400px'></div>";
                require("includes/footer_inc.php");
                die();
            }
            ?>
            <section>
                <div class="container py-5">
                    <div class="row d-flex justify-content-center align-items-center h-140">
                        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                            <div class="card shadow-2-strong" style="border-radius: 1rem;">
                                <div class="card-body p-5 text-center" style="margin: 15px">

                                    <form action="register_do.php" method="post">
                                        <fieldset>
                                            <h3 class="mb-5">Registriere dich</h3>
                                            <div class="account">
                                                <div class="form-outline mb-4">
                                                    <label for="username" class="form-label">Benutzername</label><br>
                                                    <input type="text" name="username" placeholder="Dein Username" id="username" class="form-control form-control-lg" style="border-radius: 5rem; font-size: 1rem;">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="account">
                                                <div class="form-outline mb-4">
                                                    <label for="mail" class="form-label">E-Mail</label><br>
                                                    <input type="email" name="mail" placeholder="Deine E-Mail-Adresse" id="mail" class="form-control form-control-lg" style="border-radius: 5rem;font-size: 1rem;">
                                                </div>
                                            </div>

                                            <br>
                                            <div class="account">
                                                <div class="form-outline mb-4">
                                                    <label for="password" class="form-label">Passwort</label><br>
                                                    <input type="password" name="password" placeholder="your password" id="password" class="form-control form-control-lg" style="border-radius: 5rem;font-size: 1rem;">
                                                </div>
                                            </div>

                                            <br>

                                        </fieldset>

                                        <button type="submit" class="subscribe-btn" style="font-size: 1rem;">Registrieren</button>
                                        <br>
                                        <br>

                                    </form>
                                    Du hast schon einen Account?<br>Dann klicke
                                    <a style="color:#FC7F16;" href="login.php">hier</a>, um dich einzuloggen.

                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            <?php
            require("includes/footer_inc.php");
            ?>
            </div>
        </div>
    </body>
    </html>