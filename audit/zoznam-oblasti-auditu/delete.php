<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });
    
    require $_SERVER['DOCUMENT_ROOT'] . "/include/inc.dBconnect.php";

    $uri = "/audit/zoznam-oblasti-auditu/";
    $list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;

    $homepage = new Page($uri , $list);

    $id = mysqli_real_escape_string($conn, $_POST['delete']);
    
    // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá sa zmazať.
    $sql = "SELECT 
                (SELECT COUNT(*) FROM `31_zoznam_typ_auditu` WHERE `ID30_zoznam_oblast_auditu`= ".$id.")
                +
                (SELECT COUNT(*) FROM `01_certifikaty` WHERE `ID30_zoznam_oblast_auditu`= ".$id.")
            AS Pocet;";
    $pocetArray = dBzoznam($sql, $uri);
    // vytiahnutie počtu z výsledku dotazu
    $pocet = (int)$pocetArray[0]['Pocet'];

    if (isset($_POST['submit'])) {
        $sql = "DELETE FROM `30_zoznam_oblast_auditu` WHERE `ID30`=".$id.";";
        dBzoznam2($sql, $uri);
        header("Location: $uri");       
        exit();
    }

    if ($pocet > 0) {

ob_start();  // Začiatok definície hlavného obsahu
?>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">
            <div class="card" >
                <div class="card-header">
                    Upozornenie
                </div>
                <div class="card-body register-card-body">
                    <p class="h5 text-center">
                        Záznam nieje možné zmazať
                        <br>pretože sa používa
                        <br>v iných tabuľkách.
                        <br>
                        <br>Celkom: <strong><?php echo $pocet; ?>x</strong>
                    </p>
                </div>
            </div>
            <div class="row justify-content-center">
                    <a href="<?= $uri ?>" type="submit" name="vzad" class="btn btn-secondary">Späť</a>                    
                </div>
        </div>
    </div>

<?php
    } else {

    // detaily k položke
    $sql = "SELECT * FROM 30_zoznam_oblast_auditu WHERE ID30='".$id."'; ";
    $data = dBzoznam($sql, $uri);

ob_start();  // Začiatok definície hlavného obsahu
?>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">

            <form action="<?= $uri ?>delete" method="post">
                <div class="card" >
                    <div class="card-header">
                        Zmazanie položky
                    </div>
                    <div class="card-body register-card-body">
                        <p>
                            Si si istý, že chceš zmazať položku 
                            <span class="h5 text-danger"><?= htmlspecialchars($data[0]['OblastAuditovania']); ?></span>
                            ?
                        </p>
                    </div>
                </div>
                <div class="row justify-content-center">
                        <a href="<?= $uri ?>" type="submit" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                        <button type="submit" name="submit" value="<?= htmlspecialchars($_POST['delete']); ?>" class="btn btn-outline-danger mx-1">Zmazať</button>
                </div>
            </form>
        </div>
    </div>

<?php
    }

    $homepage->content = ob_get_clean();  // Koniec hlavného obsahu
    $homepage->display();  // vykreslenie stranky
?>