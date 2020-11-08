<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/inc.require.php";

    $page = new PageZoznam();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';

    // vyberovy dotaz na data
    $data = $db->query('SELECT * FROM `31_zoznam_typ_auditu` ORDER BY LOWER(`Nazov_Norma`) ASC')->fetchAll();


ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <thead>

                            <tr>
                                <th style="width: 25px;" >P.č.</th>
                                <th>Norma</th>
                                <th>Referenčný dokument</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    foreach ($data as $key => $value):
        $id = htmlspecialchars($value['ID31']);
        $Nazov_Norma = htmlspecialchars($value['Nazov_Norma']);
        $ReferencnyDokument = htmlspecialchars($value['ReferencnyDokument']);        
?>
                            <tr id='<?= $id ?>'>
                                <td class="text-center"><?= $poradie ?>.</td>
                                <td class="pl-3"><?= $Nazov_Norma ?></td>
                                <td class="pl-3"><?= $ReferencnyDokument ?></td>                                
                            </tr>
<?php
        $poradie += 1;
    endforeach;
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

// vykreslenie stranky
$page->display();