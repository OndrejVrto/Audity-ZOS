<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Page();

    // ak uživateľ nieje prihlásený, presmeruje ho na hlavnú stránku
    if ($page->levelUser <= 2) {
        header("Location: /");
        exit();
    }

    $row = $db->query('SELECT * FROM `50_sys_users`, `53_sys_levels` WHERE `OsobneCislo` = ? AND `ID53_sys_levels` = `ID53`', $page->LoginUser)->fetchArray();

    // todo: Funkčnosť tejto stránky

    // ! každý prihlásený uživateľ
    // *// zobrazenie veľkého obrázka avatara
    // *// zobrazenie údajov v peknej forme
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
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4" style="min-width: 360px;">

                    <!-- Profile Image -->
                    <div class="card card-warning card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img src="<?= $page->suborAvatara ?>" class="profile-user-img img-fluid img-circle" alt="User Image Avatar">
                            </div>

                            <h3 class="profile-username text-center"><?= $page->userNameShort ?></h3>

                            <p class="text-muted text-center">Level <?= $row['ID53_sys_levels'] . " - " . $row['NazovCACHE'] ?></p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Osobné číslo</b> <a class="float-right"><?= $row['OsobneCislo'] ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Zamestnaný od</b> <a class="float-right"><?= $row['Zamestnany_OD'] ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Stredisko</b> <a class="float-right"><?= $row['Stredisko'] ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Názov strediska</b> <a class="float-right"><?= $row['NazovStrediska'] ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>E-mail</b> <a class="float-right"><?= $row['Email'] ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Telefónne číslo</b> <a class="float-right"><?= $row['TelefonneCislo'] ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Dátum inicializácie</b> <a class="float-right"><?= $row['Datum_Inicializacie_Konta'] ?></a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Editovanie</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <form action="<?= $page->linkCisty ?>pass" method="post">
                                <button type="submit" name="detail" ID='button-detail' value="" class="btn btn-primary btn-block">Zmena hesla</button>
                            </form>
                            <form action="<?= $page->linkCisty ?>edit" method="post">
                                <button type="submit" name="edit" ID='button-edit' value="" class="btn btn-success btn-block">Editovať údaje</button>
                            </form>
                            <form action="<?= $page->linkCisty ?>delete" method="post">
                                <button type="submit" name="delete" ID='button-delete' value="" class="btn btn-danger btn-block">Zmazať užívateľa</button>
                            </form>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Aktivita</a></li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Nastavenia</a></li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="activity">
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                            <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                                            <span class="username">
                                                <a href="#">Jonathan Burke Jr.</a>
                                                <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                            </span>
                                            <span class="description">Shared publicly - 7:30 PM today</span>
                                        </div>
                                        <!-- /.user-block -->
                                        <p>
                                            Lorem ipsum represents a long-held tradition for designers,
                                            typographers and the like. Some people hate it and argue for
                                            its demise, but others ignore the hate as they create awesome
                                            tools to help create filler text for everyone from bacon lovers
                                            to Charlie Sheen fans.
                                        </p>

                                        <p>
                                            <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                            <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                            <span class="float-right">
                                                <a href="#" class="link-black text-sm">
                                                    <i class="far fa-comments mr-1"></i> Comments (5)
                                                </a>
                                            </span>
                                        </p>

                                        <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                                    </div>
                                    <!-- /.post -->



                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="settings">
                                    <form class="form-horizontal">
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputName" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputName2" placeholder="Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputExperience" class="col-sm-2 col-form-label">Experience</label>
                                            <div class="col-sm-10">
                                                <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputSkills" class="col-sm-2 col-form-label">Skills</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky