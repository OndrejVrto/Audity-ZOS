<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";
    
    $page = new \Page\Page();
    $page->zobrazitBublinky = false;
    
ob_start();  // Začiatok definície hlavného obsahu
?>
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-warning"> 404</h2>

                <div class="error-content">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Stránka neexistuje.</h3>

                    <p>
                        Skús vyhľadať stránku pomocou menu na <a href="/">hlavnej stránke.</a><br>
                        Alebo využi nasledovné vyhľadávanie.
                    </p>

                    <form class="search-form" action="/vyhladavanie" method="POST">
                        <div class="input-group">
                            <input type="text" name="hladanyRetazec" class="form-control" placeholder="Hľadať ...">

                            <div class="input-group-append">
                                <button type="submit" name="submit" class="btn btn-warning"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <!-- /.input-group -->
                    </form>
                </div>
                <!-- /.error-content -->
            </div>
            <!-- /.error-page -->
        </section>
        <!-- /.content -->

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky