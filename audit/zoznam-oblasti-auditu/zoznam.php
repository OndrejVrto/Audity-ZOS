<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });

    $uri = "/audit/zoznam-oblasti-auditu/";
    $list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;

    $homepage = new Page($uri , $list);
    $homepage->zobrazitBublinky = true;

    // vyberovy dotaz na data
    $sql = "SELECT * FROM 30_zoznam_oblast_auditu ORDER BY LOWER(OblastAuditovania) ASC";
    $data = dBzoznam($sql, $uri);

ob_start();  // Začiatok definície hlavného obsahu
?>
<div class="row justify-content-center pb-3">
        <a href="<?= $uri ?>novy" type="submit" name="novy" class="btn btn-warning">Pridať položku</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">

        <div class="card">
            <div class="card-body p-2">
                <table class="table table-sm">
                    <thead>

                        <tr>
                            <th style="width: 15px">P.č.</th>
                            <th>Zoznam oblastí</th>
                            <th style="width: 180px"></th>
                        </tr>

                    </thead>
                    <tbody>
<?php
    $poradie = 1;
    foreach ($data as $key => $value) {
        $id = htmlspecialchars($value['ID30']);
        $oblastAuditovania = htmlspecialchars($value['OblastAuditovania']);
?>
                        <tr id="<?= $id ?>">
                            <td><?= $poradie ?>.</td>
                            <td><?= $oblastAuditovania ?></td>
                            <td>
                                <div class="btn-group">
                                    <form action="<?= $uri ?>detail" method="post">
                                        <button type="submit" name="detail" value="<?= $id ?>" class="btn btn-xs btn-outline-info">Detaily</button>
                                    </form>
                                    <form action="<?= $uri ?>edit" method="post" class="mx-1">
                                        <button type="submit" name="edit" value="<?= $id ?>" class="btn btn-xs btn-outline-secondary">Editovať</button>
                                    </form>
                                    <form action="<?= $uri ?>delete" method="post">
                                        <button type="submit" name="delete" value="<?= $id ?>" class="btn btn-xs btn-outline-danger">Zmazať</button>
                                    </form>
                                </div>
                            </td>
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
</div>

<?php
$homepage->content = ob_get_clean();  // Koniec hlavného obsahu
$homepage->display();  // vykreslenie stranky
?>