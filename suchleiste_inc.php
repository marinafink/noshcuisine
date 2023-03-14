<div class="weite_suchleiste">
    <form action="suchleiste_do.php" method="post" enctype="multipart/form-data">
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 53px;margin-top: 1px;"><img src="pic_collection/icons/icon_filter.png"></button>
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
            <input type="text" name="search" id="search" class="form-control" aria-label="Search input with dropdown button" placeholder="Search for your favorite recipes..." style="height: 55px; border-color: transparent;box-shadow: none;">
            <div class="input-group-append">
                <input class="search_button" type="image" src="pic_collection/icons/icon_search.png" style="margin-top: 1px; alt="Submit"/>
            </div>
        </div>
    </form>
</div>
