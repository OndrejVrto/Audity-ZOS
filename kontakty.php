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
                            <a href="tel:+421455302111">(00421 45) 53 02 111</a>
                        <h4 class="text-muted pt-4"><i class="far fa-envelope mr-3" aria-hidden="true"></i>E-mail</h4>
                            <a href="mailto:zoszv&#64;zoszv.sk?subject=Aplikácia (audity.zoszv.sk)">zoszv&#64;zoszv.sk</a>
                        <h4 class="text-muted pt-4"><i class="fas fa-network-wired mr-3" aria-hidden="true"></i>WWW</h4>
                            <a target="_blank" href="http://www.zoszv.sk">www.zoszv.sk</a>
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

                <hr class="my-4">
                
                <div class="row">
                    <div class="col-12">
                        <h4 class="text-muted"><i class="fas fa-map-marker-alt mr-3"></i>Poloha areálu</h4>
                        <p>
                            <span class="text-bold mr-2">GPS:</span>
                            <a target="_blank" href="https://sk.mapy.cz/zakladni?vlastni-body&ut=%C5%BDelezni%C4%8Dn%C3%A9%20opravovne%20a%20stroj%C3%A1rne%20Zvolen%20a.s.&uc=9sYHzxQQB4&ud=M%C3%B4%C5%A5ovsk%C3%A1%20cesta%20259%2F11">
                            48.5732472N, 19.1586297E</a>
                        </p>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe style='background-image: url("/dist/img/mapaZOS.png");' class="embed-responsive-item" src="https://sk.frame.mapy.cz/s/bazegekamo"></iframe>
                        </div>
                    </div>
                </div>

            </div>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky