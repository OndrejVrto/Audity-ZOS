<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\ZoznamSkripty();

ob_start();  // Začiatok definície hlavného obsahu
?>
            <div class="container pb-5" >
                <div class="row">
                    <div class="col-12 col-md-6">
                        <h4><i class="far fa-address-card mr-3" aria-hidden="true"></i>Adresa</h4>
                            <address class="mb-0">
                                <strong>Železničné opravovne a strojárne Zvolen, a.s.</strong><br>
                                Môťovská cesta 259/11<br>
                                960 03&nbsp;&nbsp;&nbsp;Zvolen
                            </address>
                        <h4 class="pt-4"><i class="fas fa-phone-alt mr-3" aria-hidden="true"></i>Telefón</h4>
                            (00421 45) 53 02 111
                        <h4 class="pt-4"><i class="far fa-envelope mr-3" aria-hidden="true"></i>E-mail</h4>
                            <a href="mailto:zoszv&#64;zoszv.sk">zoszv&#64;zoszv.sk</a>
                        <h4 class="pt-4"><i class="fas fa-network-wired mr-3" aria-hidden="true"></i>WWW</h4>
                            <a href="http://www.zoszv.sk">www.zoszv.sk</a>
                    </div>
                    <div class="col-12 col-md-6 py-4 py-md-0">
                        <h4><i class="fas fa-piggy-bank mr-3"></i>Bankové spojenie</h4>
                        <dl>
                            <dt>IČO:</dt><dd> 31 615 783</dd>
                            <dt>IČ DPH:</dt><dd> SK 20 20 47 6337</dd>
                            <dt>Bankové spojenie:</dt><dd> Všeobecná úverová banka a.s., pobočka Zvolen</dd>
                            <dt>IBAN:</dt><dd> SK15 0200 0000 0000 1050 0412</dd>
                            <dt>BIC:</dt><dd> SUBA SK BX</dd>
                            <dt>Číslo členstva v SOPK:</dt><dd> 03 16 00 99</dd>
                        </dl>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row">
                    <div class="col-12">
                        <h4><i class="fas fa-user-astronaut mr-3"></i>Tvorcovia aplikácie</h4>
                            <a href="mailto:vrto&#64;zoszv.sk">vrto&#64;zoszv.sk</a> (kl. 181)<br>
                            <a href="mailto:hancinsky&#64;zoszv.sk">hancinsky&#64;zoszv.sk</a> (kl. 640)<br>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row">
                    <div class="col-12">
                        <h4><i class="fas fa-tty mr-3"></i>Klapky</h4>
                        <p><em>(Verzia VIS: 11-12-2020)</em></p>
                        
                        <table class='table table-sm hover compact' id='tabulka'>
                            <thead>
                                <tr>
                                    <th>Prípojka</th>
                                    <th>Priezvisko</th>
                                    <th>Meno</th>
                                    <th>Titul</th>
                                    <th>Kancelária</th>
                                    <th>Stredisko</th>
                                    <th>Mobil</th>
                                    <th>Poznámka</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    $poradie = 1;
    
    //TODO>  prerobit databazu na klapky
    $data = $db->query("SELECT *
                        FROM `50_sys_users`
                        WHERE `ID53_sys_levels` >= 3 AND `ID50` > 4
                        ORDER BY Zamestnany_OD ASC LIMIT 20;")->fetchAll();

    foreach ($data as $key => $value)
    {
        $osCislo = $id  = htmlspecialchars($value['OsobneCislo']);
        $meno           = htmlspecialchars($value['Meno']);
        $priezvisko     = htmlspecialchars($value['Priezvisko']);
        $titul          = htmlspecialchars($value['Titul']);
        $strediskoCislo = htmlspecialchars($value['Stredisko']);
        $stredisko      = htmlspecialchars($value['NazovStrediska']);
        $nastup         = htmlspecialchars($value['Zamestnany_OD']);
        $hesloStare     = htmlspecialchars($value['Password_OLD']);
        //TODO>  prerobit databazu na klapky
?>
                                <tr id='<?= $id ?>'>
                                    <td><?= $poradie ?>.</td>
                                    <td><?= $osCislo ?></td>
                                    <td><?= $meno ?></td>
                                    <td><?= $priezvisko ?></td>
                                    <td><?= $titul ?></td>
                                    <td><?= $strediskoCislo ?></td>
                                    <td><?= $stredisko ?></td>
                                    <td><?= $stredisko ?></td>
                                </tr>
<?php
        $poradie += 1;
    }
?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky