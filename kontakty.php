<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;

ob_start();  // Začiatok definície hlavného obsahu
?>
            <div class="container pb-5" >
                <div class="row">

                    <div class="col-12 col-md-6">
                        <h4 class="text-muted"><i class="far fa-address-card mr-3" aria-hidden="true"></i>Adresa</h4>
                            <address class="mb-0">
                                <strong>Železničné opravovne a strojárne Zvolen, a.s.</strong><br>
                                Môťovská cesta 259/11<br>
                                960 03 <span class="ml-2">Zvolen</span><br>
                                Slovenská Republika
                            </address>
                        <h4 class="text-muted pt-4"><i class="fas fa-phone-alt mr-3" aria-hidden="true"></i>Telefón</h4>
                            <a class="text-muted" href="tel:+421455302111">(00421 45) 53 02 111</a>
                        <h4 class="text-muted pt-4"><i class="far fa-envelope mr-3" aria-hidden="true"></i>E-mail</h4>
                            <a class="text-muted" href="mailto:zoszv&#64;zoszv.sk?subject=Aplikácia (audity.zoszv.sk)">zoszv&#64;zoszv.sk</a>
                        <h4 class="text-muted pt-4"><i class="fas fa-network-wired mr-3" aria-hidden="true"></i>WWW</h4>
                            <a class="text-muted"  target="_blank" href="http://www.zoszv.sk">www.zoszv.sk</a>
                    </div>
                    <div class="col-12 col-md-6 py-4 py-md-0">
                        <h4 class="text-muted"><i class="fas fa-piggy-bank mr-3"></i>Bankové spojenie</h4>
                        <dl>
                            <dt>IČO</dt><dd> 31 615 783</dd>
                            <dt>IČ DPH</dt><dd> SK 20 20 47 6337</dd>
                            <dt>Bankové spojenie</dt><dd> Všeobecná úverová banka a.s., pobočka Zvolen</dd>
                            <dt>IBAN</dt><dd> SK15 0200 0000 0000 1050 0412</dd>
                            <dt>BIC</dt><dd> SUBA SK BX</dd>
                            <dt>Číslo členstva v SOPK</dt><dd> 03 16 00 99</dd>
                        </dl>
                    </div>

                </div>
            </div>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky