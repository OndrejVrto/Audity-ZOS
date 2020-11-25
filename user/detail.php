<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;

    // ak uživateľ nieje prihlásený, presmeruje ho na hlavnú stránku
    if ( $page->levelUser <= 2 ){
        header("Location: /");
        exit();
    }

    $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $page->LoginUser)->fetchArray();

    // todo: Funkčnosť tejto stránky
    
    // ! každý prihlásený uživateľ
    // * zobrazenie veľkého obrázka avatara
    // * zobrazenie údajov v peknej forme
    // * zobrazenie posledných 5 dátumov nalogovania
    // * editácia vstupných údajov (email, telefon)
    // * zmena nového hesla
    // * reset hesla na pôvodný Pasword_OLD
    // * zmena avatara - v modálnom okne + referer na túto stránku
    // ? štatistika:  počet nalogovania do aplikácie Celkový/po mesiacoch/po rokoch -> vytvoriť pohľad v databáze
    // ? zoznam akcií v jednotlivých (hlavných) tabuľkách ktoré vykonal aktuálny uživateľ

    // ! LEN admin
    // * zobrazenie dátumu registrácie
    // * zobrazenie kompletného zoznamu dátumov nalogovania do systému aj s IP adresou a typom prehliadača
    // * zobrazenie hesla OLD
    // * pridelenie LEVELu od -1 do 20 prepínačmi


ob_start();  // Začiatok definície hlavného obsahu
?>

    <div class="row justify-content-around">
        <div class="col-12 col-md-8 col-xl-5">
            
            <h4 class="m-0 text-dark">Detaily načítané z MAXu</h4>

            <div class='card'>
                <div class='card-body p-2'>

                    <table class='table table-sm hover compact' width="100%" id='tabulka'>
                        <thead>

                            <tr>
                                <th class="h4" style="width: 180px;">Údaj</th>
                                <th class="h4" >Hodnota</th>
                            </tr>

                        </thead>
                        <tbody>

                            <tr>
                                <td><strong>Osobné číslo</strong></td>
                                <td><?= htmlspecialchars($data['ucislo']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Meno</strong></td>
                                <td><?= htmlspecialchars($data['umeno']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Priezvisko</strong></td>
                                <td><?= htmlspecialchars($data['upriezv']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Titul</strong></td>
                                <td><?= htmlspecialchars($data['utitul']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Stredisko</strong></td>
                                <td><?= htmlspecialchars($data['ustred']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Názov strediska</strong></td>
                                <td><?= htmlspecialchars($data['nazstred']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Nástup do ŽOS</strong></td>
                                <td><?= htmlspecialchars($data['ondate']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Výstup</strong></td>
                                <td><?= htmlspecialchars($data['offdate']) ?></td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
        <div class="col-12 col-md-8 col-xl-5">
            
            <h4 class="m-0 text-dark">Detaily tejto aplikácie</h4>

            <div class='card'>
                <div class='card-body p-2'>

                    <table class='table table-sm hover compact' width="100%" id='tabulka'>
                        <thead>

                            <tr>
                                <th class="h4" style="width: 180px;">Údaj</th>
                                <th class="h4" >Hodnota</th>
                            </tr>

                        </thead>
                        <tbody>

                            <tr>
                                <td><strong>Osobné číslo</strong></td>
                                <td><?= htmlspecialchars($dataLocal['OsobneCislo']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Meno</strong></td>
                                <td><?= htmlspecialchars($dataLocal['Meno']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Priezvisko</strong></td>
                                <td><?= htmlspecialchars($dataLocal['Priezvisko']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Titul</strong></td>
                                <td><?= htmlspecialchars($dataLocal['Titul']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>E-mail</strong></td>
                                <td><?= htmlspecialchars($dataLocal['Email']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Telefónne číslo</strong></td>
                                <td><?= htmlspecialchars($dataLocal['TelefonneCislo']) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Dátum registrácie</strong></td>
                                <td><?= htmlspecialchars($dataLocal['DatumRegistracie']) ?></td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>


            <div class='row justify-content-center pb-3'>
                <div id="UpravaDat" class='form-inline'>
                    <form action="<?= $page->linkCisty ?>pass" method="post">
                        <button type="submit" name="detail" ID='button-detail' value="" class="btn btn-primary">Zmena hesla</button>
                    </form>
                    <form action="<?= $page->linkCisty ?>edit" method="post" class="mx-1">
                        <button type="submit" name="edit" ID='button-edit' value="" class="btn btn-success">Editovať údaje</button>
                    </form>
                    <form action="<?= $page->linkCisty ?>delete" method="post">
                        <button type="submit" name="delete" ID='button-delete' value="" class="btn btn-danger">Zmazať užívateľa</button>
                    </form>
                </div>
            </div>

        </div>        
    </div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky