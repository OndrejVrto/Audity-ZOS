<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;

ob_start();  // Začiatok definície hlavného obsahu
?>
            <div class="container pb-5" >

                <div class="row">
                    <div class="col-12">
                        <h4 class="text-muted"><i class="fas fa-dove mr-3"></i>Popis programu</h4>
                        <div class="content px-2">
                            <p class="my-0">
                                Tento program vznikol na pre potreby správy všetkých auditov vykonávaných v ŽOS Zvolen.
                                Jedná sa o centrálnu evidenciu zistení, prijatých nápravných opatrení ako aj kontrolu plnenia prijatých úloh.
                            </p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12">
                        <h4 class="text-muted"><i class="fas fa-user-astronaut mr-3"></i>Tvorcovia aplikácie</h4>
                        
                        <div class="row mt-3">
                            <div class="col-12 col-md-6">
                                <div class="row">
                                    <div class="col-3">
                                        <a href="/user/detail/7706" title="Viac o autorovi ...">
                                            <img class="profile-user-img img-fluid img-circle" src="/dist/avatar/[7706] ondrej vrto.svg" alt="User Avatar Ondrej Vrťo">
                                        </a>
                                    </div>
                                    <div class="col-9">
                                        <a href="/user/detail/7706" title="Viac o autorovi ...">
                                            <h5 class="font-weight-bold text-warning">Ing. Ondrej VRŤO, IWE</h5>
                                        </a>
                                        <dl class="row p-0 m-0">
                                            <dt class="col-3">Email</dt><dd class="col-9"><a href="mailto:vrto&#64;zoszv.sk">vrto&#64;zoszv.sk</a></dd>
                                            <dt class="col-3">Email 2</dt><dd class="col-9"><a href="mailto:ondrej.vrto&#64;gmail.com">ondrej.vrto&#64;gmail.com</a></dd>
                                            <dt class="col-3">Telefón</dt><dd class="col-9"><a href="tel:+421455302640">kl. 640</a></dd>
                                            <dt class="col-3">Mobil</dt><dd class="col-9"><a href="tel:+421903504072">0903 504 072</a></dd>
                                        </dl>
                                    </div>    
                                </div>
                            </div>

                            <div class="col-12 col-md-6 py-4 py-md-0">
                                <div class="row">
                                    <div class="col-3">
                                        <a href="/user/detail/7880" title="Viac o autorovi ...">
                                            <img class="profile-user-img img-fluid img-circle" src="/dist/avatar/[7880] vladimir hancinsky.svg" alt="User Image Avatar">
                                        </a>
                                    </div>
                                    <div class="col-9">
                                        <a href="/user/detail/7880" title="Viac o autorovi ...">
                                            <h5 class="font-weight-bold text-warning">Ing. Vladimír HANČINSKÝ</h5>
                                        </a>
                                        <dl class="row p-0 m-0">
                                            <dt class="col-3">Email</dt><dd class="col-9"><a href="mailto:hancinsky&#64;zoszv.sk">hancinsky&#64;zoszv.sk</a></dd>
                                            <dt class="col-3">Telefón</dt><dd class="col-9"><a href="tel:+42153022181">kl. 181</a></dd>
                                            <dt class="col-3">Mobil</dt><dd class="col-9"><a href="tel:+421903504032">0903 504 032</a></dd>
                                        </dl>
                                    </div>    
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-12">
                        <h4 class="text-muted"><i class="fas fa-copyright mr-3"></i>Licencia šablony AdminLTE</h4>
                        <div class="content px-2">
                            <p>AdminLTE je projekt s otvoreným zdrojom, ktorý je licencovaný na základe 
                                <a target="_blank" href="https://opensource.org/licenses/MIT">licencie MIT</a>.
                                To umožňuje robiť so šablonou takmer čokoľvek, pokiaľ sú vo "všetkých podstatných častiach softvéru" uvedené autorské práva.
                                Uvedenie zdroja nie je potrebné.
                            </p>
                            Copyright &copy; 2014-2020 <a target="_blank" href="https://adminlte.io">AdminLTE.io</a>. All rights reserved.
                        </div>
                    </div>
                </div>

            </div>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky