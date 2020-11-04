<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });

    require $_SERVER['DOCUMENT_ROOT'] . "/include/inc.dBconnect.php";

    $uri = "/audit/zoznam-oblasti-auditu/";
    $list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;

    $homepage = new Page($uri , $list);

    // inicializácia konštánt formulára v prípade volania metódou GET
    $mena_vsetkych_poli = array ('oblast-auditu', 'oblast-auditu-poznamka');
    foreach ($mena_vsetkych_poli as $key => $value) {
        $validation_values[$value] = $validation_classes[$value] = $validation_feedback[$value] = '';
    }

    if (isset($_POST['submit'])) {
        // inicializácia class Validate
        $validation = new ValidatorOblastAuditu($_POST);

        $validation->odsadenie = 5;  // odsadzuje HTML kod o 5 tabulátorov
        $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
        $validation_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
        $validation_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
        $validation_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

        // if result is TRUE (1) --> save data to db  OR  reditect page
        if ($result == 1) {
            $oblast = mysqli_real_escape_string($conn, $validation_values['oblast-auditu']);
            $poznamka = mysqli_real_escape_string($conn, $validation_values['oblast-auditu-poznamka']);

            $sql = "INSERT INTO `30_zoznam_oblast_auditu` 
                    (`OblastAuditovania`, `Poznamka`) 
                    VALUES 
                    ('".$oblast."', '".$poznamka."' );";

            dBzoznam2($sql, $uri);
            header("Location: $uri");
            exit();
        }
    }

ob_start();  // Začiatok definície hlavného obsahu
?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">

        <form action="<?= $uri ?>novy" method="post">
            <div class="card" >
                <div class="card-header">
                    Vytvorenie novej položky
                </div>
                <div class="card-body register-card-body">

                <?php $meno_pola = 'oblast-auditu'; ?><!-- FORM - Oblasť -->
                    <div class="form-group ">
                        <label>Názov oblasti</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Položka">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                            <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>

                    <?php $meno_pola = 'oblast-auditu-poznamka'; ?><!-- FORM - Poznámka -->
                    <div class="form-group ">
                        <label>Poznámka</label>
                        <textarea class="form-control<?= $validation_classes[$meno_pola]; ?>" name="<?= $meno_pola; ?>"><?= $validation_values[$meno_pola]; ?> </textarea>
                        </div>
                        <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
<?= $validation_feedback[$meno_pola]; ?>
                    </div>

                </div>
                <div class="row justify-content-center">
                    <a href="<?= $uri ?>" type="submit" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                    <button type="submit" name="submit" class="btn btn-outline-warning mx-1">Uložiť</button>
                </div>
            </div>
        </form>
        </div>
    </div>

<?php
$homepage->content = ob_get_clean();  // Koniec hlavného obsahu
$homepage->display();  // vykreslenie stranky
?>