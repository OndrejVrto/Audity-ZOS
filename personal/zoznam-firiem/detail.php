<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Detail();
    $page->bodyClassExtended = 'col-11';
    $page->bodyWidthExtended = 'max-width: 1100px;';

    $id = (int)$_POST['detail'];

    $data = $db->query('SELECT * FROM `20_vstup_firma_auditora` WHERE ID20 = ?', $id)->fetchArray();

    $nazov      = vycistiText($data['NazovFirmy']);
    $popis      = vycistiText($data['Popis']);
    $adresa     = vycistiText($data['AdresaFirmy']);
    $telefon    = vycistiText($data['OficialnyTelKontakt']);
    $mail       = vycistiText($data['OficialnyEmail']);
    $www        = vycistiText($data['wwwStranka']);
    $ico        = vycistiText($data['ICO']);
    $poznamka   = vycistiText($data['Poznamka']);
    
ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

<div class="row">

    <!-- FORM - Zoznam firiem - Názov audítorskej firmy -->
    <div class="col-xl-7">
        <div class="form-group ">
            <label>Názov firmy</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?= $nazov ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-signature"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM - Zoznam firiem - ICO -->
    <div class="col-xl-2">
        <div class="form-group ">
            <label>IČO</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?= $ico ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-university"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM - Zoznam firiem - Oficiálny kontakt -->
    <div class="col-xl-3">
        <div class="form-group ">
            <label>WWW</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?= $www ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-globe fa-spin"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- /row  -->

<!-- FORM - Zoznam firiem - Popis -->
<div class="form-group ">
    <label>Popis firmy</label>
    <div class="input-group">
        <input type="text" class="form-control" value="<?= $popis ?>">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-font"></span>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <!-- FORM - Zoznam firiem - Adresa kompletná -->
    <div class="col-xl-6">
        <div class="form-group ">                            
            <label>Adresa</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?= $adresa ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-map-marked-alt"></span>
                    </div>
                </div>                
            </div>
        </div>
    </div>

    <!-- FORM - Zoznam firiem - Oficiálny kontakt -->
    <div class="col-xl-3">
        <div class="form-group ">
            <label>Telefón</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?= $telefon ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-phone"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM - Zoznam firiem - Oficiálny kontakt -->
    <div class="col-xl-3">
        <div class="form-group ">
            <label>E-mail</label>
            <div class="input-group">
                <input type="text" class="form-control" value="<?= $mail ?>">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-at"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- /row  -->

<!-- FORM - Zoznam firiem - Poznámka -->
<div class="form-group ">
    <label>Poznámka</label>
    <textarea class="form-control"><?= $poznamka ?></textarea>
</div>

<?php
if (empty($data)) {
    $upozornenie = '<p class="h4 d-inline-flex animate__animated animate__flash animate__delay-2s animate__slow animate__repeat-2">Položka <span class="mx-3 text-danger text-bold">'. vycistiText($id) .'</span> neexistuje !</p>';
    $page->content = $page->pridaj_tabulator_html($upozornenie, 7);
    ob_clean();
} else {
    $page->content = $page->pridaj_tabulator_html(ob_get_clean(), 7);  // Koniec hlavného obsahu
}

$page->display();  // vykreslenie stranky